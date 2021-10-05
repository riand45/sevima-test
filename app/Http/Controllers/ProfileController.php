<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        $follows = (auth()->user()) ? auth()->user()->following->contains($user->profile) : false;

        $postCount =  $user->posts->count();

        $followersCount =  $user->profile->followers->count();

        $followingCount = $user->following->count();

        return view('profiles.index', compact('user', 'follows', 'postCount', 'followersCount', 'followingCount'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user->profile);

        return view('profiles.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user->profile);

        $dataProfile = $request->validate([
            'website' => ['sometimes', 'url', 'nullable'],
            'bio' => ['sometimes', 'string', 'nullable'],
            'image' => ['sometimes', 'image', 'max:3000']
        ]);

        $dataUser = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
        ]);

        if (request('image')) {
            $imagePath = request('image')->store('/profile', 'public');
            $image = Image::make(public_path("storage/{$imagePath}"))->fit(300, 300);
            $image->save();
            $imageArray = ['image' => $imagePath];
        }

        auth()->user()->profile->update(array_merge(
            $dataProfile,
            $imageArray ?? []
        ));

        auth()->user()->update($dataUser);

        return redirect('/profile/' . auth()->user()->username);
    }
}
