<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
/* To use MySQL queries
use DB;
*/

class PostsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        //$posts = DB::select('SELECT * FROM posts');
        
        //$post = Post::where('title', 'Post Title')->get();
        //$posts = Post::orderBy('created_at', 'desc')->get();
        //$posts = Post::orderBy('created_at', 'desc')->take(3)->get();
        //$posts = Post::all();
        $posts = Post::orderBy('created_at', 'desc')->paginate(3);
        return view('posts.index')->with('posts', $posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
            'category' => 'required',
            'post_image' => 'image|max:4999|nullable'
        ]);
        
        $post = new Post;
        
        $post->user_id = auth()->user()->id;;
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->category = $request->input('category');
        
        if ($request->input('keywords')) {
            $post->keywords = $request->input('keywords');
        } else {
            $post->keywords = '';
        }
        
        $hasFail = "No";
        
        if ($request->hasFile('post_image')) {
            
            // Upload to Imgur and get link
            $image = base64_encode(file_get_contents($request->file('post_image')));
            $options = array('http'=>array(
                'method'=>"POST",
                'header'=>"Authorization: Bearer c3d5d3a28eb9596ef0d46e247ff85e8424108a75\n".
                "Content-Type: application/x-www-form-urlencoded",
                'content'=>$image
            ));
            $context = stream_context_create($options);
            $imgurURL = "https://api.imgur.com/3/image";
            $response = file_get_contents($imgurURL, false, $context);
            $response = json_decode($response);
            $imglink = $response->data->link;
            
            $post->cover_image = $imglink;
            
            $hasFail = "Yes here it is ".$imglink;
        }
        
        $post->save();
        
        return redirect('/home')->with('success', 'Post created, and image? '.$hasFail);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $post = Post::find($id);
        return view('posts.showpost')->with('post', $post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $post = Post::find($id);
        
        // Check for correct user
        if (auth()->user()->id !== $post->user_id) {
            return redirect('/posts')->with('error', 'Unauthorized Page');
        }
        
        // Way to remove image from imgur??
        
        $post->delete();
        
        return redirect('/home')->with('success', 'Post Removed');
    }
}
