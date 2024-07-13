<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.Task', function ($task, $id) {
    return (int) $task->id === (int) $id;
});
