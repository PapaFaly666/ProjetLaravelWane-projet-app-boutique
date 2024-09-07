<?php

namespace App\Events;

use App\Models\Client;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ClientCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $client;
    public $user;

    public function __construct(Client $client, User $user)
    {
        $this->client = $client;
        $this->user = $user;
    }
}
