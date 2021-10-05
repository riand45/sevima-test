<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\User  $user
     * @return \Illuminate\Http\Response
     */
    public function store(User $user)
    {
        auth()->user()->following()->toggle($user->profile->id);

        return redirect()->back();
    }
}
