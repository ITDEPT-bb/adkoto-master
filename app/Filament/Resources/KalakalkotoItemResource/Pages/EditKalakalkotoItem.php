<?php

namespace App\Filament\Resources\KalakalkotoItemResource\Pages;

use App\Filament\Resources\KalakalkotoItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKalakalkotoItem extends EditRecord
{
    protected static string $resource = KalakalkotoItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
