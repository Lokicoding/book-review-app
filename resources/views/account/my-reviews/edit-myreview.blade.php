@extends('layouts.app')

@section('main')
<div class="container">
    <div class="row my-5">
        <div class="col-md-3">
            @include('layouts.sidebar')               
        </div>
        <div class="col-md-9">
            
            <div class="card border-0 shadow">
                <div class="card-header  text-white">
                    Edit Review
                </div>
                <div class="card-body pb-0">    
                    <div class="card-body">
                        <form action="{{ route('account.my-reviews.updateReview',$review->id) }}" method="post">
                        @csrf    
                            <div class="mb-3">
                                <h4>Review</h4>
                                <div class="mb-3">
                                    <textarea name="myreview" id="review"  class="form-control @error('myreview') is-invalid @enderror" cols="5" rows="5" placeholder="Review">{{ old('myreview', $review->review) }}</textarea>
                                    @error('myreview')
                                        <p class="invalid-feedback">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="author" class="form-label">Rating</label>
                                <select name="rating" id="rating"
                                    class="form-control @error('rating') is-invalid @enderror">
                                    <option value="1" {{ ($review->rating == 1 ? 'selected' : '') }}>1<i class="fa-regular fa-star"></i></option>
                                    <option value="2" {{ ($review->rating == 2 ? 'selected' : '') }}>2<i class="fa-regular fa-star"></i></option>
                                    <option value="3" {{ ($review->rating == 3 ? 'selected' : '') }}>3<i class="fa-regular fa-star"></i></option>
                                    <option value="4" {{ ($review->rating == 4 ? 'selected' : '') }}>4<i class="fa-regular fa-star"></i></option>
                                    <option value="5" {{ ($review->rating == 5 ? 'selected' : '') }}>5<i class="fa-regular fa-star"></i></option>
                                </select>
                                @error('rating')
                                <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                            <button class="btn btn-primary mt-2">Update</button>                     
                        </form>
                    </div>
                </div>
            </div>                
        </div>
    </div>       
</div>
@endsection