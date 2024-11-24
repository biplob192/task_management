<?php

use Illuminate\Support\Facades\Storage;

// Upload file to the 'public' disk
if (!function_exists('uploadFile')) {
    function uploadFile($path, $file)
    {
        return $file->store($path, 'public');
    }
}


// Assign user role
if (!function_exists('assignUserRole')) {
    function assignUserRole($user, $role)
    {
        // Remove the previous role if any
        if ($user->getRoleNames()->first()) {
            $user->removeRole($user->getRoleNames()->first());
        }

        // Assign a new role
        $user->assignRole($role);
        return $user->roles->pluck('name');
    }
}


// Update user profile image
if (!function_exists('updateProfileImage')) {
    function updateProfileImage($user, $image)
    {
        // Remove the previous profile_image
        if ($user->profile_image) {
            Storage::disk('public')->delete($user->profile_image);
        }

        // Upload new profile_image
        return uploadFile('users/profile_image', $image);
    }
}


// Update user
if (!function_exists('updateUser')) {
    function updateUser($user, $validatedData, $request)
    {
        $user->roles->pluck('name');

        // Update user role
        if (!empty($validatedData['role'])) {
            assignUserRole($user, $validatedData['role']);
        }

        // Update profile image
        if ($request->hasFile('profile_image')) {
            // $validatedData['profile_image'] = updateProfileImage($user, $request->file('profile_image'));
            $user->profile_image = updateProfileImage($user, $request->file('profile_image'));
        }

        // Save the user model
        $user->save();

        // Return updated user model
        return $user;
    }
}
