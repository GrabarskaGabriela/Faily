<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:20',
            'age' => 'nullable|integer|min:1|max:120',
            'description' => 'nullable|string',
        ]);

        $user->update($validated);

        return redirect()->route('profile.show')->with('success', 'Profil został zaktualizowany.');
    }
    public function edit(Request $request): View  // Poprawiona deklaracja
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function editPhoto()
    {
        return view('profile.edit-photo', ['user' => Auth::user()]);
    }

    public function updatePhoto(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'photo' => 'required|image|max:2048', // max 2MB
        ]);

        // Usuń stare zdjęcie jeśli istnieje
        if ($user->photo_path && Storage::disk('public')->exists($user->photo_path)) {
            Storage::disk('public')->delete($user->photo_path);
        }

        // Zapisz nowe zdjęcie
        $photoPath = $request->file('photo')->store('profile-photos', 'public');

        $user->update([
            'photo_path' => $photoPath,
            'photo_updated_at' => now(),
        ]);

        return redirect()->route('profile.show')->with('success', 'Zdjęcie profilowe zostało zaktualizowane.');
    }

    public function toggle2FA(Request $request)
    {
        $user = Auth::user();
        $user->two_factor_enabled = $request->input('value');
        $user->save();

        return response()->json(['success' => true]);
    }

    public function toggleNotifications(Request $request)
    {
        $user = Auth::user();
        $user->email_notifications = $request->input('value');
        $user->save();

        return response()->json(['success' => true]);
    }
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

}
