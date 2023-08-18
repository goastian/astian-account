<?php

namespace App\Http\Controllers\Account;

use App\Events\Accounting\StoreAccountingEvent;
use App\Events\Accounting\UpdateAccountingEvent;
use App\Exceptions\ReportMessage;
use App\Http\Controllers\GlobalController as Controller;
use App\Http\Requests\Accounting\StoreRequest;
use App\Http\Requests\Accounting\UpdateRequest;
use App\Models\Account\Accounting;
use App\Transformers\Accounting\AccountingTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('transform.request:' . AccountingTransformer::class)->only('store', 'update');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Accounting $accounting)
    {
        $params = $this->filter_transform($accounting->transformer);

        $data = $this->search($accounting->table, $params);

        return $this->showAll($data, $accounting->transformer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, Accounting $accounting)
    {
        DB::transaction(function () use ($request, $accounting) {
            $accounting = $accounting->fill($request->only('description', 'price', 'type', 'method'));
            $accounting->code = "A" . $this->generateUniqueCode(0);
            $accounting->save();

        });

        StoreAccountingEvent::dispatch($this->AuthKey());

        return $this->showOne($accounting, $accounting->transformer, 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Accounting $accounting)
    {
        if (substr($accounting->code, 0, 2) != "A0") {
            throw new ReportMessage(__("este elemento no puede ser actualizado"), 403);
        }

        DB::transaction(function () use ($request, $accounting) {
            
            if ($this->is_diferent($accounting->description, $request->description)) {
                $this->can_update[] = true;
                $accounting->description = $request->description;
            }

            if ($this->is_diferent($accounting->price, $request->price)) {
                $this->can_update[] = true;
                $accounting->price = $request->price;
            }

            if ($this->is_diferent($accounting->type, $request->type)) {
                $this->can_update[] = true;
                $accounting->type = $request->type;
            }

            if ($this->is_diferent($accounting->method, $request->method)) {
                $this->can_update[] = true;
                $accounting->method = $request->method;
            }

            if (in_array(true, $this->can_update)) {
                $accounting->push();
            }

        });

        if (in_array(true, $this->can_update)) {
            UpdateAccountingEvent::dispatch($this->AuthKey());
        }

        return $this->showOne($accounting, $accounting->transformer, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
