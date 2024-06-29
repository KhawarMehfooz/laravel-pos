<?php

namespace App\Filament\Pages;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Pages\Page;

class PointOfSale extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?int $navigationSort = 4;

    protected static string $view = 'filament.pages.point-of-sale';
     
    public function getTitle(): string|Htmlable
    {
        return '';
    }
    
}
