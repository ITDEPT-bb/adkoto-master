<?php

namespace App\Filament\Resources\KalakalkotoItemResource\Pages;

use App\Filament\Resources\KalakalkotoItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKalakalkotoItems extends ListRecords
{
    protected static string $resource = KalakalkotoItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
