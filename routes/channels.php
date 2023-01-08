<?php

use App\Models\Auth;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('spondylus', function ($user) {
    return $user->id == request()->user()->id;
});

Broadcast::channel('spondylus.{id}', function ($user, $id) {
    return $user->id == $id;
});
