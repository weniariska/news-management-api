<?php

namespace App\Http\Controllers;

use App\Http\Resources\NewsLogsResource;
use App\Models\NewsLogs;
use Illuminate\Http\Request;

class NewsLogsController extends BaseController
{
    public function __construct()
    {
        $this->middleware('restrictRole:admin');
    }

    public function index()
    {
        // get all news logs
        $news = NewsLogs::all();

        // return response
        return $this->sendResponse('All news logs retrieved successfully.', NewsLogsResource::collection($news));
    }

    public function show($news_id)
    {
        // find news logs by news_id
        $news = NewsLogs::find($news_id);

        // check if news logs not found
        if (!$news) {
            return $this->sendError('News not found.', 404);
        }

        // return response
        return $this->sendResponse('News logs retrieved successfully.', new NewsLogsResource($news));
    }
}
