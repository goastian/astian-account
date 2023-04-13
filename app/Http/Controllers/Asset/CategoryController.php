<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\GlobalController as Controller;
use App\Http\Requests\Category\StoreRequest;
use App\Http\Requests\Category\UpdateRequest;
use App\Models\Book\Category;
use App\Transformers\Asset\CategoryTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $categories = $category->all();

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

    public function show(Category $category)
    {
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
        $category->delete();

        return $this->showOne($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $category = Category::onlyTrashed()->find($id);

        if (isset($category)) {
            $category->restore();
            return $this->showOne($category);
        }

        return $this->message(['data' => [
            "message" => "El recurso ha sido habilitado",
        ]]);
    }
}
