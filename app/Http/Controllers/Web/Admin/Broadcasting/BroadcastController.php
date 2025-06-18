<?php
namespace App\Http\Controllers\Web\Admin\Broadcasting;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\WebController;
use App\Repositories\BroadcastRepository;
use App\Http\Requests\Broadcast\StoreRequest;

class BroadcastController extends WebController
{

    /**
     * Repository instance
     * @var 
     */
    public $repository;

    /**
     * Constructor
     * @param \App\Repositories\BroadcastRepository $broadcastRepository
     */
    public function __construct(BroadcastRepository $broadcastRepository)
    {
        parent::__construct();
        $this->repository = $broadcastRepository;
        $this->middleware('userCanAny:administrator_broadcast_full,administrator_broadcast_view')->only('index');
        $this->middleware('userCanAny:administrator_broadcast_full,administrator_broadcast_create')->only('store');
        $this->middleware('userCanAny:administrator_broadcast_full,administrator_broadcast_destroy')->only('destroy');
    }

    /**
     * Show the resource
     * @param \Illuminate\Http\Request $request
     * @return \Elyerr\ApiResponse\Assets\JsonResponser|\Inertia\Response
     */
    public function index(Request $request)
    {
        if (request()->wantsJson()) {
            return $this->repository->search($request);
        }

        return Inertia::render("Admin/Broadcast/Index");
    }

    /**
     * Store a newly created resource in storage.
     * @param \App\Http\Requests\Broadcast\StoreRequest $request
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function store(StoreRequest $request)
    {
        return $this->repository->create($request->toArray());
    }

    /**
     * Delete specific resource
     * @param string $id
     * @return \Elyerr\ApiResponse\Assets\JsonResponser
     */
    public function destroy(string $id)
    {
        return $this->repository->delete($id);
    }
}
