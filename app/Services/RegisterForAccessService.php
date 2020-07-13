<?php

namespace App\Services;


use App\Registration;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class RegisterForAccessService
{
    /**
     * Go through the process of registering for access.
     *
     * @param $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function registerAccess($request)
    {
        if ($request->has('register')) {
            if ($request->input('register') === 'true') {
                if ($this->checkForDuplication()) {
                    $this->registerUser();
                }
                return $this->retrieveView($registered = true);
            }
            if ($request->input('register') === 'false') {
                if (!$this->checkForDuplication()) {
                    $this->unregisterUser();
                }
                return $this->retrieveView($registered = false);
            }
        } else {
            if (!$this->checkForDuplication()) {

                return $this->retrieveView($registered = true);
            }
        }
        return $this->retrieveView($registered = null);
    }

    /**
     * Check if there is already an opened "registration" in our records.
     *
     * @return bool
     */
    public function checkForDuplication()
    {
        if (Registration::whereUserId(Auth::user()->id)
            ->whereEndTime(null)
            ->first()) {
            return false;
        }
        return true;
    }

    /**
     * Persist the registration to the database.
     */
    public function registerUser()
    {
        Registration::create([
            'user_id' => Auth::user()->id,
            'start_time' => Carbon::now()
        ]);
    }

    /**
     * Unregister the currently "registered for access" user.
     *
     */
    public function unregisterUser()
    {
        $currentRegistration = Registration::whereUserId(Auth::user()->id)
            ->whereEndTime(null)
            ->first();

        $duration = Carbon::now()->diffInMinutes($currentRegistration->start_time);

        $currentRegistration->update(['end_time' => Carbon::now(), 'duration_minutes' => $duration]);
    }

    /**
     * Retrieve the view with (un)register button.
     *
     * @param $registered
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function retrieveView($registered)
    {
        return view('register', compact('registered'));
    }
}
