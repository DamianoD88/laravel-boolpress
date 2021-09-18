<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\post;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        //prendere i dati
        $data = $request->all();

        //creare la nuova istanza con dati presi dalla request
        $new_post = new Post();
        $new_post->slug = Str::slug($data['title'], '-');
        $new_post->fill($data);

        // e poi salvare i dati
        $new_post->save();

        return redirect()->route('admin.posts.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
     //collegare id
    public function show($slug)
    {   
        $post = Post::where('slug', $slug)->first();
        return view('admin.posts.show', compact('post'));
    }
    //collegare id

    // public function show(Post $post)
    // {
    //     return view('admin.posts.show', compact('post'));
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        return view('admin.post.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $data = $request->all();
        if($data['title'] != $data->title){

            $slug = Str::slug($data['title'],'-'); //titolo-d'esempio


            $slug_base = $slug['slug'];//titolo-d'esempio
            $slug_presente = Post::where('slug', $slug['slug'])->first();

            $contatore = 1;
            while($slug_presente){

                $slug = $slug_base . '-' . $contatore;

                //controllo se il post esiste ancora
                $slug_presente = Post::where('slug', $slug)->first();

                //incremento contatore
                $contatore++;
            }

            $data['slug'] = $slug;

            


        }
        
        $post->updae($data);

        return redirect()->route('admin.post.index')->with('udated', 'Modifica corretta' . $post->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
