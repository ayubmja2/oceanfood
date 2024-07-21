<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('recipes', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
