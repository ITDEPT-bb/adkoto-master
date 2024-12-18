<?php

namespace App\Filament\Resources\ProfanityWordResource\Pages;

use App\Filament\Resources\ProfanityWordResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageProfanityWords extends ManageRecords
{
    protected static string $resource = ProfanityWordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
