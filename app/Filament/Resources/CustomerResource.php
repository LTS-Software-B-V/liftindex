<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use App\Models\ObjectManagementCompany;
use App\Models\objectSupplier;
use App\Services\AddressService;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
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


                        Forms\Components\TextInput::make("name")
                            ->label("Naam / Bedrijfsnaam")
                    ->columnSpan("full"),

                Grid::make(5)->schema([
                            Forms\Components\TextInput::make("zipcode")
                                ->label("Postcode")
                                ->maxLength(255)
                                ->suffixAction(
                                    Action::make("searchAddressByZipcode")
                                        ->icon("heroicon-m-magnifying-glass")
                                        ->action(function (Get $get, Set $set) {


                                            $data = (new AddressService())->GetAddress(
                                                $get("zipcode"),
                                                $get("number")
                                            );
                                            $data = json_decode($data);

                                            if (isset($data->error_id)) {
                                                Notification::make()
                                                    ->warning()
                                                    ->title("Geen resultaten")
                                                    ->body(
                                                        "Helaas er zijn geen gegevens gevonden bij de postcode <b>" .
                                                        $get("zipcode") .
                                                        "</b> Controleer de postcode en probeer opnieuw."
                                                    )
                                                    ->send();
                                            } else {
                                                //dd($data);
                                                $set("place", $data?->municipality);
                                                $set("gps_lat", $data?->lat);
                                                $set("gps_lon", $data?->lng);
                                                $set("address", $data?->street);
                                                $set("municipality", $data?->municipality);
                                                $set("province", $data?->province);
                                                $set("place", $data?->settlement);
                                                $set("building_type", $data?->purposes[0]);
                                                $set("construction_year", $data?->constructionYear);
                                                $set("surface", $data?->surfaceArea);

                                            }
                                        })
                                ),


                            Forms\Components\TextInput::make("address")
                                ->label("Straatnaam")
                                ->columnSpan(2),

//                            Forms\Components\TextInput::make(
//                                "housenumber"
//                            )->label("Huisnummer"),

                            Forms\Components\TextInput::make("place")->label(
                                "Plaats"
                            ),


                            // ...
                        ]),
                    ])
                    ->columns(3)
                    ->columnSpan(4),


            ]);






    }

    public static function table(Table $table): Table
    {
        return $table

            ->columns([



                        Tables\Columns\TextColumn::make('name')
                            ->searchable()
                            ->weight('medium')
                            ->alignLeft()
                            ->label('Bedrijfsnaam'),

                        Tables\Columns\TextColumn::make('emailaddress')
                            ->label('Email address')
                            ->searchable()


                            ->alignLeft(),



                        Tables\Columns\TextColumn::make('address')
                            ->searchable()

                            ->weight('medium')
                            ->alignLeft(),



                        Tables\Columns\TextColumn::make('zipcode')->state(
                            function (Customer $rec) {
                                return $rec->zipcode . " " . $rec->place;
                            }),





                    // Tables\Columns\TextColumn::make('phonenumber')
                    // ->label('Telefoonnummer')
                    // ->searchable()
                    // ->sortable(),


                    TextColumn::make('locations_count')->counts('locations')->label('Locaties')->badge()->alignment(Alignment::Center)->color('success'),
                    TextColumn::make('objects_count')->counts('objects')->label('Objecten')->badge()->alignment(Alignment::Center)->color('success'),


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
            RelationManagers\LocationsRelationManager::class,
            RelationManagers\ObjectsRelationManager::class,
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
