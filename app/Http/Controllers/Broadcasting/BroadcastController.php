<?php

namespace App\Http\Controllers\Broadcasting;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use App\Models\Broadcasting\Broadcast;
use Elyerr\ApiResponse\Exceptions\ReportError;
use App\Http\Controllers\GlobalController as Controller;

class BroadcastController extends Controller
{
    /**
     * Constructor of class
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:administrator_channel_full,administrator_channel_view')->only('index');
        //$this->middleware('scope:administrator_channel_full,administrator_channel_show')->only('show');
        $this->middleware('scope:administrator_channel_full,administrator_channel_create')->only('store');
        //$this->middleware('scope:administrator_channel_full,administrator_channel_update')->only('update');
        $this->middleware('scope:administrator_channel_full,administrator_channel_destroy')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Broadcast $broadcast)
    {
        $this->checkMethod('get');

        $params = $this->filter_transform($broadcast->transformer);

        $data = $broadcast->query();

        $data = $this->searchByBuilder($data, $params);
        $data = $this->orderByBuilder($data, $params);

        return $this->showAllByBuilder($data, $broadcast->transformer);
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
            'name' => [
                'required',
                'max:100',
                function ($attribute, $value, $fail) use ($broadcast) {
                    $slug = $this->slug($value);
                    $model = $broadcast->where('slug', $slug)->first();

                    if ($model) {
                        $fail(__("The :attribute has been registered", ['attribute' => $attribute]));
                    }
                }
            ],
            'description' => ['required', 'max:350'],
            'system' => ['nullable', 'boolean'],
        ]);

        $this->checkMethod('post');
        $this->checkContentType($this->getPostHeader());

        $request->merge([
            'slug' => $this->slug($request->name)
        ]);

        DB::transaction(function () use ($request, $broadcast) {
            $broadcast = $broadcast->fill($request->all());
            $broadcast->save();
        });

        $this->privateChannel("StoreBroadcastEvent", "New channel created");

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
        throw_if($broadcast->system, new ReportError(__("This service cannot be deleted because it is a system service."), 403));

        collect(Broadcast::channelsByDefault())->map(function ($value, $key) use ($broadcast) {
            if ($value == $broadcast->channel) {
                throw new ReportError(__("This action cannot be completed because this channel is a system channel and cannot be deleted."), 400);
            }
        });

        $this->checkMethod('delete');
        $this->checkContentType(null);

        $broadcast->delete();

        $this->privateChannel("DestroyBroadcastEvent", "Channel deleted");

        return $this->showOne($broadcast, $broadcast->transformer);
    }
}
