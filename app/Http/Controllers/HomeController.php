<?php

namespace App\Http\Controllers;
use App\People;
use App\Category;
use App\Foundation;
use App\foundationPost;
use App\User;
use App\userPost;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    use AuthenticatesUsers;

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
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user_post=userPost::all();
        $foundation_post=foundationPost::orderBy('id','desc')->paginate(6);
        $foundation=Foundation::all();
        $category=Category::all();
            return view('home',compact('user_post','foundation','foundation_post','category'));
        }

    public function getSearchCategory(Request $request)
    {
        $q=$request['q'];
        $user_post=userPost::all();
        $foundation=Foundation::all();
        $category=Category::all();
        if($q=='All'){
              $foundation_post=foundationPost::orderBy('id','desc')->paginate(6);
              return view('home',compact('user_post','foundation','category','foundation_post'));
        }
              $foundation_post=foundationPost::orderBy('id','desc')->where('f_post_category',"LIKE","%$q%")->paginate(6);
              return view('home',compact('category','foundation','foundation_post','user_post'));
    }
    
    public function getSearchFoundation(Request $request)
    {
        $q=$request['q'];
        $user_post=userPost::all();
        $foundation=Foundation::all();
        $category=Category::all();
        if($q=='0'){
             $foundation_post=foundationPost::orderBy('id','desc')->paginate(6);
             return view('home',compact('category','foundation','foundation_post','user_post'));
        }
             $foundation_post=foundationPost::orderBy('id','desc')->where('foundation_id',"LIKE","%$q%")->paginate(6);
             return view('home',compact('category','foundation','foundation_post','user_post'));
    }

    public function profile(Request $request){

        $f_id=foundationPost::all();
        $p_id=userPost::all();
        $data=User::where('id',$request->id)->first();
        $foundation=Foundation::where('user_id',$request->id)->first();
        $people=People::where('user_id',$request->id)->first();
        $userpost=userPost::where('user_id',$request->id)->first();
        $foundationpost=foundationPost::where('user_id',$request->id)->first();
        $fpost=foundationPost::where('user_id',$request->id)->get();
        $ppost=userPost::where('user_id',$request->id)->get();
        return view('frontend.profile',compact('data','foundation','people','userpost','foundationpost','fpost','ppost','f_id','p_id'));

    }

    public function account(Request $request){
        $foundation=Foundation::where('user_id',$request->id)->first();
        $people=People::where('user_id',$request->id)->first();
        return view('frontend.account',compact('foundation','people'));
    }
}
