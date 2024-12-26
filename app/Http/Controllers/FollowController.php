<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    // when someone follow another one  
    public function createFollow(User $user){
        // you cannot follow yourself
        if(auth()->user()->id == $user->id){
            return back()->with('failure','You cannot Follow yourself');
        }
        // you cannot follow someone you're already following

        $existCheck = Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->id]])->count();

        if($existCheck){
            return back()->with('failure','You are already following that user');
        }

        $newFollow = new Follow;
        $newFollow->user_id = auth()->user()->id;
        $newFollow->followeduser = $user->id;
        $newFollow->save();

        return back()->with('success', 'Followed Successfully.');
    }

    // when someone unfollow another one 
    public function removeFollow(User $user){
        Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->id]])->delete();
        return back()->with('success', 'Unfollowed Successfully.');
    }

    // show user followers list
    public function profileFollowers(){

    }

    // show user following list 
    public function profileFollowing(){
        
    }
}
