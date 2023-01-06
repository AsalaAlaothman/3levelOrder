<?php

namespace App\Listeners;

use App\Events\CheckIngriedientLevel;
use App\Mail\Reminder;
use App\Models\IngredientLevelEmails;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SendFillIngredientEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\CheckIngriedientLevel  $event
     * @return void
     */
    public function handle($event)
    {
        $ingredient = $event->ingredient;

        if ($ingredient->in_stock < ($ingredient->stock_level / 2)) {
            $this->check_record($ingredient);
        }
    }



    public function check_record($ingredient)
    {
        $is_sent = $ingredient->child->where('ingredient_id', $ingredient->id)->where('user_id', NULL)->first();
        if (!$is_sent) {
            $this->send_email($ingredient);
        }
    }

    public function send_email($ingredient)
    {
        // let type 0 => merchant
        $merchant = User::select('email')->where('type', 0)->first();
        
        Mail::to($merchant->email)->later(now(), new Reminder($merchant->email, $ingredient->name));
        $this->insert_record($ingredient);


    }


    public function insert_record($ingredient)
    {

        IngredientLevelEmails::create([
            'ingredient_id' => $ingredient->id,
        ]);
    }
}
