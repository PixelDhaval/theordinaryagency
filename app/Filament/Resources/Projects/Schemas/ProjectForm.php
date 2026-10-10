<?php

namespace App\Filament\Resources\Projects\Schemas;

use App\Filament\Schemas\SeoSection;
use App\Support\ServiceCatalog;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        $disciplines = ServiceCatalog::all()->pluck('name', 'name')->all();

        return $schema
            ->columns(3)
            ->components([
                Section::make('Case study')
                    ->columnSpan(2)
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state): void {
                                if (($get('slug') ?? '') !== Str::slug((string) $old)) {
                                    return;
                                }

                                $set('slug', Str::slug((string) $state));
                            }),
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->alphaDash()
                            ->unique(ignoreRecord: true)
                            ->prefix('/work/'),
                        TextInput::make('client')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('year')
                            ->numeric()
                            ->minValue(1990)
                            ->maxValue((int) now()->addYear()->format('Y')),
                        Select::make('discipline')
                            ->label('Primary discipline')
                            ->options($disciplines)
                            ->required()
                            ->helperText('Used for the Work page filter.'),
                        CheckboxList::make('services')
                            ->label('All services involved')
                            ->options($disciplines)
                            ->columns(2),
                        Textarea::make('summary')
                            ->required()
                            ->rows(3)
                            ->maxLength(300)
                            ->helperText('One-line outcome shown on cards; also the default meta description.')
                            ->columnSpanFull(),
                    ]),

                Section::make('Publishing')
                    ->columnSpan(1)
                    ->schema([
                        DateTimePicker::make('published_at')
                            ->label('Publish date')
                            ->native(false)
                            ->helperText('Empty keeps it as a draft. A future date schedules it.'),
                        Toggle::make('is_featured')
                            ->label('Featured')
                            ->helperText('Featured projects are shown first, including on the home page.'),
                        FileUpload::make('cover_image')
                            ->label('Cover image')
                            ->required()
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->visibility('public')
                            ->directory('projects')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(4096),
                        FileUpload::make('gallery')
                            ->multiple()
                            ->reorderable()
                            ->appendFiles()
                            ->image()
                            ->disk('public')
                            ->visibility('public')
                            ->directory('projects/gallery')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(4096)
                            ->maxFiles(12),
                    ]),

                Section::make('Story')
                    ->description('Build the case study from text, image, video and custom HTML blocks. Drag to reorder.')
                    ->columnSpanFull()
                    ->schema([
                        ProjectContentBuilder::make()->hiddenLabel(),
                    ]),

                SeoSection::make('projects')->columnSpanFull(),
            ]);
    }
}
