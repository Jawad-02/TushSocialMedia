<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PostController extends Controller
{

    // Show Create Post Form
    public function showCreateForm(){
        return view('create-post');
    }

    // Store The Post in the Database
    public function storeNewPost(Request $request){
        $incomingField = $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);
        // clear any html tags that user might enter 
        $incomingField['title'] = strip_tags($incomingField['title']);
        $incomingField['body'] = strip_tags($incomingField['body']);
        // add the corrent user id to the array 
        $incomingField['user_id'] = auth()->id();
        // send the array to store in the database 
        $newPost = Post::create($incomingField);
        
        return redirect("/post/$newPost->id")->with('success', 'New Post successfully created');
    }

    // in order to show a spicific post by post id number  
    public function viweSinglePost(Post $post){
        
            // allow markdown syntex to re format the body text 
            $post['body'] = Str::markdown($post->body);

        return view('single-post', ['post' => $post]);
    }
    // delete a spicific post 
    public function delete(Post $post){
        $post->delete();
        return redirect('/profile/' . auth()->user()->username)->with('success', ' Post successfully deleted');
    }
    // to bring edit form 

    public function showEditForm(Post $post){

        return view('edit-post', ['post' => $post]);

    }

    public function actuallyUpdate(Post $post, Request $request){
        $incomingField = $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);

        // clear any html tags that user might enter 
        $incomingField['title'] = strip_tags($incomingField['title']);
        $incomingField['body'] = strip_tags($incomingField['body']);

        $post->update($incomingField);
        
        return back()->with('success','Post Updated Successfully');
    }

}
