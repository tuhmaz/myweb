<?php

namespace App\Observers;

use App\Models\News;
use App\Services\SitemapService;

class NewsObserver
{
    protected $sitemapService;

    public function __construct(SitemapService $sitemapService)
    {
        $this->sitemapService = $sitemapService;
    }

    /**
     * Handle the News "created" event.
     */
    public function created(News $news)
    {
        $this->sitemapService->generateNewsSitemap($news->getConnectionName());
    }

    /**
     * Handle the News "updated" event.
     */
    public function updated(News $news)
    {
        $this->sitemapService->generateNewsSitemap($news->getConnectionName());
    }

    /**
     * Handle the News "deleted" event.
     */
    public function deleted(News $news)
    {
        $this->sitemapService->generateNewsSitemap($news->getConnectionName());
    }
}
