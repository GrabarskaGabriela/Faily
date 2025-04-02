<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit()
    {
        $user = Auth::user();
        return view('user.settings', compact('user'));
    }


    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'preferred_language' => 'required|in:pl,en',
            'theme' => 'required|in:light,dark',
            'current_password' => 'nullable|required_with:password|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'The current password is incorrect.']);
            }

            $user->password = Hash::make($validated['password']);
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::delete('public/avatars/' . basename($user->avatar));
            }

            $avatarPath = $request->file('avatar')->store('public/avatars');
            $user->avatar = Storage::url($avatarPath);
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->preferred_language = $validated['preferred_language'];
        $user->theme = $validated['theme'];
        $user->save();

        return redirect()->route('user.settings.edit')
            ->with('success', 'Settings have been updated!');
    }

    public function showDeleteForm()
    {
        return view('user.delete');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Incorrect password.']);
        }

        if ($user->avatar) {
            Storage::delete('public/avatars/' . basename($user->avatar));
        }

        Auth::logout();

        $user->delete();

        return redirect()->route('welcome')
            ->with('success', 'Your account has been deleted!.');
    }
}
