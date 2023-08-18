<?php

namespace App\Http\Controllers\User;

use App\Events\Booking\Client\UpdateBookingRoomClientEvent;
use App\Http\Controllers\GlobalController as Controller;
use App\Http\Requests\Client\UpdateRequest;
use App\Models\User\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Client $client)
    {
        $params = $this->filter_transform($client->transformer);

        $clients = $this->search($client->table, $params);

        return $this->showAll($clients, $client->transformer);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        return $this->showOne($client, $client->transformer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Client $client)
    {
        DB::transaction(function () use ($request, $client) {

            if ($this->is_diferent($client->name, $request->name)) {
                $this->can_update[] = true;
                $client->name = $request->name;
            }

            if ($this->is_diferent($client->last_name, $request->last_name)) {
                $this->can_update[] = true;
                $client->last_name = $request->last_name;
            }

            if ($this->is_diferent($client->document, $request->document)) {
                $this->can_update[] = true;
                $client->document = $request->document;
            }

            if ($this->is_diferent($client->number, $request->number)) {
                $this->can_update[] = true;
                $client->number = $request->number;
            }

            if ($this->is_diferent($client->city, $request->city)) {
                $this->can_update[] = true;
                $client->city = $request->city;
            }

            if ($this->is_diferent($client->country, $request->country)) {
                $this->can_update[] = true;
                $client->country = $request->country;
            }

            if ($this->is_diferent($client->email, $request->email)) {
                $this->can_update[] = true;
                $client->email = $request->email;
            }

            if ($this->is_diferent($client->phone, $request->phone)) {
                $this->can_update[] = true;
                $client->phone = $request->phone;
            }

            if (in_array(true, $this->can_update)) {
                $client->push();
            }

        });

        if (in_array(true, $this->can_update)) {
            UpdateBookingRoomClientEvent::dispatch($this->AuthKey());
        }

        return $this->showOne($client, $client->transformer, 201);
    }

}
