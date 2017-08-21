<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use App\Post;
use App\User;
use App\Like;
use App\Keyword;
use App\Comment;
use App\Category;
use App\Imgur_Token;
use Hash;

class ProfileController extends Controller
{
    //
    
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['showProfile']]);
    }
    
    public function showProfile($id) {
        $user = User::find($id);
        
        $posts = Post::where('user_id', $id)->orderBy('created_at', 'desc')->paginate(5);
        
        $likes = array();
        if (auth()->user()) {
            $likes = Like::where('user_id', auth()->user()->id)->pluck('post_id')->toArray();
        }
        
        return view('profile.show-profile')
            ->with('posts', $posts)
            ->with('user', $user)
            ->with('likes', $likes);
    }
    
    public function edit($id) {
        
        // Check for correct user
        if (auth()->user()->id != $id) {
            return redirect('/user-profile/'.$id)->with('error', 'Unauthorized Page');
        }
        
        $user = User::find($id);
        
        return view('profile.edit')->with('user', $user);
    }
    
    public function update(Request $request, $id) {
        
        // Check for correct user
        if (auth()->user()->id != $id) {
            return redirect('/user-profile/'.$id)->with('error', 'Unauthorized Page');
        }
        
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'profile_picture' => 'image|max:1999|nullable'
        ]);
        
        $user = User::find($id);
        
        if ($user->email !== $request->input('email')) {
            if (User::where('email', $request->input('email'))->first()) {
                return redirect('/user-profile/'.$id.'/edit')->with('error', 'Email '.$request->input('email').' is already registered.');
            }
            
            $user->email = $request->input('email');
        }
        
        $user->name = $request->input('name');
        
        if ($request->hasFile('profile_picture')) {
            
            // Remove image from imgur
            if ($user->image_hash !== "placeholder") {
                $imgHash = $user->image_hash;
                
                $client = new Client();
                
                $imgur_from_db = Imgur_Token::first();
                
                try {
                    $response = $client->request('DELETE', "https://api.imgur.com/3/image/".$imgHash, [
                        'headers' => [
                            'authorization' => 'Bearer '.$imgur_from_db->token,
                        ]
                    ]);
                    //$body = (string)$response->getBody();
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
            
            $image = base64_encode(file_get_contents($request->file('profile_picture')));
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
            
            $user->profile_picture = $imglink;
            $user->image_hash = $imgHash;
        }
        
        if ($request->input('about')) {
            $user->about = $request->input('about');
        } else {
            if ($user->about !== '') {
                $user->about = '';
            }
        }
        
        $user->save();
        
        return redirect('/home')->with('success', 'Profile updated');
    }
    
    public function changePassword(Request $request, $id) {
        
        // Check for correct user
        if (auth()->user()->id != $id) {
            return redirect('/user-profile/'.$id)->with('error', 'Unauthorized Page');
        }
        
        $this->validate($request, [
            'password' => 'required',
            'new-password' => 'required|min:6',
            'confirm-new-password' => 'required'
        ]);
        
        if ($request->input('new-password') === $request->input('confirm-new-password')) {
            $user = User::find($id);
            
            if(Hash::check($request->input('password'), $user->password)){
                 
                $user->password = Hash::make($request->input('new-password'));
                
                $user->save();
                
                return redirect('/user-profile/'.$id.'/edit')->with('success', 'Password changed');
            
            } else {
                return redirect('/user-profile/'.$id.'/edit')->with('error', 'Incorrect current password');
            }
        
        } else {
            return redirect('/user-profile/'.$id.'/edit')->with('error', "New passwords don't match");
        }
        
    }
     public function destroy(Request $request, $id) {
         
        if (auth()->user()->id != $id) {
            return redirect('/user-profile/'.$id)->with('error', 'Unauthorized Page');
        }
        
        $this->validate($request, [
            'profile_password' => 'required',
        ]);
        
        $user = User::find($id);
        
        if(Hash::check($request->input('profile_password'), $user->password)){
            
            //remove profile image from imgur
            if ($user->image_hash !== "placeholder") {
                $imgHash = $user->image_hash;
                
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
            
            $posts = Post::where('user_id', $id)->get();
            
            //remove all posts
            foreach($posts as $post) {
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
                
                Like::where('post_id', $post->id)->delete();
                
                Comment::where('post_id', $post->id)->delete();
                
                Category::where('title', $post->category)->decrement('count', 1);
                
                Post::find($post->id)->delete();
            }
            
            
            //remove profile
            $user->delete();
            
            return redirect('/posts')->with('success', 'Account deleted successfully');
        } else {
            return redirect('/user-profile/'.$id.'/edit')->with('error', 'Incorrect password');
        }
    }
}
