<?php

namespace App\Http\Controllers;

// import model user
use App\Models\News;
use App\Models\NewsLogs;

// import resource
use App\Http\Resources\NewsResource;
use App\Providers\NewsHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;

class NewsController extends BaseController
{
    public function __construct()
    {
        $this->middleware('restrictRole:admin')->except(['index', 'show']);
    }

    public function index()
    {
        // get all news
        $news = News::latest()->paginate(5);

        // response
        return new NewsResource('All news retrieved successfully.', $news);
    }

    public function show($id)
    {
        // find news by id
        $news = News::with(['comment'])->find($id);

        // check if news not found
        if (!$news) {
            return $this->sendError('News not found.', 404);
        }

        // response
        return new NewsResource('News retrieved successfully.', $news);
    }

    public function store(Request $request)
    {
        // define validation rules
        $validator = Validator::make($request->all(), [
            'title'     => 'required|max:255',
            'image'     => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'content'   => 'required|min:10',
        ]);

        // check if validation error
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', 422, $validator->errors());
        }

        try {
            //upload image
            $image = $request->file('image');
            $image->storeAs('public/news', $image->hashName());
            return $this->sendError($image->storeAs('public/news', $image->hashName()), 400);
            // insert
            $news = News::create([
                'title' => $request->title,
                'image' => $image->hashName(),
                'content' => $request->content,
                'admin_id' => $request->user()->id,
            ]);

            // event listener
            event(new NewsHistory($news->id, "Insert"));

            // response
            return new NewsResource('News added successfully.', $news);
        } catch (QueryException $e) {
            return $this->sendError('Error.', 400);
        }
    }

    public function update(Request $request, $id)
    {
        // find news by id
        $news = News::find($id);

        // check if news not found
        if (!$news) {
            return $this->sendError('News not found.', 404);
        }

        // define validation rules
        $validator = Validator::make($request->all(), [
            'title'     => 'max:255',
            'content'     => 'min:10',
        ]);

        // check if validation error
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', 422, $validator->errors());
        }

        try {
            // update
            $data = [];
            $request->has('title') ? $data['title'] = $request->title : '';
            $request->has('content') ? $data['content'] = $request->content : '';
            $news->update($data);

            // event listener
            event(new NewsHistory($news->id, "Update"));

            // repsonse
            return new NewsResource('News updated successfully.', $news);
        } catch (QueryException $e) {
            return $this->sendError('Error.', 400);
        }
    }

    public function destroy($id)
    {
        // find news by id
        $news = News::find($id);

        // check if news not found
        if (!$news) {
            return $this->sendError('News not found.', 404);
        }

        try {
            // soft delete
            $news->delete();

            // event listener
            event(new NewsHistory($news->id, "Delete"));

            // response
            return new NewsResource('News deleted successfully.', $news);
        } catch (QueryException $e) {
            return $this->sendError('Error.', 400);
        }
    }
}
