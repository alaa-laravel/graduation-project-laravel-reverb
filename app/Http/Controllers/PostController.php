<?php

namespace App\Http\Controllers;

use App\Models\post;
use App\Http\Requests\StorepostRequest;
use App\Http\Requests\UpdatepostRequest;
use Illuminate\Http\Client\ResponseSequence;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{

    public function index()
    {
        $posts = post::select('id', 'user_id', 'title', 'description')->with('user:id,name')->get();

        return  response()->json([
            'status' => 200,
            'message' => 'All posts',
            'data' => $posts,

        ]);
    }

    public function store(StorepostRequest $request)
    {
        $post = $request->validated();
        $post['user_id'] = Auth::id();
        $post = Post::create($post);

        return response()->json([
            'status' => 201,
            'message' => 'post created successfully',
            'data' => $post,

        ]);
    }

    public function show($id)
    {

        $one_post = Post::with([
            'comments.user:id,name'
        ])->find($id);

        if (!$one_post) {
            return response()->json([
                'status' => 404,
                'message' => 'no post found'
            ]);
        }

        return response()->json([
            'status' => 200,
            'message' => 'post retreived successfully',
            'data' => $one_post,
        ]);
    }

    public function update(UpdatePostRequest $request, $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'status'  => 404,
                'message' => 'Post not found',
            ]);
        }

        if (Auth::id() !== $post->user_id) {
            return response()->json([
                'status'  => 403,
                'message' => 'You are not allowed to update this post.',
            ]);
        }

        $data = $request->validated();
        $post->update($data);

        return response()->json([
            'status'  => 200,
            'message' => 'Post updated successfully',
            'data'    => $post,
        ]);
    }

    public function destroy($id)
    {
        $post = post::find($id);

        if (!$post) {
            return response()->json([
                'status' => 404,
                'message' => 'post not found',
            ]);
        }

        if (Auth::id() !== $post->user_id) {
            return response()->json([
                'status' => 403,
                'message' => 'You are not allowed to delete this post.',
            ]);
        }
        $post->delete();
        return response()->json([
            'status' => 200,
            'message' => 'post deleted successfully',
        ]);
    }
}