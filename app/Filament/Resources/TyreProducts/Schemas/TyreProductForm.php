<?php

namespace App\Filament\Resources\TyreProducts\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TyreProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('sku')
                    ->label('SKU')
                    ->required(),
                TextInput::make('brand')
                    ->required(),
                TextInput::make('pattern')
                    ->required(),
                TextInput::make('size')
                    ->required(),
                TextInput::make('category')
                    ->required(),
                TextInput::make('unit_price_tzs')
                    ->required()
                    ->numeric(),
                TextInput::make('wholesale_price_tzs')
                    ->numeric(),
                TextInput::make('stock_quantity')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('reorder_threshold')
                    ->required()
                    ->numeric()
                    ->default(10),
                FileUpload::make('image_url')
                    ->image(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
