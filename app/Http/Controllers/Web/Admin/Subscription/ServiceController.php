<?php
namespace App\Http\Controllers\Web\Admin\Subscription;

use Inertia\Inertia;
use App\Rules\BooleanRule;
use Illuminate\Http\Request;
use App\Rules\StringOnlyRule;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\Subscription\Service;
use App\Http\Controllers\WebController;
use Elyerr\ApiResponse\Exceptions\ReportError;
use Illuminate\Database\UniqueConstraintViolationException;

class ServiceController extends WebController
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('userCanAny:administrator_service_full,administrator_service_view')->only('index');
        $this->middleware('userCanAny:administrator_service_full,administrator_service_show')->only('show');
        $this->middleware('userCanAny:administrator_service_full,administrator_service_create')->only('store');
        $this->middleware('userCanAny:administrator_service_full,administrator_service_update')->only('update');
        $this->middleware('userCanAny:administrator_service_full,administrator_service_destroy')->only('destroy');

        $this->middleware('wants.json')->only('show');
    }

    /**
     * index
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Subscription\Service $service
     * @return mixed|\Illuminate\Http\JsonResponse|\Inertia\Response
     */
    public function index(Request $request, Service $service)
    {
        // Retrieve params of the request
        $params = $this->filter_transform($service->transformer);

        // Prepare query
        $data = $service->query();

        // Eager loading
        $data = $data->with(['group', 'scopes', 'scopes.role']);

        // Filter by visibility
        if ($request->visibility) {
            $data->where('visibility', $request->visibility);
        }

        // Search data with the param request
        $data = $this->searchByBuilder($data, $params);

        // Order by
        $data = $this->orderByBuilder($data, $service->transformer);

        if (request()->wantsJson()) {
            return $this->showAllByBuilder($data, $service->transformer);
        }

        // Render vue component
        return Inertia::render("Admin/Service/Index", [
            'route' => [
                'services' => route("admin.services.index"),
                'groups' => route("admin.groups.index"),
                'roles' => route("admin.roles.index")
            ]
        ]);
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
            'name' => ['required', new StringOnlyRule(),],
            'description' => ['required', 'max:190'],
            'group_id' => ['required', 'exists:groups,id'],
            'system' => ['nullable', new BooleanRule()],
            'visibility' => ['required', Rule::in(Service::visibilities())]
        ]);

        $request->merge([
            'slug' => $this->slug($request->name),
        ]);

        DB::transaction(function () use ($request, $service) {
            try {
                $service = $service->fill($request->all());
                $service->push();
            } catch (UniqueConstraintViolationException $th) {
                throw new ReportError(__("This service cannot be registered, as it is already associated with this group."), 400);
            }
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
            'description' => ['nullable', 'max:190'],
            'visibility' => ['nullable', Rule::in(Service::visibilities())]
        ]);

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

            if ($request->has('visibility') && $service->visibility != $request->visibility) {
                $update = true;
                $service->visibility = $request->visibility;
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
        throw_if($service->system, new ReportError(__("This action cannot be completed because this service is a system service and cannot be deleted."), 403));

        throw_if($service->scopes()->count() > 0, new ReportError(__("This action can't be done"), 400));

        $service->delete();



        return $this->showOne($service, $service->transformer);
    }
}
