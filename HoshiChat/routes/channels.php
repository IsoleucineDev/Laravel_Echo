<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The required channels are declared here while
| optional channels may be registered when needed.
|
*/

Broadcast::channel('chat', function () {
    return true;
});
