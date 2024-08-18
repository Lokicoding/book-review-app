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
                    Reviews
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
                                <th>User</th>  
                                <th>Date</th>  
                                <th>Status</th>                                  
                                <th width="100">Action</th>
                            </tr>
                            <tbody>
                                @if($reviews->isNotEmpty())
                                    @foreach ($reviews as $review)
                                        <tr>
                                            <td>{{$review->book->title}}</td>
                                            <td>{{$review->review}}</td>                                        
                                            <td>{{$review->rating}}<i class="fa-regular fa-star"></i></td>
                                            <td>{{$review->user->name}}</td>
                                            <td>{{\Carbon\Carbon::parse($review->created_at)->format('d M Y')}}</td>
                                            <td>
                                                @if ($review->status == 1)
                                                    <p class="text-success"><strong>Active</strong></p>
                                                @else
                                                    <p class="text-danger"><strong>Block</strong></p>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{route('account.reviews.edit',$review->id)}}" class="btn btn-primary btn-sm"><i class="fa-regular fa-pen-to-square"></i>
                                                </a>
                                                <a href="#" onclick="reviewdelete({{ $review->id }});" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif                                 
                            </tbody>
                        </thead>
                    </table>   
                    {{$reviews->links()}}                  
                </div>
                
            </div>                
        </div>
    </div>       
</div>
@endsection
@section('script')
<script>
    function reviewdelete(id){
        if(confirm('Are You Sure You Want To Delete This Review?')){
            $.ajax({
                url:'{{route('reviews.delete')}}',
                type:'delete',
                data:{id:id},
                headers:{
                    'X-CSRF-TOKEN' : '{{ csrf_token() }}'
                },
                success:function(response){
                    console.log(response);
                    window.location.href = '{{ route('account.reviews') }}';
                }

            })
        }
    }
</script>
@endsection