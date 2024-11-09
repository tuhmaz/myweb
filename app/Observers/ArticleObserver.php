<?php

namespace App\Observers;

use App\Models\Article;
use App\Services\SitemapService;

class ArticleObserver
{
    protected $sitemapService;

    public function __construct(SitemapService $sitemapService)
    {
        $this->sitemapService = $sitemapService;
    }

    /**
     * Handle the Article "created" event.
     */
    public function created(Article $article)
    {
        // Update the sitemap when an article is created
        $this->sitemapService->generateArticlesSitemap($article->getConnectionName());
    }

    /**
     * Handle the Article "updated" event.
     */
    public function updated(Article $article)
    {
        // Update the sitemap when an article is updated
        $this->sitemapService->generateArticlesSitemap($article->getConnectionName());
    }

    /**
     * Handle the Article "deleted" event.
     */
    public function deleted(Article $article)
    {
        // Update the sitemap when an article is deleted
        $this->sitemapService->generateArticlesSitemap($article->getConnectionName());
    }
}
