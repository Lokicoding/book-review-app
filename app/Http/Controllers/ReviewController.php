<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request){
        $reviews = Review::with('book','user');

        if (!empty($request->keyword)) {
            $keyword = $request->keyword;
    
            $reviews->where(function($query) use ($keyword) {
                $query->where('review', 'like', '%'.$keyword.'%')
                      ->orWhereHas('book', function($q) use ($keyword) {
                          $q->where('title', 'like', '%'.$keyword.'%');
                      });
            });
        }

        $reviews = $reviews->orderBy('created_at','DESC')->paginate(10);

        return view('account.review.list',[
            'reviews' => $reviews
        ]);
    }

    public function edit($id){
        $review = Review::findOrFail($id);
        return view('account.review.edit',[
            'review' => $review
        ]);
    }

    public function update(Request $request,$id){
        $review = Review::findOrFail($id);
        $review->status = $request->status;
        $review->save();
        session()->flash('success','Review Status Update Successfully');
        return redirect()->route('account.reviews');
    }

    public function destroy(Request $request){
        $review = Review::find($request->id);
        
        if($review == Null){
            session()->flash('error','Review Not Found');
            return response()->json([
                'status' => false,
                'message' => 'Review Not found'
            ]);
        }else{
            $review->delete();
            session()->flash('success','Review Delete Successfully');
            return response()->json([
                'status' => true,
                'message' => 'Review Delete Successfully'
            ]);
        }    
    }
}