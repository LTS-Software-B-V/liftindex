<?php

namespace App\Filament\App\Clusters\Logs\Resources\ExternalApiLogResource\Pages;

use App\Filament\App\Clusters\Logs\Resources\ExternalApiLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExternalApiLogs extends ListRecords
{
    protected static string $resource = ExternalApiLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}