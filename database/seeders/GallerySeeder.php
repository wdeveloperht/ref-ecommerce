<?php

namespace Database\Seeders;

use Botble\Base\Facades\MetaBox;
use Botble\Base\Supports\BaseSeeder;
use Botble\Language\Models\LanguageMeta;
use Botble\Gallery\Models\Gallery;
use Botble\Gallery\Models\GalleryItem;
use Illuminate\Support\Arr;

class GallerySeeder extends BaseSeeder
{
    public function run(): void
    {
        $this->uploadFiles('sliders');

        Gallery::query()->truncate();
        GalleryItem::query()->truncate();

        $sliders = [
            [
                'name' => 'Home slider 1',
                'key' => 'home-slider-1',
                'image' => '',
                'total' => 2,
                'style' => 'style-4',
            ],
            [
                'name' => 'Home slider 2',
                'key' => 'home-slider-2',
                'image' => '',
                'total' => 2,
                'style' => 'style-2',
            ],
            [
                'name' => 'Home slider 3',
                'key' => 'home-slider-3',
                'image' => '',
                'total' => 2,
                'style' => 'style-3',
            ],
            [
                'name' => 'Home slider 4',
                'key' => 'home-slider-4',
                'image' => '',
                'total' => 2,
                'style' => 'style-1',
            ],
            [
                'name' => 'Home slider 5',
                'key' => 'home-slider-5',
                'image' => '',
                'total' => 2,
                'style' => 'style-5',
            ],
            [
                'name' => 'Home slider 6',
                'key' => 'home-slider-6',
                'image' => '',
                'total' => 1,
                'style' => 'style-6',
            ],
            [
                'name' => 'Blog slider 1',
                'key' => 'blog-slider-1',
                'image' => '',
                'total' => 6,
                'style' => 'style-1',
                'gallery' => true,
            ],
        ];

        $sliderItems = [
            [
                'title' => 'Don’t miss amazing<br /> grocery deals',
                'link' => '/products',
                'description' => 'Sign up for the daily newsletter',
            ],
            [
                'title' => 'Fresh Vegetables<br />
										Big discount',
                'link' => '/products',
                'description' => 'Save up to 50% off on your first order',
            ],
        ];

        foreach ($sliders as $index => $value) {
            $slider = Gallery::query()->create(Arr::only($value, ['name', 'key', 'image']));

            LanguageMeta::saveMetaData($slider);

            if (Arr::get($value, 'style')) {
                MetaBox::saveMetaBoxData($slider, 'gallery_style', $value['style']);
            }

            if (Arr::get($value, 'gallery')) {
                for ($i = 1; $i <= $value['total']; $i++) {
                    $item = [
                        'image' => 'gallery/thumbnail-' . $i . '.jpg',
                        'order' => $i,
                        'gallery_id' => $slider->id,
                    ];

                    GalleryItem::query()->create($item);
                }
            } else {
                foreach (collect($sliderItems)->take($value['total']) as $key => $item) {
                    $item['image'] = 'gallery/' . ($index + 1) . '-' . ($key + 1) . '.png';
                    $item['order'] = $key + 1;
                    $item['gallery_id'] = $slider->id;

                    GalleryItem::query()->create($item);
                }
            }
        }
    }
}
