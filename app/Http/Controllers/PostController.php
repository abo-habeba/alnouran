<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
  
    public function index()
    {
        
    }
    public function store(Request $request)
    {
        $post = Post::create($request->all());
        return response()->json(['data' => $post], 201);
    }
    public function update(Request $request, Post $post)
    {
        //
    }
    public function destroy(Post $post)
    {
        //
    }
}