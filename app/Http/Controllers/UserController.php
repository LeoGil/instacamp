<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        if (auth()->id() !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        if (auth()->id() !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $data = $request->validate([
            'name' => 'required',
            'bio' => 'required',
            'profile_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if (isset($data['profile_image'])) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $imgPath = $request->file('profile_image')->store('uploads', 'public');
            $data['profile_image'] = $imgPath;
        }

        $user->update($data);

        return redirect()->route('users.show', $user->id);
    }
}
