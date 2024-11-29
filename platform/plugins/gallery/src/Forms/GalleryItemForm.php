<?php

namespace Botble\Gallery\Forms;

use Botble\Base\Forms\FieldOptions\DescriptionFieldOption;
use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\FieldOptions\SortOrderFieldOption;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Gallery\Http\Requests\GalleryItemRequest;
use Botble\Gallery\Models\GalleryItem;

class GalleryItemForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(GalleryItem::class)
            ->setValidatorClass(GalleryItemRequest::class)
            ->contentOnly()
            ->add('gallery_id', 'hidden', [
                'value' => $this->getRequest()->input('gallery_id'),
            ])
            ->add('title', TextField::class, [
                'label' => trans('core/base::forms.title'),
                'attr' => [
                    'data-counter' => 120,
                ],
            ])
            ->add('link', TextField::class, [
                'label' => trans('core/base::forms.link'),
                'attr' => [
                    'placeholder' => 'https://',
                    'data-counter' => 120,
                ],
            ])
            ->add('description', TextareaField::class, DescriptionFieldOption::make())
            ->add('order', NumberField::class, SortOrderFieldOption::make())
            ->add('image', MediaImageField::class, MediaImageFieldOption::make()->required());
    }
}
