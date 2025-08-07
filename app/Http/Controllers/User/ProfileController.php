<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
        ]);

        $user = Auth::user();
        $user->update($validated);

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Kullanıcının oturum açtığını kontrol et
        if (!$request->user()) {
            return redirect()->route('login')->with('error', 'You must be logged in to delete your account.');
        }

        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        // Hesap silme işlemi
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('status', 'profile-deleted');
    }

    public function deactivate(Request $request)
    {
        // Kullanıcının oturum açtığını kontrol et
        if (!$request->user()) {
            return redirect()->route('login')->with('error', 'You must be logged in to deactivate your account.');
        }

        $user = $request->user();
        $user->update(['is_active' => 0]);

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('status', 'Your account has been deactivated.');
    }

    public function activate(Request $request)
    {
        // Kullanıcının oturum açtığını kontrol et
        if (!$request->user()) {
            return redirect()->route('login')->with('error', 'You must be logged in to activate your account.');
        }

        $user = $request->user();
        if ($user->is_active == 0) {
            $user->update(['is_active' => 1]);
        }

        return redirect()->route('profile.edit')->with('status', 'Your account has been activated.');
    }

}
