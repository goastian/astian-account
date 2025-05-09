<?php
namespace App\Http\Controllers\Subscription;

use App\Rules\BooleanRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Subscription\Service;
use App\Http\Controllers\GlobalController;
use Elyerr\ApiResponse\Exceptions\ReportError;

class ServiceController extends GlobalController
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:administrator_service_full,administrator_service_view')->only('index');
        $this->middleware('scope:administrator_service_full,administrator_service_show')->only('show');
        $this->middleware('scope:administrator_service_full,administrator_service_create')->only('store');
        $this->middleware('scope:administrator_service_full,administrator_service_update')->only('update');
        $this->middleware('scope:administrator_service_full,administrator_service_destroy')->only('destroy');
    }

    /**
     * show all resources
     * @param \App\Models\Subscription\Service $service
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index(Service $service)
    {
        $this->checkMethod('get');

        $params = $this->filter_transform($service->transformer);

        $data = $service->query();

        $this->searchByBuilder($data, $params);

        return $this->showAllByBuilder($data, $service->transformer);
    }

    /**
     * Create a new service
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Subscription\Service $service
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Service $service)
    {

        $this->validate($request, [
            'name' => [
                'required',
                function ($attribute, $value, $fail) use ($service) {
                    $slug = $this->slug($value);
                    $model = $service->where('slug', $slug)->first();
                    if ($model) {
                        $fail(__("The :attribute already exists", ['attribute' => $attribute]));
                    }
                }
            ],
            'description' => ['required', 'max:190'],
            'group_id' => ['required', 'exists:groups,id'],
            'system' => ['nullable', new BooleanRule()],
        ]);

        $this->checkMethod('post');
        $this->checkContentType($this->getPostHeader());

        $request->merge([
            'slug' => $this->slug($request->name),
        ]);

        DB::transaction(function () use ($request, $service) {
            $service = $service->fill($request->all());
            $service->push();
        });

        return $this->showOne($service, $service->transformer, 201);
    }

    /**
     * Show resource information
     * @param \App\Models\Subscription\Service $service
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(Service $service)
    {
        $this->checkMethod('get');
        $this->checkContentType(null);

        return $this->showOne($service, $service->transformer, 201);
    }

    /**
     *  Update service resource
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Subscription\Service $service
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Service $service)
    {
        $this->validate($request, [
            'description' => ['nullable', 'max:190']
        ]);

        $this->checkMethod('put');
        $this->checkContentType($this->getUpdateHeader());

        DB::transaction(function () use ($request, $service) {

            $update = false;

            if ($request->has('description') && $service->description != $request->description) {
                $update = true;
                $service->description = $request->description;

            }

            if ($request->has('name') && $service->name != $request->name) {
                $update = true;
                $service->name = $request->name;
            }

            if ($update) {
                $service->push();
            }
        });

        return $this->showOne($service, $service->transformer, 200);
    }

    /**
     * destroy service resource
     * @param \App\Models\Subscription\Service $service
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(Service $service)
    {
        $this->checkMethod('delete');
        $this->checkContentType(null);

        throw_if($service->system, new ReportError(__("This action cannot be completed because this service is a system service and cannot be deleted."), 403));

        throw_if($service->scopes()->count() > 0, new ReportError(__("This action can't be done"), 400));

        $service->delete();

        $this->privateChannel("GroupDeleted", "Group deleted");

        return $this->showOne($service, $service->transformer);
    }
}
