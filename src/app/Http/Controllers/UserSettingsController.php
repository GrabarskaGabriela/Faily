<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Services\Interfaces\UserServiceInterface;

class UserSettingsController extends Controller
{
    protected $userService;
    public function __construct(UserServiceInterface $userService)
    {
        $this->middleware('auth');
        $this->userService = $userService;
    }

    public function edit()
    {
        $user = Auth::user();
        return view('user.settings', compact('user'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore(Auth::id()),
            ],
            'photo_file' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'current_password' => 'nullable|required_with:password|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        try {
            if ($request->filled('current_password')) {
                $this->userService->updatePassword([
                    'current_password' => $request->current_password,
                    'password' => $request->password
                ], Auth::id());
            }

            $photo = $request->hasFile('photo_file') ? $request->file('photo_file') : null;
            $this->userService->updateProfile($validated, Auth::id(), $photo);

            return redirect()->route('user.settings.edit')
                ->with('success', 'Settings have been updated!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }


    public function editPhoto()
    {
        $user = Auth::user();
        return view('edit-photo', compact('user'));
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo_file' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            $this->userService->updatePhoto($request->file('photo_file'), Auth::id());
            return redirect()->route('user.settings.edit')
                ->with('success', 'Photo updated');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
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

        try {
            $this->userService->deleteAccount($request->password, Auth::id());

            Auth::logout();

            return redirect()->route('welcome')
                ->with('success', 'Your account has been deleted!');
        } catch (\Exception $e) {
            return back()->withErrors(['password' => $e->getMessage()]);
        }
    }
}
