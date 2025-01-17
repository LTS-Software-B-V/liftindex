<?php
namespace App\Filament\Resources;

use App\Filament\Resources\CompanyResource\Pages;
use App\Filament\Resources\CompanyResource\RelationManagers;
use App\Models\Company;
use App\Models\companyType;
use App\Services\AddressService;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Actions\ViewAction;
//Form

use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

//Form

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static ?string $navigationIcon = 'heroicon-s-building-library';
    protected static ?string $navigationLabel = "Bedrijven";
    protected static ?string $pluralModelLabel = 'Bedrijven';
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Section::make()->schema([

                Forms\Components\TextInput::make("name")
                    ->label("Naam / Bedrijfsnaam")
                    ->required()

                    ->columnSpan("full"),

                Grid::make(5)->schema([Forms\Components\TextInput::make("zipcode")
                        ->label("Postcode")
                        ->maxLength(255)
                        ->suffixAction(Action::make("searchAddressByZipcode")
                                ->icon("heroicon-m-magnifying-glass")
                                ->action(function (Get $get, Set $set) {

                                    $data = (new AddressService())->GetAddress($get("zipcode"), $get("number"));
                                    $data = json_decode($data);

                                    if (isset($data->error_id)) {
                                        Notification::make()
                                            ->warning()
                                            ->title("Geen resultaten")
                                            ->body("Helaas er zijn geen gegevens gevonden bij de postcode <b>" . $get("zipcode") . "</b> Controleer de postcode en probeer opnieuw.")->send();
                                    } else {
                                        $set("place", $data?->municipality);
                                        $set("address", $data?->street);
                                        $set("place", $data?->settlement);
                                    }
                                })),

                    Forms\Components\TextInput::make("address")
                        ->label("Adres")
                        ->columnSpan(2),
                    Forms\Components\TextInput::make("place")
                        ->label("Plaats")
                        ->columnSpan(2),
                    Forms\Components\Select::make("type_id")
                        ->required()
                        ->label("Categorie")
                        ->options(companyType::where('is_active', 1)
                                ->pluck('name', 'id'))
                        ->columnSpan(2),

                ])])
                ->columns(3)
                ->columnSpan(4),

        ]);

    }

    public static function infolist(Infolist $infolist): Infolist
    {

        return $infolist->schema([

            Components\TextEntry::make('name')
                ->label("Bedrijfsnaam")
                ->placeholder("Niet opgegeven"),

            Components\TextEntry::make('type.name')
                ->label("Categorie")
                ->badge()
                ->placeholder("Niet opgegeven"),

            Components\TextEntry::make("address")
                ->label("Adres")
                ->getStateUsing(function ($record): ?string {

                    return $record?->address . " - " . $record?->zipcode . " " . $record?->place;
                })
                ->placeholder("Niet opgegeven"),

        ]);

    }

    public static function table(Table $table): Table
    {
        return $table->groups([Group::make("type.name")
                ->titlePrefixedWithLabel(false)
                ->label("Categorie"),

        ])
            ->defaultGroup('type.name')->

            columns([

            Tables\Columns\TextColumn::make('name')
                ->searchable()
                ->weight('medium')
                ->alignLeft()
                ->placeholder('-')
                ->label('Bedrijfsnaam'),

            Tables\Columns\TextColumn::make('address')
                ->searchable()
                ->label('Adres')
                ->weight('medium')
                ->placeholder('-')
                ->alignLeft(),

            Tables\Columns\TextColumn::make('zipcode')
                ->placeholder('-')
                ->label('Postcode')->state(function (Company $rec) {
                return $rec->zipcode . " " . $rec->place;
            }),

            Tables\Columns\TextColumn::make('type.name')
                ->label('Categorie')
                ->badge()
                ->placeholder('-')
                ->searchable()
                ->sortable(),

        ])
            ->filters([

                SelectFilter::make('type_id')
                    ->label('Categorie')
                    ->options(companyType::where('is_active', 1)
                            ->pluck('name', 'id')),

                Tables\Filters\TrashedFilter::make(),

            ],
            )
            ->actions([ActionGroup::make([
                EditAction::make()
                    ->modalHeading("Bedrijf bewerken")
                    ->modalIcon('heroicon-o-pencil')
                    ->label('Snel bewerken')
                    ->slideOver(),
                ViewAction::make()
                    ->modalIcon('heroicon-o-pencil')
                    ->label('Bekijk details'),
                DeleteAction::make()
                    ->modalIcon('heroicon-o-trash')
                    ->modalHeading('Bedrijf verwijderen')
                    ->color('danger'),

                RestoreAction::make(),
            ])])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make(

                    [Tables\Actions\DeleteBulkAction::make()

                            ->modalHeading('Verwijderen van alle geselecteerde rijen'),

                        RestoreBulkAction::make(),

                    ])])
            ->emptyState(view('partials.empty-state'));
    }

    public static function getRelations(): array
    {
        return [RelationManagers\ContactsRelationManager::class];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompanies::route('/'),
            'view' => Pages\ViewCompany::route('/{record}'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ["name", "type.name"];
    }

    public static function getGlobalSearchResultDetails($record): array
    {

        return [
            'Type' => $record?->type->name,
        ];

    }

}
