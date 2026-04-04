<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('orders', function () {
    return true;
});

Broadcast::channel('trips', function () {
    return true;
});

Broadcast::channel('drivers', function () {
    return true;
});

