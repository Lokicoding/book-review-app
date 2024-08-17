<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function index(Request $request){
        $book = Book::orderby('created_at','DESC');
        
        if(!empty($request->keyword)){
            $book->where('title','like','%'.$request->keyword.'%')->orWhere('author','like','%'.$request->keyword.'%');
        }
        
        $book = $book->where('status',1)->paginate(8);
        
        return view('home',['books' => $book]);
    }

    public function detail($id){
        $book = Book::with(['reviews.user','reviews' => function($query){
            $query->where('status',1); 
        }])->findOrFail($id);
        
        if($book->status == 0){
            abort(404);
        }
        
        $relatedbooks = Book::where('status',1)->where('id','!=',$id)->take(3)->inRandomOrder()->get();
        
        return view('book-details',[
            'book' => $book,
            'relatedbooks' => $relatedbooks
        ]);
    }

    public function saveReview(Request $request){
        $validator = Validator::make($request->all(),[
            'review' => 'required|min:10',
            'rating' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        } 

        $reviewCount = Review::where('user_id',Auth::user()->id)->where('book_id',$request->book_id)->count();

        if($reviewCount > 0){
            session()->flash('error','You Already Submitted a Review.');
            return response()->json([
                'status' => true,
            ]);
        }
        
        $review = new Review();
        $review->review = $request->review;
        $review->rating = $request->rating;
        $review->user_id = Auth::user()->id;
        $review->book_id = $request->book_id;
        $review->save();
        
        session()->flash('success','Review Submitted Successfully.');
        return response()->json([
            'status' => true,
        ]);
    }
}