<?php

namespace App\Http\Controllers;

use App\Models\comment;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StorecommentRequest;
use App\Http\Requests\UpdatecommentRequest;

class CommentController extends Controller
{
    public function index()
    {
        $comments=comment::with('user:id,name','post:id')->get();

        return response()->json([
            'status'=>200,
            'message'=>'All comments with its owner',
            'data'=>$comments,
        ]);
    }

    public function store(StorecommentRequest $request)
    {
        $comment=$request->validated();
        $comment['user_id']=Auth::id();
        $comment=comment::create($comment);
        $comment->load(['user:id,name']);
        return  response()->json([
           'status'=>201,
           'message'=>'your comment created successfully',
           'data'=>$comment
        ]);
    }


    public function update(UpdatecommentRequest $request, $id)
    {
        $comment=comment::find($id);
        if(!$comment){
            return response()->json([
                'status'=>404,
                'message'=>'comment not found',
            ]);
        }
           if (Auth::id() !== $comment->user_id) {
            return response()->json([
                'status'  => 403,
                'message' => 'You are not allowed to update this comment.',
            ]);
        }
        $data=$request->validated();
        $comment->update($data);

           return response()->json([
            'status'  => 200,
            'message' => 'comment updated successfully',
            'data'    => $comment,
        ]);

    }


    public function destroy($id)
    {
        $comment = comment::find($id);

        if (!$comment) {
            return response()->json([
                'status' => 404,
                'message' => 'comment not found',
            ]);
        }

        if (Auth::id() !== $comment->user_id) {
            return response()->json([
                'status' => 403,
                'message' => 'You are not allowed to delete this comment.',
            ]);
        }
        $comment->delete();
        return response()->json([
            'status' => 200,
            'message' => 'comment deleted successfully',
        ]);
    }
}
