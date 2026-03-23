<?php

namespace App\Listeners;

use App\Events\AuthorStored;
use App\Services\SitemapService;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessAuthorSitemap implements ShouldQueue
{
  public $queue = 'default';

  public function __construct(protected SitemapService $sitemapService) {}

  public function handle(AuthorStored $event)
  {
    foreach ($event->author->locale as $language => $fields) {
      if (empty($fields['name'])) {
        continue;
      }

      $slugs = ['name' => \Str::slug($fields['name'])];


      //\Debugbar::info($slugs);
      $this->sitemapService->updateOrCreateSlug('authors', $event->author->id, $language, 'author', $slugs);
    }
  }
}
