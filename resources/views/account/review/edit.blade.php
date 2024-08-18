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
                        <form action="{{ route('account.reviews.update',$review->id) }}" method="post">
                        @csrf    
                            <div class="mb-3">
                                <h4>Review</h4>
                                <p>{{$review->review}}</p>
                            </div>
                            <div class="mb-3">
                                <label for="author" class="form-label">Status</label>
                                <select name="status" id="status"
                                    class="form-control @error('status') is-invalid @enderror">
                                    <option value="0" {{ ($review->status == 0 ? 'selected' : '') }}>Block</option>
                                    <option value="1" {{ ($review->status == 1 ? 'selected' : '') }}>Active</option>
                                </select>
                                @error('status')
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