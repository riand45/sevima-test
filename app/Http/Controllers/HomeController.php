<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Redirect;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Array of users that the auth user follows
        $users_id = auth()->user()->following()->pluck('profiles.user_id');

        // Get Users Id form $following array
        $sugg_users = User::all()->reject(function ($user) {
            $users_id = auth()->user()->following()->pluck('profiles.user_id')->toArray();
            return $user->id == Auth::id() || in_array($user->id, $users_id);
        });

        // Add Auth user id to users id array
        $users_id = $users_id->push(auth()->user()->id);

        // $posts = Post::whereIn('user_id', $users)->with('user')->latest()->paginate(5);
        $posts = Post::whereIn('user_id', $users_id)->with('user')->latest()->paginate(10)->getCollection();
        
        return view('home', compact('posts', 'sugg_users'));
    }
}
