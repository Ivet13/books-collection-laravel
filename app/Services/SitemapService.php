<?php

namespace App\Services;

use App\Models\sql\Sitemap;


class SitemapService
{
    public function __construct(private Sitemap $sitemap) {}

    public function updateOrCreateSlug($entity, $entityId, $language, $routeName, $slugs)
    {
        $this->sitemap->updateOrCreate([
            'entity' => $entity,
            'entity_id' => $entityId,
            'language' => $language
        ], [
            'path' => route($language . '.' . $routeName, $slugs),
            'route_name' => $language . '.' . $routeName
        ]);
    }

    public function deleteSlug($entity, $entityId, $slug)
    {
        $this->sitemap->where('entity', $entity)->where('entity_id', $entityId)->where('slug', $slug)->delete();
    }
}
