<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\User;
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

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
Broadcast::channel('leave', function () {
    return true;
});
Broadcast::channel('holiday', function () {
    return true;
});
Broadcast::channel('announcement.{toUser}', function (User $user, $toUser) {
    return (int)$user->id === (int)$toUser;
});
Broadcast::channel('companypolicy', function () {
    return true;
});
