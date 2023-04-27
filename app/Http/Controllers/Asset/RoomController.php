<?php

namespace App\Http\Controllers\Asset;

use App\Events\Asset\DestroyRoomEvent;
use App\Events\Asset\DisableRoomEvent;
use App\Events\Asset\EnableRoomEvent;
use App\Events\Asset\StoreRoomEvent;
use App\Events\Asset\UpdateRoomEvent;
use Error;
use App\Models\Assets\Room;
use Illuminate\Http\Request;
use App\Exceptions\ReportMessage;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Room\StoreRequest;
use App\Http\Requests\Room\UpdateRequest; 
use App\Transformers\Asset\RoomTransformer;
use App\Http\Controllers\GlobalController as Controller;

class RoomController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('transform.request:' . RoomTransformer::class)->only('store', 'update');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Room $room)
    {
        $params = $this->transformFilter($room->transformer);

        $rooms = $this->search($room->view, $params);

        return $this->showAll($rooms, RoomTransformer::class);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, Room $room)
    {
        DB::transaction(function () use ($request, $room) {
            $room = $room->fill($request->all());
            $room->save();
        });

        StoreRoomEvent::dispatch($this->AuthKey());

        return $this->showOne($room, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $room = Room::withTrashed()->find($id);

        return $this->showOne($room);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Room $room)
    {
        DB::transaction(function () use ($request, $room) {
            $room = $room->fill($request->all());
            $room->push();
        });

        UpdateRoomEvent::dispatch($this->AuthKey());

        return $this->showOne($room, 201);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Room $room)
    {
        $rents = count($room->rents()->get());

        if ($rents > 0) {
            throw new ReportMessage(__("La categoria tiene recursos asociados y no puede ser eliminada"), 403);
        }

        $room->forceDelete();

        DestroyRoomEvent::dispatch($this->AuthKey());

        return $this->showOne($room);
    }

    public function disable(Room $room)
    {
        $room->delete();

        DisableRoomEvent::dispatch($this->AuthKey());

        return $this->showOne($room);
    }

    public function enable($id)
    {
        try {

            $room = Room::onlyTrashed()->where('id', $id)->first();

            $room->restore();

            EnableRoomEvent::dispatch($this->AuthKey());

            return $this->showOne($room);

        } catch (Error $e) {
            throw new ReportMessage("Error al procesar la petición", 404);

        }

    }

}
