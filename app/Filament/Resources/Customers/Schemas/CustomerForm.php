<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('contact_person'),
                TextInput::make('phone')
                    ->tel(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('tin_number'),
                TextInput::make('vrn_number'),
                Textarea::make('billing_address')
                    ->required()
                    ->columnSpanFull(),
                Select::make('customer_type')
                    ->options([
                        'retail' => 'Retail',
                        'corporate_ngo' => 'Corporate/NGOs',
                        'government' => 'Government',
                    ])
                    ->required()
                    ->default('retail'),
            ]);
    }
}
