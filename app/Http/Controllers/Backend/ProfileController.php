<?php
namespace App\Http\Controllers\Backend;

use Auth;
use Hash;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Helpers\ImageUploadHelper;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:staff update profile')->only('profile');
        $this->middleware('permission:staff update password')->only('showChangePasswordForm','changePassword');
        
    }
    public function profile(UpdateProfileRequest $request)
    {
        $user = Auth::user();

        if ($request->isMethod('post')) {
            // Handle image upload if exists
            $imagePath = $user->img; // Keep old image by default
            if ($request->hasFile('img')) {
                // Delete old image if it exists
                if ($user->img) {
                    ImageUploadHelper::deleteImage($user->img, 'employees');
                }
                // Upload new image
                $imagePath = ImageUploadHelper::uploadImage($request, 'img', 'employees', 300, 300);
            }

            // Update user profile
            $user->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'img' => $imagePath,
            ]);

            return redirect()->back()->with('success', 'Profile updated successfully!');
        }

        return view('settings.profile', [
            'user' => $user, // Pass user data to the view
        ]);
    }

    public function showChangePasswordForm()
    {
        return view('settings.change-password');
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        // Check if current password matches the logged-in user's password
        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        // Update the user's password
        Auth::user()->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('user.change-password')->with('success', 'Password changed successfully!');
    }
}

