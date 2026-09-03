<?php

namespace App\Filament\Resources\TyreProducts\Pages;

use App\Filament\Resources\TyreProducts\TyreProductResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTyreProducts extends ListRecords
{
    protected static string $resource = TyreProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
