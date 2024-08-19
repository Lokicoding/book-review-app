@extends('layouts.app')

@section('main')
    <div class="container">
        <div class="row my-5">
            <div class="col-md-3">
                @include('layouts.sidebar')
            </div>
            <div class="col-md-9">
                @include('layouts.message')
                <div class="card border-0 shadow">
                    <div class="card-header  text-white">
                        My Reviews
                    </div>   
                    <div class="card-body pb-0">       
                        <form action="" method="get">
                            <div class="d-flex">
                                <input type="text" placeholder="Search Review" name="keyword" value="{{ Request::get('keyword') }}" class="form-control">
                                <button class="btn btn-primary ms-2">Search</button>
                                <a href="{{ route('account.reviews') }}" class="btn btn-secondary ms-2">Clear</a>
                            </div>
                        </form>         
                        <table class="table  table-striped mt-3">
                            <thead class="table-dark">
                                <tr>
                                    <th>Book</th>
                                    <th>Review</th>
                                    <th>Rating</th>
                                    <th>Status</th>                                  
                                    <th width="100">Action</th>
                                </tr>
                                <tbody>
                                    @if ($myreviews->isNotEmpty())
                                        @foreach ($myreviews as $myreview)
                                            <tr>
                                                <td>{{ $myreview->book->title }}</td>
                                                <td>{{ $myreview->review }}</td>                                        
                                                <td>{{ $myreview->rating }}<i class="fa-regular fa-star"></i></td>
                                                <td>
                                                    @if ($myreview->status == 1)
                                                        <p class="text-success"><strong>Active</strong></p>
                                                    @else
                                                        <p class="text-danger"><strong>Block</strong></p>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{route('account.my-reviews.editReview',$myreview->id)}}" class="btn btn-primary btn-sm"><i class="fa-regular fa-pen-to-square"></i>
                                                    </a>
                                                    <a href="#" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></a>
                                                </td>
                                            </tr> 
                                        @endforeach                                        
                                    @endif                                 
                                </tbody>
                            </thead>
                        </table>   
                        {{ $myreviews->links() }}                 
                    </div>
                </div>                
            </div>
        </div>       
    </div>
@endsection