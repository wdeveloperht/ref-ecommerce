<?php

namespace Botble\Services\Forms;

use Botble\Base\Forms\FieldOptions\ContentFieldOption;
use Botble\Base\Forms\Fields\EditorField;
use Botble\Services\Http\Requests\ServiceRequest;
use Botble\Services\Models\Service;
use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\SortOrderFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\OnOffField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Illuminate\Support\Str;

class ServiceForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(Service::class)
            ->setValidatorClass(ServiceRequest::class)
            ->add('key', TextField::class, [
                'label' => trans('plugins/services::services.key'),
                'required' => true,
                'attr' => [
                    'placeholder' => trans('plugins/services::services.key'),
                    'data-counter' => 255,
                ],
                'default_value' => $this->generateServiceKey(),
            ])
            ->add('name',
                TextField::class,
                NameFieldOption::make()
                    ->required()
            )
            ->add('subtitle',
                TextField::class,
                NameFieldOption::make()
                    ->label(trans('plugins/services::services.sub_title'))
            )
            ->add('description',
                EditorField::class,
                ContentFieldOption::make()
                    ->label(trans('plugins/services::services.description'))
                    ->allowedShortcodes()
            )
            ->add('order', NumberField::class, SortOrderFieldOption::make())
            ->add('slug', TextField::class, [
                'label' => trans('plugins/services::services.slug'),
                'attr' => [
                    'placeholder' => trans('plugins/services::services.slug'),
                    'data-counter' => 255,
                ],
            ])
            ->add('open_in_new_tab', OnOffField::class, [
                'label' => trans('plugins/services::services.open_in_new_tab'),
                'default_value' => false,
            ])
            ->add('image', MediaImageField::class, MediaImageFieldOption::make())
            ->add('tablet_image', MediaImageField::class, [
                'label' => trans('plugins/services::services.tablet_image'),
                'help_block' => [
                    'text' => __('For devices with width from 768px to 1200px, if empty, will use the image from the desktop.'),
                ],
            ])
            ->add('mobile_image', MediaImageField::class, [
                'label' => trans('plugins/services::services.mobile_image'),
                'help_block' => [
                    'text' => __('For devices with width less than 768px, if empty, will use the image from the tablet.'),
                ],
            ])
            ->add('status', SelectField::class, StatusFieldOption::make())
            ->setBreakFieldPoint('status');
    }

    protected function generateServiceKey(): string
    {
        do {
            $key = strtoupper(Str::random(12));
        } while (Service::query()->where('key', $key)->exists());

        return $key;
    }
}
