<?php
namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        return view('settings.index', compact('user'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = auth()->user();
        $data = $request->validated();

        // 1. Handle Remove Avatar
        if ($request->input('remove_avatar') == '1' || $request->boolean('remove_avatar')) {
            if ($user->avatar) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = null;
        }

        // 2. Handle Cropped Avatar (base64 from Cropper.js)
        if ($request->filled('cropped_avatar')) {
            $base64Data = $request->input('cropped_avatar');
            
            // Extract the base64 part
            if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $type)) {
                $base64Data = substr($base64Data, strpos($base64Data, ',') + 1);
                $type = strtolower($type[1]); // e.g. png, jpeg, etc.
                
                if (in_array($type, ['png', 'jpg', 'jpeg', 'gif', 'webp'])) {
                    $decodedImage = base64_decode($base64Data);
                    if ($decodedImage !== false) {
                        // Delete old avatar if exists
                        if ($user->avatar) {
                            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
                        }
                        
                        $filename = 'uploads/avatars/' . uniqid() . '.' . $type;
                        \Illuminate\Support\Facades\Storage::disk('public')->put($filename, $decodedImage);
                        $data['avatar'] = $filename;
                    }
                }
            }
        } 
        // 3. Fallback to standard avatar file upload if no crop was used
        elseif ($request->hasFile('avatar')) {
            if ($user->avatar) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar')->store('uploads/avatars', 'public');
            $data['avatar'] = $path;
        }

        // Clean up input fields so they don't get updated on the model
        unset($data['cropped_avatar']);
        unset($data['remove_avatar']);

        $user->update($data);

        return redirect()->route('users.show', $user->username)
            ->with('success', 'Profile updated successfully.');
    }
}
