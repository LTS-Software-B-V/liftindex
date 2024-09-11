<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use App\Models\objectSupplier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([


                Forms\Components\Section::make()
                    ->schema([


                        Forms\Components\TextInput::make('name')
                            ->label('Naam')
                            ->maxLength(255)
                            ->required(),

                        Forms\Components\TextInput::make('zipcode')

                            ->label('Postcode')
                            ->maxLength(255),


                        Forms\Components\TextInput::make('place')
                            ->label('Plaats')
                            ->maxLength(255),


                        Forms\Components\TextInput::make('address')
                            ->label('Adres')
                            ->maxLength(255),

                        // ->content(fn (Customer $record): ?string => $record->updated_at?->diffForHumans()),
                    ])
                    ->columnSpan(['lg' => 2]),
                //  ->hidden(fn (?Customer $record) => $record === null),


                Forms\Components\Section::make()
                    ->schema([

                        Forms\Components\TextInput::make('emailaddress')
                            ->email()
                            ->label('E-mailadres')   ->columnSpan('full')

                            ->maxLength(255),

                        Forms\Components\TextInput::make('phonenumber')
                            ->label('Telefoonnummer')   ->columnSpan('full')

                            ->maxLength(255),



                    ])
                    ->columns(2)
                    ->columnSpan(['lg' => 1]),

            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table

            ->columns([
                Tables\Columns\Layout\Split::make([
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('name')
                            ->searchable()

                            ->weight('medium')
                            ->alignLeft()        ->label('Bedrijfsnaam'),

                        Tables\Columns\TextColumn::make('emailaddress')
                            ->label('Email address')
                            ->searchable()


                            ->alignLeft(),
                    ])->space(),

                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('address')
                            ->searchable()

                            ->weight('medium')
                            ->alignLeft(),



                        Tables\Columns\TextColumn::make('zipcode')->state(
                            function (Customer $rec) {
                                return $rec->zipcode . " " . $rec->place;
                            }),





                    ])->space(2),


                    // Tables\Columns\TextColumn::make('phonenumber')
                    // ->label('Telefoonnummer')
                    // ->searchable()
                    // ->sortable(),




                ])->from('md'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->modalHeading('Wijzigen'),
                Tables\Actions\EditAction::make()->modalHeading('Wijzigen'),
                Tables\Actions\DeleteAction::make()->modalHeading('Verwijderen van deze rij'),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->modalHeading('Verwijderen van alle geselecteerde rijen'),

                ]),
            ]) ->emptyState(view('partials.empty-state')) ;
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
