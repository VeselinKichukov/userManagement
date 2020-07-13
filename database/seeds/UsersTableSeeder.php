<?php

use App\Registration;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Populate the database with users and create registrations foreach
     * user on the fly using the Models relations.
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Registration::truncate();
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        factory(App\User::class, 10)
            ->create()
            ->each(function ($user) {
                for ($i = 0; $i <= 10; $i++) {
                    $user->registrations()
                        ->save(factory(App\Registration::class)
                            ->make());
                }
            });

        $registrations = Registration::all();

        foreach ($registrations as $registration) {
            $start = Carbon::parse($registration->start_time);
            $end = Carbon::parse($registration->end_time);

            $diff = $start->diffInMinutes($end);

            $registration->update([
                'duration_minutes' => $diff
            ]);
        }
    }
}
