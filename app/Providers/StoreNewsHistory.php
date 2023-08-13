<?php

namespace App\Providers;

use App\Models\NewsLogs;

use App\Providers\NewsHistory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreNewsHistory
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NewsHistory $event): void
    {
        $newsHistory = NewsLogs::create([
            'news_id' => $event->news_id,
            'description' => $event->description,
        ]);
    }
}
