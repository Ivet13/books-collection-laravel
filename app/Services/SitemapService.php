<?php

namespace App\Services;

use App\Models\Sitemap;
use Illuminate\Support\Str;

class SitemapService
{
    public function __construct(private Sitemap $sitemap) {}

    public function updateOrCreateSlug($entity, $entityId, $slug)
    {

        $this->sitemap->updateOrCreate([
            'entity' => $entity,
            'entity_id' => $entityId,
        ], [
            'slug' => Str::slug($slug),
        ]);
    }

    public function deleteSlug($entity, $entityId)
    {
        $this->sitemap->where('entity', $entity)->where('entity_id', $entityId)->delete();
    }

    public function getSlug($slug)
    {
        return $this->sitemap->where('slug', $slug)->first();
    }
}
