<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function me()
    {
        return new  UserResource(auth()->user());
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes',
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'age' => 'nullable|integer|min:1|max:120',
            'description' => 'nullable|string',
            'language' => 'nullable|string|in:en,pl,de,uk',
            'theme' => 'nullable|string|in:light,dark',
        ]);

        $user->update($validated);

        return new UserResource($user);
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = auth()->user();

        if ($user->photo_path)
        {
            Storage::disk('public')->delete($user->photo_path);
        }

        $path = $request->file('photo')->store('profile-photos', 'public');

        $user->photo_path = $path;
        $user->photo_updated_at = now();
        $user->save();

        return new UserResource($user);
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            return response()->json([
                'message' => 'Current password is incorrect.',
                'errors' => [
                    'current_password' => ['Current password is incorrect.']
                ]
            ], 422);
        }

        $user->password = Hash::make($validated['password']);
        $user->password_updated_at = now();
        $user->save();

        return response()->json(['message' => 'Password has been changed successfully.']);
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'email_notifications' => 'sometimes|boolean',
            'two_factor_enabled' => 'sometimes|boolean',
        ]);

        $user = Auth::user();
        $user->update($validated);

        return new UserResource($user);
    }
}
