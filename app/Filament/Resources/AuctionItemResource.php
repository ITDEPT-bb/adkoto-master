<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuctionItemResource\Pages;
use App\Filament\Resources\AuctionItemResource\RelationManagers;
use App\Models\AuctionItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AuctionItemResource extends Resource
{
    protected static ?string $model = AuctionItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('category_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('location')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('starting_price')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('bid_increment')
                    ->required()
                    ->numeric()
                    ->default(1.00),
                Forms\Components\Select::make('bidding_type')
                    ->options([
                        'live' => 'Live',
                        'normal' => 'Normal',
                    ])
                    ->default('normal')
                    ->required(),
                Forms\Components\DateTimePicker::make('auction_ends_at')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('starting_price')
                    ->money('php'),
                Tables\Columns\TextColumn::make('bidding_type'),
                Tables\Columns\TextColumn::make('auction_ends_at')->sortable(),
                Tables\Columns\TextColumn::make('user.name'),
                // Tables\Columns\TextColumn::make('user_id')
                //     ->numeric()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('category_id')
                //     ->numeric()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('name')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('location')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('starting_price')
                //     ->numeric()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('bid_increment')
                //     ->numeric()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('bidding_type'),
                // Tables\Columns\TextColumn::make('auction_ends_at')
                //     ->dateTime()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('created_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // Tables\Columns\TextColumn::make('updated_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // Tables\Columns\TextColumn::make('deleted_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListAuctionItems::route('/'),
            'create' => Pages\CreateAuctionItem::route('/create'),
            'view' => Pages\ViewAuctionItem::route('/{record}'),
            'edit' => Pages\EditAuctionItem::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
