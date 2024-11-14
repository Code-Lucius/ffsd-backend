<?php

namespace App\Http\Controllers;

use App\Events\PostCreated;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;



class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('user:id,first_name,last_name')
        ->orderBy('created_at', 'desc')
        ->get(); 
        return response()->json($posts);
    }


    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:500',
            'created_by' => 'required|numeric|gte:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $post = Post::create([
            'title' => $request->title,
            'content' => $request->content,
            'created_by'=>$request->created_by,
        ]);

        
        if(!$post){
            return response()->json(['message'=> 'Post could not be created'], 422);
        }

        broadcast(new PostCreated($post));

        return response()->json($post, 200);
    }

    public function update(Request $request, $id)
    {
        // Validate the incoming data
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // Find the post by ID
        $post = Post::findOrFail($id);

        // Update the post
        $post->title = $request->title;
        $post->content = $request->content;
        $post->save();

        return response()->json(['message' => 'Post updated successfully', 'post' => $post]);
    }

    // Delete post method
    public function destroy($id)
    {
        // Find the post by ID
        $post = Post::findOrFail($id);

        // Delete the post
        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);
    }

}
