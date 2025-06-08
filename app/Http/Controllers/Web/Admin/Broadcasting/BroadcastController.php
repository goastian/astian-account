<?php
namespace App\Http\Controllers\Web\Admin\Broadcasting;

use Inertia\Inertia;
use App\Rules\BooleanRule;
use Illuminate\Http\Request;
use App\Rules\StringOnlyRule;
use Illuminate\Support\Facades\DB;
use App\Models\Broadcasting\Broadcast;
use App\Http\Controllers\WebController;
use Elyerr\ApiResponse\Exceptions\ReportError;

class BroadcastController extends WebController
{
    /**
     * Constructor of class
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('userCanAny:administrator_broadcast_full,administrator_broadcast_view')->only('index');
        $this->middleware('userCanAny:administrator_broadcast_full,administrator_broadcast_create')->only('store');
        $this->middleware('userCanAny:administrator_broadcast_full,administrator_broadcast_destroy')->only('destroy');
    }

    /**
     * index
     * @param \App\Models\Broadcasting\Broadcast $broadcast
     * @return mixed|\Illuminate\Http\JsonResponse|\Inertia\Response
     */
    public function index(Broadcast $broadcast)
    {
        // Retrieve params of the request
        $params = $this->filter_transform($broadcast->transformer);

        // Prepare query
        $data = $broadcast->query();

        // Search 
        $data = $this->searchByBuilder($data, $params);

        // Order by
        $data = $this->orderByBuilder($data, $params);

        if (request()->wantsJson()) {
            return $this->showAllByBuilder($data, $broadcast->transformer);
        }

        return Inertia::render("Admin/Broadcast/Index");
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
                new StringOnlyRule(),
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
            'system' => ['nullable', new BooleanRule()],
        ]);

        $request->merge([
            'slug' => $this->slug($request->name)
        ]);

        DB::transaction(function () use ($request, $broadcast) {
            $broadcast = $broadcast->fill($request->all());
            $broadcast->save();
        });


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

        $broadcast->delete();

        return $this->showOne($broadcast, $broadcast->transformer);
    }
}
