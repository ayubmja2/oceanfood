<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Illuminate\Http\File;

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
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
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

    public function uploadProfileImage(Request $request) {
        $request->validate([
            'profile_image' => 'required|image', // Validate image
        ]);

        $user = Auth::user();

        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');

            // Initialize ImageManager with the driver
            $manager = new ImageManager(new Driver());

            try {
                // Resize the image
                $resizedImage = $manager->read($image->getPathname())->resize(200, 200);

                //Encode the image
                $encodedImage = $resizedImage->toJpg(75);

                //create temporary file path
                $tempPath = sys_get_temp_dir() . '/' . $image->hashName() . '.jpg';

                // Save the encoded image to the temp path
                file_put_contents($tempPath, $encodedImage);

                // Delete old image if it exists
                if ($user->profile_image_url) {
                    $oldPath = str_replace(Storage::disk('spaces')->url("images/user_uploads/user_{$user->id}/profile_image"), "images/user_uploads/user_{$user->id}/profile_image", $user->profile_image_url);

                    if(!empty($oldPath)){
                        Storage::disk('spaces')->delete($oldPath);
                    }

                }

                // Store new image in DigitalOcean Spaces
                $path = Storage::disk('spaces')->putFileAs("images/user_uploads/user_{$user->id}/profile_image", new File($tempPath), $image->hashName() . '.jpg', 'public');

                // Check if the path is empty
                if (!$path) {
                    return redirect()->back()->with('error', 'Failed to upload profile image.');
                }

                $imageUrl = Storage::disk('spaces')->url($path);

                // Check if the imageUrl is empty
                if (!$imageUrl) {
                    return redirect()->back()->with('error', 'Failed to get the URL of the uploaded profile image.');
                }

                // Delete the temporary file
                unlink($tempPath);

                // Update user's profile_image_url
                $user->update([
                    'profile_image_url' => $imageUrl,
                ]);

                return redirect()->back()->with('status', 'Profile image updated successfully!');

            } catch (NotReadableException $e) {
                return redirect()->back()->with('error', 'Failed to read the image file.');
            }
        }

        return redirect()->back()->with('error', 'No image file found.');
    }

    public function addAllergen(Request $request){
        $request->validate(['allergen' => 'required|string']);

        $user = $request->user();
        $allergens = $user->allergens ?? [];
        $allergens[] = $request->input('allergen');
        $user->allergens = array_unique($allergens);
        $user->save();

        return redirect()->back();
    }

    public function removeAllergen(Request $request){
        $request->validate(['allergen' => 'required|string']);

        $user = $request->user();
        $allergens = $user->allergens ?? [];
        $allergens = array_diff($allergens, [$request->input('allergen')]);
        $user->allergens = $allergens;
        $user->save();

        return redirect()->back();
    }
    public function show() {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }
}
