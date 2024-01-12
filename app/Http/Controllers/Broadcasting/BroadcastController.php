<?php

namespace App\Http\Controllers\Broadcasting;

use App\Events\Broadcast\DestroyBroadcastEvent;
use App\Events\Broadcast\StoreBroadcastEvent;
use App\Http\Controllers\GlobalController as Controller;
use App\Models\Broadcasting\Broadcast;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BroadcastController extends Controller
{

    public function __construct(Broadcast $broadcast)
    {
        parent::__construct();
        $this->middleware('scope:broadcast');
        $this->middleware('transform.request:' . $broadcast->transformer)->only('store');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Broadcast $broadcast)
    {
        $params = $this->filter_transform($broadcast->transformer);

        $broadcasts = $this->search($broadcast->table, $params);

        return $this->showAll($broadcasts, $broadcast->transformer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Broadcast $broadcast)
    {
        $this->validate($request, [
            'channel' => ['required', 'max:100', 'unique:broadcasts,channel'],
            'description' => ['required', 'max:350'],
        ]);

        DB::transaction(function () use ($request, $broadcast) {
            $broadcast = $broadcast->fill($request->only('channel', 'description'));
            $broadcast->save();
        });

        StoreBroadcastEvent::dispatch();

        return $this->showOne($broadcast, $broadcast->transformer, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Broadcast $broadcast)
    {
        if ($broadcast->channel == 'auth') {
            throw new ReportError(__('es un canal por defecto del sistema y no puede ser eliminado'), 403);
        }

        $broadcast->delete();

        DestroyBroadcastEvent::dispatch();

        return $this->showOne($broadcast, $broadcast->transformer);
    }
}
