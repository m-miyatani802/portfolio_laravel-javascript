<?php

namespace App\Listeners;

use App\Models\FavoritesUser;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class logSuccessfulLogin
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
     * @param  \Illuminate\Auth\Events\Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $user_id = Auth::id();
        $user_data = [
            'user_id' => $user_id,
            'mylist' => DB::table('mylists')->where('user_id', $user_id)->get(),
            'favorites_users' => FavoritesUser::where('user_id', $user_id)->get(),
        ];
        session()->put('login', $user_data);
    }
}
