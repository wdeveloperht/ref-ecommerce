<?php

namespace Botble\Services\Models;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\Media\Facades\RvMedia;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Service extends BaseModel
{
    protected $table = 'services';

    protected $fillable = [
        'key',
        'name',
        'subtitle',
        'description',
        'slug',
        'open_in_new_tab',
        'order',
        'image',
        'tablet_image',
        'mobile_image',
        'status'
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'open_in_new_tab' => 'boolean',
    ];

    protected function randomHash(): Attribute
    {
        return Attribute::get(fn () => hash('sha1', $this->key . $this->getKey()));
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::get(
            function (): ?string {
                if (config('plugins.services.general.use_real_image_url')) {
                    return RvMedia::getImageUrl($this->image);
                }

                return $this->parseImageUrl();
            }
        );
    }

    protected function tabletImageUrl(): Attribute
    {
        return Attribute::get(
            function (): ?string {
                if (config('plugins.services.general.use_real_image_url')) {
                    return RvMedia::getImageUrl($this->tablet_image ?: $this->image);
                }

                return $this->parseImageUrl('tablet');
            }
        );
    }

    protected function mobileImageUrl(): Attribute
    {
        return Attribute::get(
            function (): ?string {
                if (config('plugins.services.general.use_real_image_url')) {
                    return RvMedia::getImageUrl(($this->mobile_image ?: $this->tablet_image) ?: $this->image);
                }

                return $this->parseImageUrl('mobile');
            }
        );
    }

    protected function clickUrl(): Attribute
    {
        return Attribute::get(
            fn ($_, array $attributes = []): string =>
                route('public.services-click.alternative', [
                    'randomHash' => $this->random_hash,
                    'serviceKey' => $attributes['key'],
                ])
        );
    }

    public function parseImageUrl(string $size = 'default'): string
    {
        return route('public.services-click.image', [
            'randomHash' => $this->random_hash,
            'serviceKey' => $this->key,
            'size' => $size,
            'hashName' => md5($this->key),
        ]);
    }
}
