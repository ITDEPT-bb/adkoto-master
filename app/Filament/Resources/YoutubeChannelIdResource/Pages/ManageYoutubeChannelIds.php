<?php

namespace App\Filament\Resources\YoutubeChannelIdResource\Pages;

use App\Filament\Resources\YoutubeChannelIdResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageYoutubeChannelIds extends ManageRecords
{
    protected static string $resource = YoutubeChannelIdResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
