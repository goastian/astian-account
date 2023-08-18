<?php

namespace App\Http\Controllers\Asset;

use App\Events\Asset\Category\DestroyCategoryEvent;
use App\Events\Asset\Category\DisableCategoryEvent;
use App\Events\Asset\Category\EnableCategoryEvent;
use App\Events\Asset\Category\StoreCategoryEvent;
use App\Events\Asset\Category\UpdateCategoryEvent;
use App\Exceptions\ReportMessage;
use App\Http\Controllers\GlobalController as Controller;
use App\Http\Requests\Category\StoreRequest;
use App\Http\Requests\Category\UpdateRequest;
use App\Models\Assets\Category;
use Error;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{

    public function __construct(Category $category)
    {
        parent::__construct();
        $this->middleware('transform.request:' . $category->transformer)->only('store', 'update');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Category $category)
    {
        $params = $this->filter_transform($category->transformer);

        $categories = $this->search($category->table, $params);

        return $this->showAll($categories, $category->transformer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(StoreRequest $request, Category $category)
    {
        DB::transaction(function () use ($request, $category) {

            $category = $category->fill($request->all());

            $category->save();
        });

        StoreCategoryEvent::dispatch($this->AuthKey());

        return $this->showOne($category, $category->transformer, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        $category = Category::withTrashed()->find($id);

        return $this->showOne($category, $category->transformer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(UpdateRequest $request, Category $category)
    {
        DB::transaction(function () use ($request, $category) {

            if ($this->is_diferent($category->name, $request->name)) {
                $this->can_update[] = true;
                $category->name = $request->name;
            }
            if ($this->is_diferent($category->price, $request->price)) {
                $this->can_update[] = true;
                $category->price = $request->price;
            }

            if ($this->is_diferent($category->air_conditionar, $request->air_conditionar)) {
                $this->can_update[] = true;
                $category->air_conditionar = $request->air_conditionar;
            }

            if ($this->is_diferent($category->tv, $request->tv)) {
                $this->can_update[] = true;
                $category->tv = $request->tv;
            }

            if ($this->is_diferent($category->bathroom, $request->bathroom)) {
                $this->can_update[] = true;
                $category->bathroom = $request->bathroom;
            }

            if ($this->is_diferent($category->hot_water, $request->hot_water)) {
                $this->can_update[] = true;
                $category->hot_water = $request->hot_water;
            }

            if ($this->is_diferent($category->cold_water, $request->cold_water)) {
                $this->can_update[] = true;
                $category->cold_water = $request->cold_water;
            }

            if ($this->is_diferent($category->wifi, $request->wifi)) {
                $this->can_update[] = true;
                $category->wifi = $request->wifi;
            }

            if ($this->is_diferent($category->fan, $request->fan)) {
                $this->can_update[] = true;
                $category->fan = $request->fan;
            }

            if (in_array(true, $this->can_update)) {
                $category->push();
            }
        });

        if (in_array(true, $this->can_update)) {
            UpdateCategoryEvent::dispatch($this->AuthKey());
        }

        return $this->showOne($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $rooms = count($category->rooms()->get());
        $calendars = count($category->calendar()->get());

        if ($rooms > 0 or $calendars > 0) {
            throw new ReportMessage(__("La categoria tiene recursos asociados y no puede ser eliminada"), 403);
        }

        $category->forceDelete();

        DestroyCategoryEvent::dispatch($this->AuthKey());

        return $this->showOne($category, $category->transformer);
    }

    public function disable(Category $category)
    {
        $category->delete();

        DisableCategoryEvent::dispatch($this->AuthKey());

        return $this->showOne($category, $category->transformer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function enable($id)
    {
        try {

            $category = Category::onlyTrashed()->find($id);

            $category->restore();

            EnableCategoryEvent::dispatch($this->AuthKey());

            return $this->showOne($category, $category->transformer);

        } catch (Error $e) {

            throw new ReportMessage("Error al procesar la petición", 404);

        }

    }
}
