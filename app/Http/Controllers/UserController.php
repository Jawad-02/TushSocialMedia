<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\View;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;

class UserController extends Controller
{
    // make user lougged out
    public function logout(){
        auth()->logout();
        return redirect('/')->with('success','GoodBye ):');;
    }

    //end make user lougged out

    // take user or gust to the home page

    public function showCorrectHomepage(){
        if(auth()->check()){
            return view('homepage-feed',['posts' => auth()->user()->feedPosts()->latest()->get()]);   
        }else{
            return view('homepage');
        }
    }

    //end take user or gust to the home page
   
    // register a new user 

    public function register(Request $request){
        $incomingField = $request->validate([
            'username' => ['required', 'min:3', 'max:20', Rule::unique('users', 'username')],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:8', 'confirmed']
        ]);
        $user = User::create($incomingField);
        auth()->login($user);
        return redirect('/')->with('success', 'You have successfully registered');
    }

    // end register a new user 

    // login for already existing user 

    public function login(Request $request){
        $incomingField = $request->validate([
            'loginusername' => 'required',
            'loginpassword' => 'required'
        ]);

        if(auth()->attempt(['username' => $incomingField['loginusername'], 'password' => $incomingField['loginpassword']])){
            $request->session()->regenerate();
            return redirect('/')->with('success','You Have succesfully Logged in');
        }else{
            return redirect('/')->with("failure", 'Your Username Or Password Does Not Match');
        }
    }

    // end login for already existing user 

    // make a backage of data to share with other function 
    private function getSharedData($user){
        $currentlyFollowing = 0;

        if(auth()->check()){
            $currentlyFollowing =  Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->id]])->count(); 
        }
        View::share('sharedData', [ 'currentlyFollowing' => $currentlyFollowing, 'avatar' => $user->avatar, 'username' => $user->username,'postCount'=> $user->posts()->count(), 'followersCount' => $user->followers()->count(), 'followingCount' => $user->followingThesesUsers()->count()]);
    }

    // Show User Profile with posts 
    public function profile(User $user){
        $this->getSharedData($user);
        return view('profile-posts', ['posts' => $user->posts()->latest()->get()]);
    }
    // Show User Profile  with Followers
    public function profileFollowers(User $user){
        $this->getSharedData($user);
        return view('profile-followers', ['followers' => $user->followers()->latest()->get()]);
    }
    // Show User Profile with Following
    public function profileFollowing(User $user){
        $this->getSharedData($user);
        return view('profile-following', ['following' => $user->followingThesesUsers()->latest()->get()]);
    }

    // Show Manage Avatar form Where You Can Edit Your Avatar Photo
    public function showAvatarForm(){
        return view('avatar-form');
    }

    // store the upladed avatar in the database
    public function storeAvatar(Request $request){
        $request->validate([
            'avatar' => 'required|image|max:3000'
        ]);
        $user = auth()->user();
        $filename = $user->id . '-' . uniqid() . '.jpg';

        $manager = new ImageManager(new Driver());
        $imgData = $manager->read($request->file('avatar'))->resize(120, 120)->toJpg();

        Storage::put('public/avatars/' . $filename, $imgData);

        $oldAvatar = $user->avatar;

        $user->avatar = $filename;
        $user->save();
        if($oldAvatar != '/fallback-avatar.jpg'){
            Storage::delete(str_replace("/storage/", "public/", $oldAvatar));

        }
        return back()->with('success', 'Congrats on the new avatar (:');
    }
}
