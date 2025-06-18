<?php

namespace App\Http\Controllers\Web\Admin\Setting;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\WebController;
use App\Repositories\TerminalRepository;

class TerminalController extends WebController
{
    /**
     * Terminal repository
     * @var TerminalRepository
     */
    public $repository;

    public function __construct(TerminalRepository $terminalRepository)
    {
        parent::__construct();
        $this->repository = $terminalRepository;
        $this->middleware('userCanAny:administrator_terminal_full,administrator_user_view')->only('index');
        $this->middleware('userCanAny:administrator_terminal_full,administrator_user_execute')->only('show');
    }

    /**
     * Show resources
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse|\Inertia\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return $this->repository->search($request);
        }

        return Inertia::render('Terminal/Index', [
            'route' => route('admin.terminals.index')
        ]);
    }

    /**
     * Execute new command
     * @param \Illuminate\Http\Request $request
     */
    public function store(Request $request)
    {
        $request->validate([
            'command' => ['required', 'string', 'max:255']
        ]);

        return $this->repository->create($request->toArray());
    }
}
