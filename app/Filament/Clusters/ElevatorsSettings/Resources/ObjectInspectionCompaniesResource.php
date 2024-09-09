<?php

namespace App\Filament\Clusters\ElevatorsSettings\Resources;

use App\Filament\Clusters\ElevatorsSettings;
use App\Filament\Clusters\ElevatorsSettings\Resources\ObjectInspectionCompaniesResource\Pages;
use App\Filament\Clusters\ElevatorsSettings\Resources\ObjectInspectionCompaniesResource\RelationManagers;
use App\Models\objectInspectionCompany;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ObjectInspectionCompaniesResource extends Resource
{
    protected static ?string $model = objectInspectionCompany::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = ElevatorsSettings::class;
    protected static ? string $navigationGroup = 'Extern';
    protected static ? string $navigationLabel = 'Keuringinstanties';



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
                        function (objectInspectionCompany $rec) {
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
            'index' => Pages\ListObjectInspectionCompanies::route('/'),
            'create' => Pages\CreateObjectInspectionCompanies::route('/create'),
            'edit' => Pages\EditObjectInspectionCompanies::route('/{record}/edit'),
        ];
    }
}
