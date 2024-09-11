<?php

namespace App\Filament\Resources\AuctionItemResource\Pages;

use App\Filament\Resources\AuctionItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAuctionItem extends EditRecord
{
    protected static string $resource = AuctionItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
