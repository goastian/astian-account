<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Setting\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stevebauman\Purify\Facades\Purify;

class AppController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api')->except('index', 'show');
        $this->middleware('scope:assets')->except('index', 'show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(App $app)
    {
        $params = $this->filter_transform($app->transformer);
        $apps = $this->search($app->table, $params);

        return $this->showAll($apps, $app->transformer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, App $app)
    {
        $this->validate($request, [
            'name' => ['required', 'max:150', 'unique:apps,name'],
            'url' => ['required', 'url:http,https', 'max:150', 'unique:apps,url'],
            'icon' => ['nullable', 'max:150', 'unique:apps,icon'],
            'description' => ['nullable', 'max:1000'],
        ]);

        DB::transaction(function () use ($request, $app) {

            $app = $app->fill($request->except('icon'));
            if ($app->icon) {
                $app->icon = Purify::config('icons')->clean($request->icon);
            }
            $app->save();
            $this->publicChannel("AppCreated", "New app created");

        });

        return $this->showOne($app, $app->transformer, 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(App $app)
    {
        return $this->showOne($app, $app->transformer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, App $app)
    {
        $this->validate($request, [
            'name' => ['nullable', 'max:150', 'unique:apps,name,' . $request->user()->id],
            'url' => ['nullable', 'url:http,https', 'max:150', 'unique:apps,url,' . $request->user()->id],
            'icon' => ['nullable', 'max:150', 'unique:apps,icon,' . $request->user()->id],
            'description' => ['nullable', 'max:1000'],
        ]);

        DB::transaction(function () use ($request, $app) {
            $updated = false;
            if ($this->is_different($app->name, $request->name)) {
                $updated = true;
                $app->name = $request->name;
            }

            if ($this->is_different($app->url, $request->url)) {
                $updated = true;
                $app->url = $request->url;
            }

            if ($this->is_different($app->url, $request->url)) {
                $updated = true;
                $app->icon = $request->icon;
            }

            if ($this->is_different($app->icon, $request->icon)) {
                $updated = true;
                $app->icon = Purify::config('icons')->clean($request->icon);
            }

            if ($updated) {
                $app->push();
                $this->publicChannel("AppUpdated", "App updated");
            }

        });

        return $this->showOne($app, $app->transformer, 201);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(App $app)
    {
        DB::transaction(function () use ($app) {

            $app->delete();
            $this->publicChannel("AppDeleted", "App Deleted");

        });

        return $this->show($app, $app->transformer);
    }
}
