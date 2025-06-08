<?php

namespace App\Http\Controllers\Web\Admin\Setting;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Setting\Terminal;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\WebController;
use Elyerr\ApiResponse\Exceptions\ReportError;

class TerminalController extends WebController
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('userCanAny:administrator_terminal_full,administrator_user_view')->only('index');
        $this->middleware('userCanAny:administrator_terminal_full,administrator_user_execute')->only('show');
    }

    /**
     * Summary of index
     * @param \App\Models\Setting\Terminal $terminal
     * @return mixed|\Illuminate\Http\JsonResponse|\Inertia\Response
     */
    public function index(Terminal $terminal)
    {
        // Retrieve params of the request
        $params = $this->filter_transform($terminal->transformer);

        // prepare query
        $data = $terminal->query();
        // Eager loading
        $data = $data->with(['user']);
        // Search
        $data = $this->searchByBuilder($data, $params);
        // Order by
        $data = $this->orderByBuilder($data, $terminal->transformer);

        if (request()->wantsJson()) {
            return $this->showAllByBuilder($data, $terminal->transformer);
        }

        return Inertia::render('Terminal/Index', [
            'route' => route('admin.terminals.index')
        ]);
    }


    public function store(Request $request, Terminal $terminal)
    {
        $request->validate([
            'command' => ['required', 'string', 'max:255']
        ]);

        $allowed_commands = $terminal->whiteList();

        $is_allowed = false;
        foreach ($allowed_commands as $allowed) {
            if (str_starts_with($request->command, $allowed)) {
                $is_allowed = true;
                break;
            }
        }

        if (!$is_allowed) {
            throw new ReportError(__('The command is invalid'), 400);
        }

        DB::transaction(function () use ($request, $terminal) {

            $exitCode = 0;
            $terminal = $terminal->fill($request->only('command'));
            $terminal->user_id = auth()->user()->id;

            try {

                $output = [];
                exec("cd " . base_path() . " && {$request->command} 2>&1", $output, $exitCode);

                $terminal->output = json_encode($output);
                $terminal->status = $exitCode === 0;

            } catch (\Exception $e) {
                $terminal->output = implode("\n", $e->getTrace());
                $terminal->status = $exitCode === 0;
            }

            $terminal->save();
        });

        return $this->showOne($terminal, $terminal->transformer, 201);
    }

}
