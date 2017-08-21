<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Post;
use App\Like;


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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $user_id = auth()->user()->id;
        $user = User::find($user_id);
        
        //$posts = $user->posts->sortByDesc('created_at');
        $posts = Post::where('user_id', $user_id)->orderBy('created_at', 'desc')->paginate(5);
        
        $likes = array();
        if (auth()->user()) {
            $likes = Like::where('user_id', auth()->user()->id)->pluck('post_id')->toArray();
        }
        
        return view('home')
            ->with('posts', $posts)
            ->with('user', $user)
            ->with('likes', $likes);
    }
}
