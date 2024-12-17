<?php

namespace Botble\Gallery\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gallery extends BaseModel
{
    protected $table = 'gallery';

    protected $fillable = [
        'status',
        'name',
        'key',
        'description',
        'image',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'name' => SafeContent::class,
        'key' => SafeContent::class,
        'description' => SafeContent::class,
    ];

    protected static function booted(): void
    {
        static::deleted(function (Gallery $slider): void {
            $slider->sliderItems()->each(fn (GalleryItem $item) => $item->delete());
        });
    }

    public function sliderItems(): HasMany
    {
        return $this->hasMany(GalleryItem::class)->orderBy('gallery_items.order');
    }
}
