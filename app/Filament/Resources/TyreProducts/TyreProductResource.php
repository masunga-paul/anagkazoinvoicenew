<?php

namespace App\Filament\Resources\TyreProducts;

use App\Filament\Resources\TyreProducts\Pages\CreateTyreProduct;
use App\Filament\Resources\TyreProducts\Pages\EditTyreProduct;
use App\Filament\Resources\TyreProducts\Pages\ListTyreProducts;
use App\Filament\Resources\TyreProducts\Schemas\TyreProductForm;
use App\Filament\Resources\TyreProducts\Tables\TyreProductsTable;
use App\Models\TyreProduct;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TyreProductResource extends Resource
{
    protected static ?string $model = TyreProduct::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return TyreProductForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TyreProductsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTyreProducts::route('/'),
            'create' => CreateTyreProduct::route('/create'),
            'edit' => EditTyreProduct::route('/{record}/edit'),
        ];
    }
}
