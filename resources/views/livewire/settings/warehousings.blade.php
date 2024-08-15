<div>
<div class="page-header">
      <div class="row align-items-center">
         <div class="col">
            <h1 class="page-header-title">Magazijnen</h1>
         </div>
         <div class="col-auto">
            <button type="button" data-bs-toggle="modal" data-bs-target="#crudModal"    wire:click = "clear()" class="btn btn-sm btn-primary btn-120" >
            Toevoegen
            </button>
         </div>
      </div>
   </div>
   
   <div class="row ">
      <div class="col-xl-12">

         <div class="card ">
            <div class="card-header card-header-content-md-between">
               <div class="mb-2 mb-md-0">

                  <form>
                     <!-- Search -->
                     <div class="input-group input-group-merge">
                        <input type="text" wire:model.live="filters.keyword" class="js-form-search form-control"
                           placeholder="Zoeken op trefwoord..." data-hs-form-search-options='{
                           "clearIcon": "#clearIcon2",
                           "defaultIcon": "#defaultClearIconToggleEg"
                           }'>
                        <button type="button" class="input-group-append input-group-text">
                           <i id="clearIcon2" class="bi-x-lg" style="display: none;"></i>
                           <i id="defaultClearIconToggleEg" class="bi-search" style="display: none;"></i>
                        </button>
                     </div>
                  </form>
               </div>
               <!-- End Col -->

               <div>

                  @if($this->cntFilters)
                  <div role="alert">
                     <i class="bi-filter me-1"></i> Resultaten gefilterd met @if($this->cntFilters
                     <= 1) 1 filter @else {{$this->cntFilters}} filters @endif 
                     <span wire:click="resetFilters()" style="cursor: pointer" class="text-primary">Wis alle
                        filters</span>
                  </div>
                  @endif

               </div>

            </div>
            <div class="card-bodys  ">
               <div class="row ">
                  <div class="loading" wire:loading>
                  @include('layouts.partials._loading')
                  </div>
                  <div class="col-md-12 " wire:loading.remove>
                     @if($this->cntFilters)
                     <div class="p-3" role="alert">
                        <i class="bi-filter me-1"></i> Resultaten gefilterd met @if($this->cntFilters
                        <= 1) 1 filter @else {{$this->cntFilters}} filters @endif <span wire:click="resetFilters()"
                           style="cursor: pointer" class="text-primary">Wis alle
                        filters</span>
                     </div>
                     @endif
                     <div wire:loading.remove>
                        @if($items->count())
                        <x-table>
                           <x-slot name="head">
                              <x-table.heading sortable wire:click="sortBy('name')">Naam</x-table.heading>
                              <x-table.heading sortable wire:click="sortBy('address')" :direction="$sortDirection">
                                 Adres
                              </x-table.heading>
                              <x-table.heading sortable wire:click="sortBy('zipcode')" :direction="$sortDirection">
                                 Postcode
                              </x-table.heading>
                              <x-table.heading sortable wire:click="sortBy('place')" :direction="$sortDirection">
                                 Plaats
                              </x-table.heading>
                              <x-table.heading></x-table.heading>
                              <x-table.heading></x-table.heading>
                           </x-slot>
                           <x-slot name="body">
                              @foreach ($items as $item)
                              <x-table.row wire:click="edit({{$item->id}})" data-bs-toggle="modal"
                                 data-bs-target="#crudModal" wire:key="row-{{ $item->id }}">
                                 <x-table.cell>
                                    {{$item->name}}
                                 </x-table.cell>
                                 <x-table.cell>
                                    {{$item->address}}<br>
                                 </x-table.cell>
                                 <x-table.cell>
                                    {{$item->zipcode}}
                                 </x-table.cell>
                                 <x-table.cell>
                                    {{$item->place}}
                                 </x-table.cell>
                                 <x-table.cell>
                                    {{$item->address}}
                                 </x-table.cell>
                                 <x-table.cell>
                              <div style="float: right">

                                 <div class="dropdown">
                                    <button type="button" class="btn btn-ghost-secondary btn-icon btn-sm rounded-circle" id="apiKeyDropdown1"
                                       data-bs-toggle="dropdown" aria-expanded="false">
                                       <i class="bi-three-dots-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="apiKeyDropdown1">
                                       <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                          wire:click="edit({{$item->id}})" data-bs-target="#crudModal">Wijzig</a>
                                       <div class="dropdown-divider"></div>
                                       <a class="dropdown-item text-danger" href="#" wire:click="delete({{$item->id}})"
                                          wire:confirm.prompt="Weet je zeker dat je de deze regel verwijderen?\n\nType AKKOORD voor bevestiging |AKKOORD">Verwijderen</a>
                                    </div>
                                 </div>

                              </div>
                           </x-table.cell>
                              </x-table.row>
                              @endforeach
                           </x-slot>
                        </x-table>
                        @else
                        @include('layouts.partials._empty')
                        @endif
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="clearfix pt-3  ">
            <div class="float-end wire:loading.remove"> @if($items->links())
               {{ $items->links() }}
               @endif
            </div>
         </div>
       
      </div>
   </div>
   <!-- CrudModal  -->
   <div wire:ignore.self class="modal fade" id="crudModal" tabindex="-1" role="dialog" aria-labelledby="crudModal"
      aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
         <div class="modal-content">
            <div class="modal-body" wire:loading.class="loading-div">
               <div class="row">
                  <div class="col-md-12">
                     <div>
                        <label class="pb-2">Naam</label>
                        <input wire:model="name" class="form-control    @error('name') is-invalid   @enderror  ">
                        @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                     </div>
                  </div>
               </div>
               <div class="row pt-3">
                  <div class="col-md-6">
                     <div class="pt-3">
                        <label class="pb-2">Postcode</label>
                        <div class="input-group  ">
                           <input class="form-control required  @if ($errors->has('zipcode'))  is-invalid @endif "
                              wire:model.defer="zipcode">
                           <div class="input-group-append">
                              <button class="btn btn-soft-primary" style="height: 43px" wire:click="checkZipcode"
                                 data-toggle="tooltip" data-placement="top" title="Zoek naar postcode"
                                 wire:keydown="checkZipcode" style="height: 40px;">
                              <i class="bi-search"></i>
                              </button>
                           </div>
                           @if ($errors->has('zipcode')) <span class="text-danger">Postcode formaat niet juist</span>
                           @endif
                        </div>
                     </div>
                     <div class="pt-3">
                        <label class="pb-2">Plaats</label>
                        <input wire:model="place" class="form-control">
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="pt-3">
                        <label class="pb-2">Adres</label>
                        <input wire:model="address" class="form-control    @error('address') is-invalid   @enderror  ">
                        @error('address') <span class="invalid-feedback">{{ $message }}</span> @enderror
                     </div>
                  </div>
               </div>
               <hr>
            </div>
            <div class="modal-footer">
           
        
               <button type="button" class="btn btn-sm btn-link btn-120" data-bs-dismiss="modal">Sluiten</button>
               <button class="btn btn-primary btn-sm btn-120    disabled" wire:click="save()" type="button"
                  wire:dirty.remove.class="disabled">
                  <div wire:loading wire:target="save">
                     <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                  </div>
                  Opslaan
            </div>
         </div>
      </div>
   </div>
</div>
<script>
   document.addEventListener('livewire:init', () => {
      Livewire.on('close-crud-modal', (event) => {
         $('#crudModal').modal('hide');
      });
   });
</script>