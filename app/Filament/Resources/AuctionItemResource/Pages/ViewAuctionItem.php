<?php

namespace App\Filament\Resources\AuctionItemResource\Pages;

use App\Filament\Resources\AuctionItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAuctionItem extends ViewRecord
{
    protected static string $resource = AuctionItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
