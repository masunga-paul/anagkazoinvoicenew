<?php

namespace App\Filament\Resources\TyreProducts\Pages;

use App\Filament\Resources\TyreProducts\TyreProductResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTyreProduct extends EditRecord
{
    protected static string $resource = TyreProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
