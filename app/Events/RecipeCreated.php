<?php

namespace App\Events;

use App\Models\Recipe;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RecipeCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $recipe;

    public function __construct(Recipe $recipe)
    {
        $this->recipe = $recipe;

    }


    public function broadcastOn(): channel
    {
       return new PrivateChannel('recipe');
    }

    public function broadcastWith(): array{
        return ['recipe' => $this->recipe];
    }
//
//    public function broadcastQueue():string{
//        return 'default';
//    }
}
