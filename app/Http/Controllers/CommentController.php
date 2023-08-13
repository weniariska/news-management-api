<?php

namespace App\Http\Controllers;

// import model user
use App\Models\Comment;

// import resource
use App\Http\Resources\CommentResource;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;

class CommentController extends BaseController
{
    public function __construct()
    {
        $this->middleware('restrictRole:user')->except(['index', 'show']);
    }

    public function index()
    {
        // get all users
        $comments = Comment::all();

        // return response
        return $this->sendResponse('All comments retrieved successfully.', CommentResource::collection($comments));
    }

    public function show($id)
    {
        // find comment by id
        $comment = Comment::find($id);

        // check if comment not found
        if (!$comment) {
            return $this->sendError('Comment not found.', 404);
        }

        // return response
        return $this->sendResponse('Comment retrieved successfully.', new CommentResource($comment));
    }

    public function store(Request $request)
    {
        // find news by id
        $news = News::find($request->news_id);

        // check if news not found
        if (!$news) {
            return $this->sendError('News not found.', 404);
        }

        // define validation rules
        $validator = Validator::make($request->all(), [
            'comment'     => 'required|max:255',
            'news_id' => 'required|integer|exists:news,id'
        ]);

        // check if validation error
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', 422, $validator->errors());
        }

        try {
            // insert
            $comment = Comment::create([
                'comment' => $request->comment,
                'news_id' => $request->news_id,
                'user_id' => auth('sanctum')->user()->id,
            ]);
            return $this->sendResponse('Comment added successfully.', new CommentResource($comment));
        } catch (QueryException $e) {
            return $this->sendError('Error.', 400);
        }
    }

    public function update(Request $request, $id)
    {
        // find comment by id
        $comment = Comment::find($id);

        // check if comment not found
        if (!$comment) {
            return $this->sendError('Comment not found.', 404);
        }

        // define validation rules
        $validator = Validator::make($request->all(), [
            'comment'     => 'max:255',
        ]);

        // check if validation error
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', 422, $validator->errors());
        }

        try {
            // update
            $data = [];
            $request->has('comment') ? $data['comment'] = $request->comment : '';
            $comment->update($data);
            return $this->sendResponse('Comment updated successfully.', new CommentResource($comment));
        } catch (QueryException $e) {
            return $this->sendError('Error.', 400);
        }
    }

    public function destroy($id)
    {
        // find comment by id
        $comment = Comment::find($id);

        // check if comment not found
        if (!$comment) {
            return $this->sendError('Comment not found.', 404);
        }

        try {
            // soft delete
            $comment->delete();
            return $this->sendResponse('Comment deleted successfully.', new CommentResource($comment));
        } catch (QueryException $e) {
            return $this->sendError('Error.', 400);
        }
    }
}
