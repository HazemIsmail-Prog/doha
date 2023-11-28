<?php

namespace App\Filament\Resources\QueryResource\Pages;

use App\Filament\Resources\QueryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListQueries extends ListRecords
{
    protected static string $resource = QueryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(auth()->user()->type == 'admin'),
        ];
    }
}
