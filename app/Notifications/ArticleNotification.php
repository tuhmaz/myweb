<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Notifications\NewArticleNotification;
use App\Models\Article;

class ArticleNotification extends Notification
{
    use Queueable;

    public $article;

    public function __construct($article)
    {
        $this->article = $article;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
{
    return [
        'title' => 'New Article: ' . $this->article->title,
        'article_id' => $this->article->id,
        'url' => route('articles.show', $this->article->id),
    ];
}
}
