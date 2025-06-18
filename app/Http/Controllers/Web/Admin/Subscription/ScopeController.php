<?php
namespace App\Http\Controllers\Web\Admin\Subscription;

use Illuminate\Http\Request;
use App\Repositories\ScopeRepository;
use App\Http\Controllers\WebController;

class ScopeController extends WebController
{
    /**
     * Repository
     * @var ScopeRepository
     */
    public $repository;

    /**
     * Construct
     * @param \App\Repositories\ScopeRepository $scopeRepository
     */
    public function __construct(ScopeRepository $scopeRepository)
    {
        parent::__construct();
        $this->repository = $scopeRepository;
        $this->middleware('userCanAny:administrator_scope_full,administrator_scope_view')->only('index');
        $this->middleware('wants.json');
    }

    /**
     * Show the all scope
     * @param \Illuminate\Http\Request $request
     */
    public function index(Request $request)
    {
        return $this->repository->search($request);
    }
}
