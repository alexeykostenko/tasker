<?php

namespace App\Controllers;

class ApiController
{
    public function getTask()
    {
        $taskId = request()->id;
        die(json_encode(model('Task')->find($taskId)));
    }
}
