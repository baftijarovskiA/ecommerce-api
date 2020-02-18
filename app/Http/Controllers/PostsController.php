<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Contracts\Providers\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class PostsController extends Controller
{
    //
    public function index(){
        $posts = Post::all()->where('deleted', '=', '0');
        return response()->json($posts,200);
    }

    public function findPost($slug){
        $post = Post::where('slug','=', $slug)->first();
        return response()->json($post,200);
    }

    public function getPost($id){
        $post = Post::find($id);
        return response()->json($post,200);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required',
            'cover_image' => 'image|nullable|max:1999'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        // File upload
        if($request->hasFile('cover_image')){

            // Get full filename
            $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
            // Get filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get extension
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = $filename."_".time().".".$extension;
            // Upload image
            $path = $request->file('cover_image')->storeAs('public/covers', $fileNameToStore);

            // This is gonna create folder in /storage/app/public/ as /covers and store the uploaded images,
            // We need to create symlink to the public/ folder

        } else {
            $fileNameToStore = 'default.png';
        }

        $post = new Post;
        $post->title = $request->input('title');
        $post->content = $request->input('content');
        $post->slug = $this->slugMaker($request->input('title'));
        $post->user_id = auth()->user()->id;
        $post->cover_image = $fileNameToStore;
        $post->save();

        return response()->json(['success'=>$post],200);
    }

    public function update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required',
            'cover_image' => 'image|nullable|max:1999'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        // File upload
        if($request->hasFile('cover_image')){

            // Get full filename
            $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
            // Get filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get extension
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = $filename."_".time().".".$extension;
            // Upload image
            $path = $request->file('cover_image')->storeAs('public/covers', $fileNameToStore);

            // This is gonna create folder in /storage/app/public/ as /covers and store the uploaded images,
            // We need to create symlink to the public/ folder

        }

        $post = Post::find($id);
        $post->title = $request->input('title');
        $post->content = $request->input('content');
        if($request->hasFile('cover_image')){
            Storage::delete('public/covers/'.$post->cover_image);
            $post->cover_image = $fileNameToStore;
        }
        $post->save();
        return response()->json(['success'=>$post],200);
    }

    public function delete($id){
        $post = Post::find($id)->first();
        $post->deleted = 1;
        $post->save();
        return response()->json(['success'=>$post],200);
    }

    public function slugMaker($title){
        $lowerAndNonCharacters = str_replace("[^a-z0-9 ]", "", strtolower($title));
        $onlyOneSpace = str_replace("[ ]{2,}", "-",$lowerAndNonCharacters);
        return str_replace(' ', '-',$onlyOneSpace);
    }
}
