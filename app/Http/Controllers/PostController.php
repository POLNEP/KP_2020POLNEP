<?php

namespace App\Http\Controllers;

use App\Post;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::orderBy('id','DESC')->where('post_type','post')->get();
        return view ('admin.post.index',compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::orderBy('name','DESC')->pluck('name','id');
        return view('admin.post.create',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'title' => 'required:unique:posts',
            'thumbnail' => 'required',
            'details' => 'required',
            'category_id' => 'required'
        ],
            [
                'title.required' => 'Masukkan title',
                'title.unique' => 'Judul harus berbeda',
                'thumbnail.required' => 'Harus diisi',
                'details.required' => 'Harus diisi',
                'category_id.required' => 'Pilih kategori'
            ]
        );

        $post = new Post();
        $post->user_id = Auth::id();
        $post->title = $request->title;
        $post->details = $request->details;
        $post->is_published = $request->is_published;
        $post->post_type = 'post';
        
        if ($request->hasFile('thumbnail')) {
            $file=$request->file('thumbnail');
            $extension=$file->getClientOriginalExtension();
            $filename=time().".".$extension;
            $file->move('storage/galleries',$filename);
            $post->thumbnail=$filename;
        }else{
            return $request;
            $post->thumbnail='';
        }
        $post->save();

        $post->categories()->sync($request->category_id, false);
        
        Session::flash('message','Telah berhasil ditambahkan');
        return redirect()->route('posts.index');
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
<<<<<<< HEAD
    public function show($id)
    {
        $post = Post::find($id);
        return view('admin.post.show', compact('post'));   
=======
    public function show(Post $post)
    {
        return view('admin.post.show', ['post'=>Post::find($post)]);   
>>>>>>> d2845400c0bd6ba242301f27900d44c94223636f
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $categories = Category::orderBy('name','DESC')->pluck('name','id');
        return view('admin.post.edit',compact('post','categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $this->validate($request,[
            'title' => 'required:unique:posts,'. $post->id . ',id',
            'thumbnail' => 'required',
            'details' => 'required',
            'category_id' => 'required'
        ],
            [
                'title.required' => 'Masukkan title',
                'title.unique' => 'Judul harus berbeda',
                'thumbnail.required' => 'Harus diisi',
                'details.required' => 'Harus diisi',
                'category_id.required' => 'Pilih kategori'
            ]
        );

        $post->user_id = Auth::id();
        $post->title = $request->title;
        $post->details = $request->details;
        $post->is_published = $request->is_published;
        $post->post_type = 'post';

        if ($request->hasFile('thumbnail')) {
            $file=$request->file('thumbnail');
            $extension=$file->getClientOriginalExtension();
            $filename=time().".".$extension;
            $file->move('storage/galleries',$filename);
            $post->thumbnail=$filename;
        }else{
            return $request;
            $post->thumbnail='';
        }
        $post->save();

        $post->categories()->sync($request->category_id, false);
        
        Session::flash('message','Telah berhasil ditambahkan');
        return redirect()->route('posts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();

        Session::flash('message','Post berhasil dihapus');
        return redirect()->route('posts.index');
    }
}
