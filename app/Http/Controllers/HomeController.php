<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

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
        
        //orderBy('created_at', 'desc')->paginate(3)
        
        return view('home')->with('posts', $user->posts->sortByDesc('created_at'));
    }
}
