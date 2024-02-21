<?php

namespace App\Http\Controllers\Auth;

use App\Events\Client\RemoveSessionEvent;
use App\Http\Controllers\Controller as Controller;
use App\Models\Auth\Session;
use Illuminate\Http\Request;

class SessionController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:web');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Session $session)
    {
        $sessions = $session->where('user_id', request()->user()->id)->get();

        return $this->showAll($sessions, $session->transformer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $session = Session::find($id);

        $session->delete();

        RemoveSessionEvent::dispatch();

        return $this->message(__("Session closed " . date('Y-m-d H:i:s', strtotime(now()))), 200);
    }
}
