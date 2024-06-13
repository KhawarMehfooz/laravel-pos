<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Milon\Barcode\Facades\DNS1DFacade as DNS1D;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        $barcode = time();
        $data['barcode'] = $barcode;
        $data['stock_check'] = $data['stock_check'] ?? true;


        return $data;
    }

}
