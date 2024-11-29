<?php

namespace Botble\Gallery\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Models\BaseModel;

class GalleryItem extends BaseModel
{
    protected $table = 'gallery_items';

    protected $fillable = [
        'gallery_id',
        'title',
        'description',
        'link',
        'image',
        'order'
    ];

    protected $casts = [
        'title' => SafeContent::class,
        'description' => SafeContent::class,
        'link' => SafeContent::class,
    ];

    protected static function booted(): void
    {
        static::deleted(function (GalleryItem $item): void {
            $item->metadata()->delete();
        });
    }
}
