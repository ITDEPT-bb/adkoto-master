<?php

namespace App\Filament\Resources\AuctionItemResource\Pages;

use App\Filament\Resources\AuctionItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAuctionItems extends ListRecords
{
    protected static string $resource = AuctionItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
