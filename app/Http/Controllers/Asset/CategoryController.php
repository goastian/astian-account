<?php

namespace App\Http\Controllers\Asset;

use Error;
use App\Models\Assets\Category;
use App\Exceptions\ReportMessage;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Category\StoreRequest;
use App\Http\Requests\Category\UpdateRequest; 
use App\Transformers\Asset\CategoryTransformer;
use App\Http\Controllers\GlobalController as Controller;

class CategoryController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('transform.request:' . CategoryTransformer::class)->only('store', 'update');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Category $category)
    {
        $categories = $category->withTrashed()->get();

        return $this->showAll($categories, CategoryTransformer::class);
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

        return $this->showOne($category, CategoryTransformer::class, 201);
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

        return $this->showOne($category);
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
            $catgegory = $category->fill($request->all());

            $category->push();
        });

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

        return $this->showOne($category);
    }

    public function disable(Category $category)
    {
        $category->delete();

        return $this->showOne($category);
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

            return $this->showOne($category);

        } catch (Error $e) {

            throw new ReportMessage("Error al procesar la petición", 404);

        }

    }
}
