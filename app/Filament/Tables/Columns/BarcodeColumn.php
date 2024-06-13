<?php
namespace App\Filament\Tables\Columns;

use Filament\Tables\Columns\Column;
use Milon\Barcode\Facades\DNS1DFacade as DNS1D;
use Milon\Barcode\Facades\DNS2DFacade as DNS2D;

class BarcodeColumn extends Column
{
    protected string $view = 'filament.tables.columns.barcode-column';

    public function getContent(): string
    {
        $barcode = $this->record->{$this->getName()};
        $barcodeImage = DNS1D::getBarcodeSVG($barcode, 'C128');
        return $barcodeImage ;
    }
}