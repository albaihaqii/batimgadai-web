<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('role.admin', function ($user) {
    return in_array($user->role ?? null, ['admin', 'superadmin']);
});

Broadcast::channel('role.superadmin', function ($user) {
    return ($user->role ?? null) === 'superadmin';
});
