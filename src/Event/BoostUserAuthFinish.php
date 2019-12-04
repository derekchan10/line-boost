<?php

namespace T8891\LineBoost\Event;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class BoostUserAuthFinish
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $id;
    public $response;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($id, $response)
    {
        $this->id = $id;
        $this->response = $response;
    }
}
