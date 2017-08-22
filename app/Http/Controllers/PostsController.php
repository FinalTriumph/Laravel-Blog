<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use App\Post;
use App\User;
use App\Category;
use App\Keyword;
use App\Like;
use App\Comment;
use App\Imgur_Token;
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
        $this->middleware('auth', ['except' => ['index', 'show', 'showCategory', 'showKeyword', 'search']]);
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
        
        $crateCategories = array('Business', 'Education', 'Entertainment', 'Fashion', 'Finance', 'Health', 'Lifestyle', 'Nature', 'Relationships', 'Science', 'Sports', 'Technology', 'Travel', 'Web Development', 'Other');
        $categories;
        
        if (count(Category::orderBy('title')->get())) {
            
            $categories = Category::orderBy('title')->get();
        
        } else {
            foreach($crateCategories as $category) {
                $newCategory = new Category;
                
                $newCategory->title = $category;
                
                $newCategory->save();
            }
            $categories = Category::orderBy('title')->get();
        }
        $total = Category::sum('count');
        
        $posts = Post::orderBy('created_at', 'desc')->paginate(5);
        
        $keywords = Keyword::orderBy('count', 'desc')->take(50)->get();
        
        $likes = array();
        if (auth()->user()) {
            $likes = Like::where('user_id', auth()->user()->id)->pluck('post_id')->toArray();
        }
        
        return view('posts.index')
            ->with('posts', $posts)
            ->with('categories', $categories)
            ->with('total', $total)
            ->with('keywords', $keywords)
            ->with('likes', $likes);
    }
    
    public function search($term) {
        
        $posts = Post::where('keywords', 'LIKE', '%'.$term.'%')
                        ->orWhere('title', 'LIKE', '%'.$term.'%')
                        ->orWhere('body', 'LIKE', '%'.$term.'%')
                        ->orderBy('created_at', 'desc')->paginate(5);
        
        $categories = Category::orderBy('title')->get();
        
        $total = Category::sum('count');
        
        $totalPostCount = Post::where('keywords', 'LIKE', '%'.$term.'%')
                        ->orWhere('title', 'LIKE', '%'.$term.'%')
                        ->orWhere('body', 'LIKE', '%'.$term.'%')
                        ->count();
        
        $keywords = Keyword::orderBy('count', 'desc')->take(50)->get();
        
        $searchTerm = $term;
        
        $likes = array();
        if (auth()->user()) {
            $likes = Like::where('user_id', auth()->user()->id)->pluck('post_id')->toArray();
        }
        
        return view('posts.search-results')
            ->with('posts', $posts)
            ->with('categories', $categories)
            ->with('total', $total)
            ->with('keywords', $keywords)
            ->with('searchTerm', $searchTerm)
            ->with('likes', $likes)
            ->with('totalPostCount', $totalPostCount);
    }
    
    //
    public function showCategory($category) {
        
        $posts = Post::where('category', $category)->orderBy('created_at', 'desc')->paginate(5);
        
        $categories = Category::orderBy('title')->get();
        
        $total = Category::sum('count');
        
        $keywords = Keyword::orderBy('count', 'desc')->take(50)->get();
        
        $activeCategory = $category;
        
        $likes = array();
        if (auth()->user()) {
            $likes = Like::where('user_id', auth()->user()->id)->pluck('post_id')->toArray();
        }
        
        return view('posts.category')
            ->with('posts', $posts)
            ->with('categories', $categories)
            ->with('total', $total)
            ->with('keywords', $keywords)
            ->with('activeCategory', $activeCategory)
            ->with('likes', $likes);
    }
    
    public function showKeyword($keyword) {
       
        $posts = Post::where('keywords', 'LIKE', '%'.$keyword.'%')->orderBy('created_at', 'desc')->paginate(5);
        
        $categories = Category::orderBy('title')->get();
        
        $total = Category::sum('count');
        
        $keywords = Keyword::orderBy('count', 'desc')->take(50)->get();
        
        $activeKeyword = $keyword;
        
        $likes = array();
        if (auth()->user()) {
            $likes = Like::where('user_id', auth()->user()->id)->pluck('post_id')->toArray();
        }
        
        return view('posts.keyword')
            ->with('posts', $posts)
            ->with('categories', $categories)
            ->with('total', $total)
            ->with('keywords', $keywords)
            ->with('activeKeyword', $activeKeyword)
            ->with('likes', $likes);
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
            'title' => 'required|max:190',
            'body' => 'required',
            'category' => 'required',
            'post_image' => 'image|max:4999|nullable'
        ]);
        
        $post = new Post;
        
        $post->user_id = auth()->user()->id;
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->category = $request->input('category');
        
        if ($request->input('keywords')) {
            $post->keywords = $request->input('keywords');
            
            $allkeywords = array_map('trim',explode(",", $request->input('keywords')));
            foreach($allkeywords as $keyword) {
                if(Keyword::where('title', $keyword)->exists()) {
                    Keyword::where('title', $keyword)->increment('count', 1);
                } else {
                    $newKeyword = new Keyword;
                    
                    $newKeyword->title = $keyword;
                    $newKeyword->count = 1;
                    
                    $newKeyword->save();
                }
            }
        } else {
            $post->keywords = '';
        }
        
        
        if ($request->hasFile('post_image')) {
            
            // Upload to Imgur and get link
            $client = new Client();
            
            $imgur_from_db = Imgur_Token::first();
            
            $image = base64_encode(file_get_contents($request->file('post_image')));
            $imgurURL = "https://api.imgur.com/3/image";
            try {
                $response = $client->request('POST', $imgurURL, [
                        'headers' => [
                            'authorization' => 'Bearer '.$imgur_from_db->token,
                            'Content-Type' => 'application/x-www-form-urlencoded'
                        ], 
                        'body' => $image
                ]);
            } catch (ClientException $e) {
                $new_token = $client->request('POST', "https://api.imgur.com/oauth2/token", [
                    'json' => [
                        'refresh_token' => $imgur_from_db->refresh_token,
                        'client_id' => $imgur_from_db->client_id,
                        'client_secret' => $imgur_from_db->client_secret,
                        'grant_type' => $imgur_from_db->grant_type
                    ]
                ]);
                $body = $new_token->getBody();
                $dec_body = json_decode($body, true);
                
                $imgur_from_db-> token = $dec_body['access_token'];
                $imgur_from_db-> refresh_token = $dec_body['refresh_token'];
                
                $imgur_from_db->save();
                
                $response = $client->request('POST', $imgurURL, [
                        'headers' => [
                            'authorization' => 'Bearer '.$imgur_from_db->token,
                            'Content-Type' => 'application/x-www-form-urlencoded'
                        ], 
                        'body' => $image
                ]);
            }
            
            $response = json_decode($response->getBody(), true);
            $imglink = $response['data']['link'];
            $imgHash = $response['data']['id'];
            
            $post->cover_image = $imglink;
            $post->image_hash = $imgHash;
        }
        
        $post->save();
        Category::where('title', $request->input('category'))->increment('count', 1);
        
        return redirect('/home')->with('success', 'Post created');
        
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
        //$posts = Post::orderBy('created_at', 'desc')->take(3)->get();
        $comments = Comment::where('post_id', $id)->orderBy('created_at', 'desc')->paginate(5);
        
        $categories = Category::orderBy('title')->get();
        
        $total = Category::sum('count');
        
        $keywords = Keyword::orderBy('count', 'desc')->take(50)->get();
        
        $likes = array();
        if (auth()->user()) {
            $likes = Like::where('user_id', auth()->user()->id)->pluck('post_id')->toArray();
        }
        
        return view('posts.showpost')
                ->with('post', $post)
                ->with('comments', $comments)
                ->with('likes', $likes)
                ->with('categories', $categories)
                ->with('total', $total)
                ->with('keywords', $keywords);
    }
    
    
    //
    public function like($id) {
        $user_id = auth()->user()->id;
        
        $like_entry = Like::where(['post_id' => $id, 'user_id' => $user_id])->get();
        
        if (count($like_entry)) {
            Like::where(['post_id' => $id, 'user_id' => $user_id])->delete();
            
            Post::find($id)->decrement('likes', 1);
            
            $newLikes = Post::find($id)->likes;
            
            echo '{ "status": "unliked", "newLikes": "'.$newLikes.'" }';
        } else {
            $addLike = new Like();
            
            $addLike->post_id = $id;
            $addLike->user_id = $user_id;
            
            $addLike->save();
            
            Post::find($id)->increment('likes', 1);
            
            $newLikes = Post::find($id)->likes;
            
            echo '{ "status": "liked", "newLikes": "'.$newLikes.'" }';
        }
    }
    
    
    //
    public function addComment(Request $request, $id) {
        $this->validate($request, [
            'comment' => 'required',
        ]);
        
        $comment = new Comment;
        
        $comment->post_id = $id;
        $comment->user_id = auth()->user()->id;
        $comment->comment = $request->input('comment');
        
        $comment->save();
        
        Post::find($id)->increment('comments', 1);
        
        return redirect('/posts/'.$id)->with('success', 'Comment added');
    }
    
    public function deleteComment($postid, $commentid) {
        //echo 'this is root to delete comment with id - '.$commentid.', from post with id - '.$postid;
        $comment = Comment::find($commentid);
        $post = Post::find($postid);
        
        if (auth()->user()->id === $comment->user_id || auth()->user()->id === $post->user_id) {
            $comment->delete();
            $post->decrement('comments', 1);
            return redirect('/posts/'.$postid)->with('success', 'Comment deleted');
        } else {
            return redirect('/posts/'.$postid)->with('error', 'Unauthorized request');
        }
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
        $post = Post::find($id);
        
        // Check for correct user
        if (auth()->user()->id !== $post->user_id) {
            return redirect('/posts')->with('error', 'Unauthorized Page');
        }
        
        return view('posts.edit')->with('post', $post);
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
        $this->validate($request, [
            'title' => 'required|max:190',
            'body' => 'required',
            'category' => 'required',
            'post_image' => 'image|max:4999|nullable'
        ]);
        
        $post = Post::find($id);
        
        // Check for correct user
        if (auth()->user()->id !== $post->user_id) {
            return redirect('/posts')->with('error', 'Unauthorized Page');
        }
        
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        
        if ($request->input('category') !== $post->category) {
            Category::where('title', $post->category)->decrement('count', 1);
            Category::where('title', $request->input('category'))->increment('count', 1);
            $post->category = $request->input('category');
        }
        
        $submittedKeywords;
        if ($request->input('keywords')) {
            $submittedKeywords = $request->input('keywords');
        } else {
            $submittedKeywords = "";
        }
        
        if ($submittedKeywords !== $post->keywords) {
            
            $oldKeywords = $post->keywords;
            
            if ($oldKeywords !== "") {
                $allOldKeywords = array_map('trim',explode(",", $oldKeywords));
                foreach($allOldKeywords as $keyword) {
                    $kw = Keyword::where('title', $keyword)->first();
                    if ($kw->count === 1) {
                        Keyword::find($kw->id)->delete();
                    } else {
                        Keyword::where('title', $keyword)->decrement('count', 1);
                    }
                }
            }
            
            if ($submittedKeywords !== '') {
                $allNewKeywords = array_map('trim',explode(",", $submittedKeywords));
                foreach($allNewKeywords as $keyword) {
                    if(Keyword::where('title', $keyword)->exists()) {
                        Keyword::where('title', $keyword)->increment('count', 1);
                    } else {
                        $newKeyword = new Keyword;
                    
                        $newKeyword->title = $keyword;
                        $newKeyword->count = 1;
                    
                        $newKeyword->save();
                    }
                }
            } 
            
            $post->keywords = $submittedKeywords;
        }
        if ($request->hasFile('post_image')) {
            // Remove image from imgur
            if ($post->image_hash !== "placeholder") {
                $imgHash = $post->image_hash;
            
                $client = new Client();
                
                $imgur_from_db = Imgur_Token::first();
                
                try {
                    $response = $client->request('DELETE', "https://api.imgur.com/3/image/".$imgHash, [
                        'headers' => [
                            'authorization' => 'Bearer '.$imgur_from_db->token,
                        ]
                    ]);
                } catch (ClientException $e) {
                    
                    $new_token = $client->request('POST', "https://api.imgur.com/oauth2/token", [
                        'json' => [
                            'refresh_token' => $imgur_from_db->refresh_token,
                            'client_id' => $imgur_from_db->client_id,
                            'client_secret' => $imgur_from_db->client_secret,
                            'grant_type' => $imgur_from_db->grant_type
                        ]
                    ]);
                    $body = $new_token->getBody();
                    $dec_body = json_decode($body, true);
                    
                    $imgur_from_db-> token = $dec_body['access_token'];
                    $imgur_from_db-> refresh_token = $dec_body['refresh_token'];
                    
                    $imgur_from_db->save();
                    
                    $response = $client->request('DELETE', "https://api.imgur.com/3/image/".$imgHash, [
                        'headers' => [
                            'authorization' => 'Bearer '.$imgur_from_db->token,
                        ]
                    ]);
                }
            }
            ///////
            // Upload to Imgur and get link
            $client = new Client();
            
            $imgur_from_db = Imgur_Token::first();
            
            $image = base64_encode(file_get_contents($request->file('post_image')));
            $imgurURL = "https://api.imgur.com/3/image";
            try {
                $response = $client->request('POST', $imgurURL, [
                        'headers' => [
                            'authorization' => 'Bearer '.$imgur_from_db->token,
                            'Content-Type' => 'application/x-www-form-urlencoded'
                        ], 
                        'body' => $image
                ]);
            } catch (ClientException $e) {
                $new_token = $client->request('POST', "https://api.imgur.com/oauth2/token", [
                    'json' => [
                        'refresh_token' => $imgur_from_db->refresh_token,
                        'client_id' => $imgur_from_db->client_id,
                        'client_secret' => $imgur_from_db->client_secret,
                        'grant_type' => $imgur_from_db->grant_type
                    ]
                ]);
                $body = $new_token->getBody();
                $dec_body = json_decode($body, true);
                
                $imgur_from_db-> token = $dec_body['access_token'];
                $imgur_from_db-> refresh_token = $dec_body['refresh_token'];
                
                $imgur_from_db->save();
                
                $response = $client->request('POST', $imgurURL, [
                        'headers' => [
                            'authorization' => 'Bearer '.$imgur_from_db->token,
                            'Content-Type' => 'application/x-www-form-urlencoded'
                        ], 
                        'body' => $image
                ]);
            }
            
            $response = json_decode($response->getBody(), true);
            $imglink = $response['data']['link'];
            $imgHash = $response['data']['id'];
            
            $post->cover_image = $imglink;
            $post->image_hash = $imgHash;
        }
        
        $post->save();
        
        return redirect('/home')->with('success', 'Post updated');
        
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
        
        
        // Remove image from imgur
        if ($post->image_hash !== "placeholder") {
            $imgHash = $post->image_hash;
            
            $client = new Client();
            $imgur_from_db = Imgur_Token::first();
                
            try {
                $response = $client->request('DELETE', "https://api.imgur.com/3/image/".$imgHash, [
                    'headers' => [
                        'authorization' => 'Bearer '.$imgur_from_db->token,
                    ]
                ]);
            } catch (ClientException $e) {
                
                $new_token = $client->request('POST', "https://api.imgur.com/oauth2/token", [
                    'json' => [
                        'refresh_token' => $imgur_from_db->refresh_token,
                        'client_id' => $imgur_from_db->client_id,
                        'client_secret' => $imgur_from_db->client_secret,
                        'grant_type' => $imgur_from_db->grant_type
                    ]
                ]);
                $body = $new_token->getBody();
                $dec_body = json_decode($body, true);
                
                $imgur_from_db-> token = $dec_body['access_token'];
                $imgur_from_db-> refresh_token = $dec_body['refresh_token'];
                
                $imgur_from_db->save();
                
                $response = $client->request('DELETE', "https://api.imgur.com/3/image/".$imgHash, [
                    'headers' => [
                        'authorization' => 'Bearer '.$imgur_from_db->token,
                    ]
                ]);
            }
        }
        ///////
        
        $keywords = $post->keywords;
        if ($keywords !== "") {
            $allkeywords = array_map('trim',explode(",", $keywords));
            foreach($allkeywords as $keyword) {
                $kw = Keyword::where('title', $keyword)->first();
                if ($kw->count === 1) {
                    Keyword::find($kw->id)->delete();
                } else {
                    Keyword::where('title', $keyword)->decrement('count', 1);
                }
            }
        }
        
        Like::where('post_id', $id)->delete();
        
        Comment::where('post_id', $id)->delete();
        
        Category::where('title', $post->category)->decrement('count', 1);
        
        $post->delete();
        
        
        return redirect('/home')->with('success', 'Post deleted');
    }
}
