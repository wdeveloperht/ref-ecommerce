<?php

namespace Botble\Services\Tables;

use Botble\Services\Models\Service;
use Botble\Base\Facades\Html;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\BulkChanges\NameBulkChange;
use Botble\Table\BulkChanges\StatusBulkChange;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\FormattedColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\NameColumn;
use Botble\Table\Columns\StatusColumn;
use Botble\Table\HeaderActions\CreateHeaderAction;

class ServiceTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Service::class)
            ->addColumns([
                IdColumn::make(),
                NameColumn::make()->route('services.edit'),
                FormattedColumn::make('key')
                    ->title(trans('plugins/services::services.shortcode'))
                    ->alignStart()
                    ->getValueUsing(function (FormattedColumn $column) {
                        $value = $column->getItem()->key;

                        if (! function_exists('shortcode')) {
                            return $value;
                        }

                        return shortcode()->generateShortcode('services', ['key' => $value]);
                    })
                    ->renderUsing(fn (FormattedColumn $column) => Html::tag('code', $column->getValue()))
                    ->copyable()
                    ->copyableState(fn (FormattedColumn $column) => $column->getValue()),
                StatusColumn::make(),
                CreatedAtColumn::make(),
            ])
            ->addHeaderAction(CreateHeaderAction::make()->route('services.create'))
            ->addActions([
                EditAction::make()->route('services.edit'),
                DeleteAction::make()->route('services.destroy'),
            ])
            ->addBulkAction(DeleteBulkAction::make()->permission('services.destroy'))
            ->addBulkChanges([
                NameBulkChange::make(),
                StatusBulkChange::make()
            ])
            ->queryUsing(function ($query): void {
                $query->select([
                    'id',
                    'name',
                    'key',
                    'status',
                    'created_at',
                ]);
            });
    }
}
