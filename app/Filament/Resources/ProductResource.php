<?php

namespace App\Filament\Resources;

use Filament\Support\Facades\FilamentIcon;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Category;
use App\Models\Product;
use Dompdf\Dompdf;
use Filament\Actions\Action;
use Filament\Actions\ExportAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Actions\ButtonAction;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Checkbox;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Filament\Tables\Filters\Filter;
use App\Filament\Tables\Columns\BarcodeColumn;
use App\Filament\Exports\ProductExporter;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationGroup = 'Inventory';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Enter Product Details')->schema([
                Grid::make(3)->schema([
                    BelongsToSelect::make('category_id')
                        ->label('Category')
                        ->relationship('category', 'name')
                        ->placeholder('Select a category')
                        ->rules(['required', 'exists:categories,id']),
                    TextInput::make('name')
                        ->autocomplete(false)
                        ->placeholder('e.g. Brown Oxford...')
                        ->rules(['required', 'string', 'max:255']),
                    TextInput::make('price')->numeric()->placeholder('199')
                        ->rules(['required', 'numeric'])
                ]),
                Grid::make(2)->schema([
                    FileUpload::make('image')
                        ->image()
                        ->acceptedFileTypes(['image/jpg', 'image/jpeg', 'image/png', 'image/svg', 'image/webp'])
                        ->directory('product-images')
                        ->visibility('public')
                        ->previewable(true)
                        ->imageEditor()
                        ->imageEditorAspectRatios([
                            '16:9',
                            '4:3',
                            '1:1',
                        ])
                        ->getUploadedFileNameForStorageUsing(fn(TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())->prepend(time()))
                        ->rules(['required', 'image', 'mimes:jpg,jpeg,png,svg,webp']),
                    Grid::make(1)->schema([
                        TextInput::make('stock')->numeric()->placeholder('34')
                            ->rules(['required', 'numeric']),
                        Checkbox::make('stock_check'),
                    ])->columnSpan('row')

                ])
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->size(80)
                    ->circular()
                    ->defaultImageUrl(url('/images/placeholder.png'))
                    ->extraImgAttributes(['loading' => 'lazy'])
                    ->extraImgAttributes(['loading' => 'lazy']),
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                    
                BarcodeColumn::make('barcode')->label('Barcode'),
                Tables\Columns\TextColumn::make('price')
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->query(
                        fn(array $data, Builder $query): Builder =>
                        $query->when(
                            $data['value'],
                            fn(Builder $query, $value): Builder => $query->where('category_id', $data['value'])
                        )
                    )
                    ->searchable()
                    ->preload(),

            ], layout: FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ExportBulkAction::make()
                        ->exporter(ProductExporter::class),
                    BulkAction::make('download_pdf')
                        ->label('Download PDF')
                        ->action(function (Collection $records) {
                            return ProductResource::downloadPdf($records);
                        })

                ]),
            ]);
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function downloadPdf(Collection $records)
    {

        $products = $records;
        $html = view('pdf.products', compact('products'))->render();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $output = $dompdf->output();

        $filename = 'products.pdf';

        return Response::streamDownload(
            fn() => print ($output),
            $filename,
            ['Content-Type' => 'application/pdf']
        );
    }


}
