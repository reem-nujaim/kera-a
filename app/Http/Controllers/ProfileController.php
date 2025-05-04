<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function show(Request $request): View
    {
        return view('admin.profile', [
            'user' => $request->user(),
        ]);
    }
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

        // $request->user()->fill($request->validated());

        // if ($request->user()->isDirty('email')) {
        //     $request->user()->email_verified_at = null;
        // }

        // $request->user()->save();

            $user = $request->user();

            if ($request->hasFile('user_image')) {
                // حذف الصورة القديمة إذا كانت موجودة
                if ($user->user_image) {
                    Storage::disk('public')->delete($user->user_image);
                }

                // حفظ الصورة الجديدة
                $path = $request->file('user_image')->store('profile_pictures', 'public');

                // تحديث مسار الصورة في قاعدة البيانات
                $user->user_image = $path;
            }

            $user->fill($request->only(['first_name', 'last_name', 'email', 'phone']));
            $user->save();
        return Redirect::route('profile.show')->with('status', 'profile-updated')->with('success', __('web.updated successfully!'));
    }

    /**
     * Delete the user's account.
     */
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
