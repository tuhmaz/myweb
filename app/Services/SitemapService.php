<?php

namespace App\Services;

use App\Models\Article;
use App\Models\News;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Illuminate\Support\Facades\Storage;

class SitemapService
{
     private function getFirstImageFromContent($content, $defaultImageUrl)
    {
        preg_match('/<img[^>]+src="([^">]+)"/', $content, $matches);
        return $matches[1] ?? $defaultImageUrl;
    }

    public function generateArticlesSitemap(string $database)
    {
        $sitemap = Sitemap::create();
        $defaultImageUrl = asset('assets/img/front-pages/icons/articles_default_image.jpg');

        $articles = Article::on($database)->get();

        foreach ($articles as $article) {
            $url = Url::create(route('frontend.articles.show', ['database' => $database, 'article' => $article->id]))
                ->setLastModificationDate($article->updated_at)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(0.8);

             $imageUrl = $article->image_url ?? $this->getFirstImageFromContent($article->content, $defaultImageUrl);
            $altText = $article->alt ?? $article->title;

             if ($imageUrl) {
                $url->addImage($imageUrl, $altText);
            }

            $sitemap->add($url);
        }

         $fileName = "sitemaps/sitemap_articles_{$database}.xml";
        Storage::disk('public')->put($fileName, $sitemap->render());
    }

    public function generateNewsSitemap(string $database)
    {
        $sitemap = Sitemap::create();
        $defaultImageUrl = asset('assets/img/front-pages/icons/news_default_image.jpg');

        // Fetch news items based on the selected database
        $newsItems = News::on($database)->get();

        foreach ($newsItems as $news) {
            // Create the URL for the news item
            $url = Url::create(route('frontend.news.show', ['database' => $database, 'id' => $news->id]))
                ->setLastModificationDate($news->updated_at)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.7);

            // Check if the image exists, use Storage::url to generate the correct path
            if ($news->image) {
                // Assuming the images are stored in the 'public/images' folder
                $imageUrl = Storage::url('images/' . $news->image);
            } else {
                // Use the default image if no image is uploaded
                $imageUrl = $defaultImageUrl;
            }

            // Set alt text based on the article title
            $altText = $news->alt ?? $news->title;

            // Add image to the sitemap entry if available
            if ($imageUrl) {
                $url->addImage($imageUrl, $altText);
            }

            // Add the news URL to the sitemap
            $sitemap->add($url);
        }

        // Save the sitemap file
        $fileName = "sitemaps/sitemap_news_{$database}.xml";
        Storage::disk('public')->put($fileName, $sitemap->render());
    }


}
