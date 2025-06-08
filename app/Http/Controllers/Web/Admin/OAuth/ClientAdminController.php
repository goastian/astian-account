<?php

namespace App\Http\Controllers\Web\Admin\OAuth;

use Inertia\Inertia;
use App\Rules\BooleanRule;
use App\Models\OAuth\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\ClientRepository;
use App\Http\Controllers\WebController;
use Elyerr\ApiResponse\Exceptions\ReportError;

class ClientAdminController extends WebController
{
    /**
     * The client repository instance.
     *
     * @var \Laravel\Passport\ClientRepository
     */
    protected $clients;

    public function __construct(ClientRepository $clients)
    {
        parent::__construct();
        $this->clients = $clients;
        $this->middleware('userCanAny:administrator_application_full,administrator_application_view')->only('index');
        $this->middleware('userCanAny:administrator_application_full,administrator_application_show')->only('show');
        $this->middleware('userCanAny:administrator_application_full,administrator_application_create')->only('store');
        $this->middleware('userCanAny:administrator_application_full,administrator_application_update')->only('update');
        $this->middleware('userCanAny:administrator_application_full,administrator_application_destroy')->only('destroy');
    }

    /**
     * 
     */
    public function index(Client $client)
    {
        // Retrieve params of the request
        $params = $this->filter_transform($client->transformer);

        // Prepare query
        $data = $client->query();

        // Filter entries without a user ID and that do not belong to a personal access client
        $data = $data->whereNull('user_id')->where('personal_access_client', false);

        // Search 
        $data = $this->searchByBuilder($data, $params);
        
        // Order by
        $data = $this->orderByBuilder($data, $client->transformer);

        if (request()->wantsJson()) {
            return $this->showAllByBuilder($data, $client->transformer);
        }

        return Inertia::render("Admin/Clients/Index", [
            "route" => route("admin.clients.index")
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Client $client)
    {
        $this->validate($request, [
            'name' => 'required|max:191',
            'redirect' => [
                'required',
                function ($attribute, $value, $fail) {
                    $urls = explode(',', $value);
                    foreach ($urls as $url) {
                        $url = trim($url);
                        if (!preg_match('/^(https?:\/\/)/i', $url)) {
                            $fail(__('Each URL in :attribute must start with http or https.', ['attribute' => $attribute]));
                            break;
                        }
                    }

                }
            ],
            'confidential' => new BooleanRule(),
        ]);

        $client = $this->clients->create(
            null,
            $request->name,
            $request->redirect,
            null,
            false,
            false,
            (bool) $request->confidential
        );

        return $this->showOne($client, $client->transformer, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        if ($client->personal_access_client) {
            throw new ReportError(__('Not found'), 404);
        }

        return $this->showOne($client, $client->transformer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        if ($client->personal_access_client) {
            throw new ReportError(__('Not found'), 404);
        }

        $this->validate($request, [
            'name' => 'nullable|max:191',
            'redirect' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (!empty($value)) {
                        $urls = explode(',', $value);
                        foreach ($urls as $url) {
                            $url = trim($url);
                            if (!preg_match('/^(https?:\/\/)/i', $url)) {
                                $fail(__('Each URL in :attribute must start with http or https.', ['attribute' => $attribute]));
                                break;
                            }
                        }
                    }
                }
            ],
        ]);

        DB::transaction(function () use ($request, $client) {
            $updated = false;

            if ($this->is_different($client->name, $request->name)) {
                $client->name = $request->name;
                $updated = true;
            }

            if ($this->is_different($client->redirect, $request->redirect)) {
                $client->redirect = $request->redirect;
                $updated = true;
            }

            if ($this->is_different($client->revoked, $request->revoked)) {
                $client->revoked = $request->revoked;
                $client->tokens()->update(['revoked' => true]);
                $updated = true;
            }

            if ($updated) {
                $client->push();
            }
        });

        return $this->showOne($client, $client->transformer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        if ($client->personal_access_client) {
            throw new ReportError(__('Not found'), 404);
        }

        $client->tokens()->update(['revoked' => true]);

        $client->delete();

        return $this->showOne($client, $client->transformer);
    }
}
