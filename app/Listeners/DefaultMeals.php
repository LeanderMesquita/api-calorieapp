<?php

namespace App\Listeners;

use App\Models\Meal;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DefaultMeals
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $user = $event->user;
        $defaultMeals = [
            ['name' => 'breakfast', 'entries_limit' => 3],
            ['name' => 'lunch', 'entries_limit' => 3],
            ['name' => 'snack', 'entries_limit' => 5],
            ['name' => 'dinner', 'entries_limit' => 3],
        ];

        foreach($defaultMeals as $meal){
            $user->meals()->create($meal);
        }
    }
}
