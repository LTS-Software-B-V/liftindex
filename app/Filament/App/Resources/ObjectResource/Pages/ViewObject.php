<?php

namespace App\Filament\App\Resources\ObjectResource\Pages;

use App\Filament\App\Resources\ObjectResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\MaxWidth;
class ViewObject extends ViewRecord
{
    protected static string $resource = ObjectResource::class;
    protected static ?string $title = 'Lifteigenschappen';
  

    protected function getHeaderActions(): array
    {
        return [
      Actions\EditAction::make()->label('Wijzigen')
      ->modalWidth(MaxWidth::SevenExtraLarge),
        ];
    }
    public function getSubheading(): ?string
    {
       
        
        
        
        if ($this->getRecord()->location) {

           
            if( $this->getRecord()->location?->name){
                $location_name =  " | " .  $this->getRecord()->location?->name;
            }
            return   $this->getRecord()->location->address . " " . $this->getRecord()->location->zipcode . " "  . $this->getRecord()->location->place .  $location_name ;




        } else {
            return "";
        }
    
    }


}
