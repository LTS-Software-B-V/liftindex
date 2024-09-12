<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ElevatorsResource\Pages;
use App\Filament\Resources\ElevatorsResource\RelationManagers;
use App\Models\Elevator;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

//Form
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;

//Table
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\FileUpload;

class ElevatorsResource extends Resource
{
    protected static ?string $model = Elevator::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ? string $navigationGroup = 'Hoofdmenu';
    protected static ? string $navigationLabel = 'Objecten';




    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('unit_nr')
                    ->label('Nummer'),

                Tables\Columns\TextColumn::make('nobo_nr ')
                    ->label('Nobonummer'),

                Tables\Columns\TextColumn::make('location.name ')
                    ->label('Locatie'),

                Tables\Columns\TextColumn::make('customer.name ')
                    ->label('Relatie'),

                Tables\Columns\TextColumn::make('customer.name ')
                    ->label('Relatie'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListElevators::route('/'),
            'create' => Pages\CreateElevators::route('/create'),
            'edit' => Pages\EditElevators::route('/{record}/edit'),
        ];
    }
}
