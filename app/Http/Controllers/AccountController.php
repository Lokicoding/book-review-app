<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Drivers\Gd\Driver;

class AccountController extends Controller
{
    public function register(){
        return view('account.register');
    }

    public function processRegister(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users', // Assuming 'users' is your user table
            'password' => 'required|confirmed|min:5',
            'password_confirmation' => 'required'
        ]);
    
        if ($validator->fails()) {
            return redirect()->route('account.register')->withInput()->withErrors($validator);
        }
    
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        return redirect()->route('account.login')->with('success','You Have Register Successfully.');
    }

    public function login(){
        return view('account.login');
    }
    
    public function authenticate(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validator->fails()){
            return redirect()->route('account.login')->withInput()->withErrors($validator);
        }

        if(Auth::attempt(['email' => $request->email,'password' => $request->password])){
            return redirect()->route('account.profile');
        }else{
            return redirect()->route('account.login')->with('error','Either Email or Password Incorrect');
        }
    }

    public function profile(){
        $user = user::find(Auth::user()->id);
        return view('account.profile',['user'=>$user]);
    }

    public function profileUpdate(Request $request){

        $rules = [
            'name' => 'required|min:3',
            'email'=> 'required|email|unique:users,email,'.Auth::user()->id.',id'
        ];

        if(!empty($request->image)){
            $rules['image'] = 'image|mimes:jpeg,png,jpg|max:2048';
        }

        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()){
            return redirect()->route('account.profile')->withInput()->withErrors($validator);
        }

        $user = User::find(Auth::user()->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        if(!empty($request->image)){
            // Delete old image
            File::delete(public_path('uploads/profile/'.$user->image));
            File::delete(public_path('uploads/profile/thumb/'.$user->image));

            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = time().'.'.$ext;
            $image->move(public_path('uploads/profile'),$imageName);
            $user->image = $imageName;
            $user->save();

            $manager = new ImageManager(new Driver());
            $img = $manager->read(public_path('uploads/profile/'.$imageName));
            $img->cover(150, 150);
            $img->save(public_path('uploads/profile/thumb/'.$imageName));
        }    
        return redirect()->route('account.profile')->with('success','Profile Updated Successfully');
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('account.login');
    }

    public function myreviews(Request $request){
        $myreviews = Review::where('user_id',Auth::user()->id)
        ->with('book');
        
        if (!empty($request->keyword)) {
            $keyword = $request->keyword;
    
            $myreviews->where(function($query) use ($keyword) {
                $query->where('review', 'like', '%'.$keyword.'%')
                      ->orWhereHas('book', function($q) use ($keyword) {
                          $q->where('title', 'like', '%'.$keyword.'%');
                      });
            });
        }

        $myreviews = $myreviews->orderBy('created_at','DESC')->paginate(10); 

        return view('account.my-reviews.my-reviews',[
            'myreviews' => $myreviews
        ]);
    }

    public function editReview($id){
        $review = Review::where([
            'id' => $id,
            'user_id' => Auth::user()->id
        ])->first();

        return view('account.my-reviews.edit-myreview',['review'=>$review]);

    }

    public function updateReview(Request $request,$id){
        $review = Review::findOrFail($id);

        $validator = Validator::make($request->all(),[
            'myreview' => 'required|min:10',
        ]);

        if ($validator->fails()) {
            return redirect()->route('account.my-reviews.editReview', $id)
                             ->withInput()
                             ->withErrors($validator);
        }

        if(!empty($review)){
            $review->review = $request->myreview;
            $review->rating = $request->rating;
            $review->save();
            
            session()->flash('success','Review Update Successfully');
            return redirect()->route('account.my-reviews');
        }else{
            session()->flash('error','Review Not Found');
            return redirect()->route('account.my-reviews');
        }

    }

}
