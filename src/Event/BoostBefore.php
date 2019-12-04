<?php

namespace T8891\LineBoost\Event;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class BoostBefore
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $id;
    public $lineId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($id, $lineId)
    {
        $this->id = $id;
        $this->lineId = $lineId;
    }
}
