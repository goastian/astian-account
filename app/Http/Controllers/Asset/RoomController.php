<?php

namespace App\Http\Controllers\Asset;

use App\Events\Asset\Room\DestroyRoomEvent;
use App\Events\Asset\Room\DisableRoomEvent;
use App\Events\Asset\Room\EnableRoomEvent;
use App\Events\Asset\Room\StoreRoomEvent;
use App\Events\Asset\Room\UpdateRoomEvent;
use App\Exceptions\ReportMessage;
use App\Http\Controllers\GlobalController as Controller;
use App\Http\Requests\Room\StoreRequest;
use App\Http\Requests\Room\UpdateRequest;
use App\Models\Assets\Room;
use Error;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{

    public function __construct(Room $room)
    {
        parent::__construct();
        $this->middleware('transform.request:' . $room->transformer)->only('store', 'update');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Room $room)
    {
        $params = $this->filter_transform($room->transformer);

        $rooms = $this->search($room->view, $params);

        return $this->showAll($rooms, $room->transformer);
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

        return $this->showOne($room, $room->transformer, 201);
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

        return $this->showOne($room, $room->transformer);
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
            if ($this->is_diferent($room->number, $request->number)) {
                $this->can_update[] = true;
                $room->number = $request->number;
            }

            if ($this->is_diferent($room->description, $request->description)) {
                $this->can_update[] = true;
                $room->description = $request->description;
            }

            if ($this->is_diferent($room->category_id, $request->category_id)) {                
                $this->can_update[] = true; 
                $room->category_id = $request->category_id;
            }

            if (in_array(true, $this->can_update)) {
                $room->push();
            }

        });

        if (in_array(true, $this->can_update)) {
            UpdateRoomEvent::dispatch($this->AuthKey());
        }

        return $this->showOne($room, $room->transformer, 201);
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

        return $this->showOne($room, $room->transformer);
    }

    public function disable(Room $room)
    {
        $room->delete();

        DisableRoomEvent::dispatch($this->AuthKey());

        return $this->showOne($room, $room->transformer);
    }

    public function enable($id)
    {
        try {

            $room = Room::onlyTrashed()->where('id', $id)->first();

            $room->restore();

            EnableRoomEvent::dispatch($this->AuthKey());

            return $this->showOne($room, $room->transformer);
        } catch (Error $e) {
            throw new ReportMessage("Error al procesar la petición", 404);
        }
    }
}
