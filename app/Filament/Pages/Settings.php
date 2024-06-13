<?php
namespace App\Filament\Pages;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Filament\Notifications\Notification;

use App\Models\Settings as SettingModel;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationGroup = 'Settings';

    public $store_name;
    public $location;
    public $contact_info;
    public $currency;
    public $charge_tax;
    public $tax_percentage;
    public $store_logo;
    public $receipt_footer;

    protected static string $view = 'filament.pages.settings';

    protected function getFormSchema(): array
    {
        return [
            Section::make('Enter Your Store Details')
                ->schema([
                    Grid::make(3)->schema([
                        TextInput::make('store_name')->label('Store Name'),
                        TextInput::make('location')->label('Location'),
                        TextInput::make('contact_info')->label('Contact No')->numeric(),
                    ]),
                    Grid::make(3)->schema([
                        Select::make('currency')
                            ->label('Currency Symbol')
                            ->options([
                                'AED' => 'AED - United Arab Emirates Dirham',
                                '€' => 'EUR - Euro',
                                '£' => 'GBP - British Pound',
                                'INR' => 'INR - Indian Rupee',
                                'PKR' => 'PKR - Pakistani Rupee',
                                '$' => 'USD - US Dollar',
                            ])
                            ->placeholder('Select Currency Symbol')
                            ->rules(['required'])
                            ->searchable(),
                        Grid::make(1)->schema([
                            Checkbox::make('charge_tax')
                                ->label('Charge Tax')
                                ->reactive()
                                ->afterStateUpdated(function (callable $set, $state) {
                                    if (!$state) {
                                        $set('tax_percentage', null);
                                    }
                                }),
                            TextInput::make('tax_percentage')
                                ->disableLabel()
                                ->numeric()
                                ->placeholder('Tax Percentage')
                                ->suffix('%')
                                ->hidden(fn($get) => !$get('charge_tax')),
                        ])->columnSpan('row'),
                        FileUpload::make('store_logo')
                            ->label('Store Logo')
                            ->image()
                            ->acceptedFileTypes(['image/png', 'image/jpg', 'image/jpeg', 'image/svg', 'image/webp'])
                            ->directory('store-logo')
                            ->visibility('public')
                            ->previewable(true)
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '1:1',
                                '16:9',
                            ])
                            ->getUploadedFileNameForStorageUsing(fn(TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())->prepend(time()))
                            ->rules(['required', 'image', 'mimes:jpg,jpeg,png,svg,webp']),
                    ]),
                    TextInput::make('receipt_footer')->label('Receipt Footer'),
                ]),
        ];
    }

    public function mount()
    {
        $settings = SettingModel::first();
        $this->form->fill([
            'store_name' => $settings->store_name ?? '',
            'location' => $settings->location ?? '',
            'contact_info' => $settings->contact_info ?? '',
            'currency' => $settings->currency ?? '',
            'charge_tax' => $settings->charge_tax ?? false,
            'tax_percentage' => $settings->tax_percentage ?? '',
            'store_logo' => $settings->store_logo ?? '',
            'receipt_footer' => $settings->receipt_footer ?? '',
        ]);
    }

    public function save()
    {
        $data = $this->form->getState();
        $data['user_id'] = auth()->id();
        // Handle default value for tax_percentage
        if (!$data['charge_tax']) {
            $data['tax_percentage'] = 0; // or set it to null if it's allowed in the database
        }

        SettingModel::updateOrCreate([], $data);
        Notification::make()
            ->title('Settings saved successfully')
            ->success()
            ->send();
    }
}
