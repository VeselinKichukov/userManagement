<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the view for editing profile.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user()
        ]);
    }

    /**
     * Update the changes made by the user.
     *
     * @param UpdateProfileRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateProfileRequest $request)
    {
        $request->user()->update(
            $request->all()
        );

        $success = true;

        return redirect()->route('profile.edit');
    }

    /**
     * Delete the current users' profile.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy( )
    {
        $user = User::find(Auth::user()->id);

        Auth::logout();

        if ($user->delete()) {
            return redirect()->route('home');
        }
    }
}
