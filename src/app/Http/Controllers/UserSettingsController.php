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
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'preferred_language' => 'required|in:pl,en',
            'theme' => 'required|in:light,dark',
            'current_password' => 'nullable|required_with:password|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Aktualne hasło jest niepoprawne.']);
            }

            $user->password = Hash::make($validated['password']);
            $user->password_updated_at = now();
        }

        if ($request->hasFile('photo')) {
            if ($user->photo_path) {
                Storage::delete($user->photo_path);
            }

            $photoPath = $request->file('photo')->store('profile-photos', 'public');
            $user->photo_path = $photoPath;
            $user->photo_updated_at = now();
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (isset($validated['preferred_language'])) {
            $user->language = $validated['preferred_language'];
        }

        if (isset($validated['theme'])) {
            $user->theme = $validated['theme'];
        }

        $user->save();

        return redirect()->route('user.settings.edit')
            ->with('success', 'Ustawienia zostały zaktualizowane!');
    }

    public function editPhoto()
    {
        $user = Auth::user();
        return view('edit-photo', compact('user'));
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('photo')) {
            if ($user->photo_path) {
                Storage::disk('public')->delete($user->photo_path);
            }

            $path = $request->file('photo')->store('profile-photos', 'public');

            $user->photo_path = $path;
            $user->photo_updated_at = now();
            $user->save();

            return redirect()->route('user.settings.edit')
                ->with('succes', 'Photo updated');
        }

        return back()->with('error', 'Photo not updated');
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
            return back()->withErrors(['password' => 'Niepoprawne hasło.']);
        }

        if ($user->photo_path) {
            Storage::delete($user->photo_path);
        }

        Auth::logout();

        $user->delete();

        return redirect()->route('welcome')
            ->with('success', 'Twoje konto zostało usunięte!');
    }
}
