<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class BookController extends Controller
{
    public function index(){
        return view('books.list');
    }

    public function create(){
        return view('books.create');
    }

    public function store(Request $request){
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

    public function edit(){

    }

    public function update(){

    }

    public function destroy(){

    }
}
