<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class BookController extends Controller
{
    public function index(Request $request){
        $books = Book::orderby('created_at','DESC');

        if(!empty($request->keyword)){
            $books->where('title','like','%'.$request->keyword.'%')->orWhere('author','like','%'.$request->keyword.'%');
        }

        $books = $books->paginate(5);
        return view('books.list',['books' => $books ]);
    }

    public function create(){
        return view('books.create');
    }

    public function store(Request $request){
        $rules = [
            'title' => 'required|min:5|unique:books',
            'author' => 'required|min:5',
            'status' => 'required'
        ];

        if(!empty($request->image)){
            $rules['image'] = 'image|mimes:jpeg,png|max:1048';
        }

        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()){
            return redirect()->route('books.create')->withInput()->withErrors($validator);
        }

        $book = new Book();
        $book->title = $request->title;
        $book->author = $request->author;
        $book->description = $request->description;
        $book->status = $request->status;
        $book->save();

        if(!empty($request->image)){
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = time().'.'.$ext;
            $image->move(public_path('uploads/books/'),$imageName);
            $book->image = $imageName;
            $book->save();

            $manager = new ImageManager(new Driver());
            $img = $manager->read(public_path('uploads/books/'.$imageName));
            $img->resize(990);
            $img->save(public_path('uploads/books/thumb/'.$imageName));
        }  

        return redirect()->route('books.index')->with('success','Book Added Successfully');
    }

    public function edit($id){
        $book = Book::findOrFail($id);
        return view('books.edit',['book' => $book]);
    }

    public function update($id,Request $request){

        $book = Book::findOrFail($id);

        $rules = [
            'title' => 'required|min:5',
            'author' => 'required|min:5',
            'status' => 'required'
        ];

        if(!empty($request->image)){
            $rules['image'] = 'image|mimes:jpeg,png|max:1048';
        }

        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()){
            return redirect()->route('books.edit',$book->id)->withInput()->withErrors($validator);
        }

        
        $book->title = $request->title;
        $book->author = $request->author;
        $book->description = $request->description;
        $book->status = $request->status;
        $book->save();

        if(!empty($request->image)){

            File::delete(public_path('uploads/books/'.$book->image));
            File::delete(public_path('uploads/books/thumb/'.$book->image));

            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = time().'.'.$ext;
            $image->move(public_path('uploads/books/'),$imageName);
            $book->image = $imageName;
            $book->save();

            $manager = new ImageManager(new Driver());
            $img = $manager->read(public_path('uploads/books/'.$imageName));
            $img->resize(990);
            $img->save(public_path('uploads/books/thumb/'.$imageName));
        }  

        return redirect()->route('books.index')->with('success','Book Update Successfully');
    }

    public function destroy(){

    }
}
