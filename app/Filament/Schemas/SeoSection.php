<?php

namespace App\Filament\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;

/** SEO overrides shared by posts and projects (see App\Models\Concerns\HasSeo). */
class SeoSection
{
    public static function make(string $uploadDirectory): Section
    {
        return Section::make('SEO')
            ->description('Optional overrides. Anything left empty falls back to the content above.')
            ->collapsible()
            ->columns(2)
            ->schema([
                TextInput::make('meta_title')
                    ->label('Meta title')
                    ->maxLength(70)
                    ->helperText('Defaults to the title. Aim for 50–60 characters.'),
                TextInput::make('canonical_url')
                    ->label('Canonical URL')
                    ->url()
                    ->maxLength(255)
                    ->helperText('Only set this if the content was first published elsewhere.'),
                Textarea::make('meta_description')
                    ->label('Meta description')
                    ->rows(3)
                    ->maxLength(320)
                    ->helperText('Defaults to the excerpt/summary. Aim for 140–160 characters.')
                    ->columnSpanFull(),
                FileUpload::make('og_image')
                    ->label('Social share image')
                    ->image()
                    ->disk('public')
                    ->visibility('public')
                    ->directory($uploadDirectory.'/og')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(4096)
                    ->helperText('1200 × 630 recommended. Defaults to the cover image.'),
                Toggle::make('noindex')
                    ->label('Hide from search engines')
                    ->helperText('Adds noindex and leaves the page out of the sitemap.'),
            ]);
    }
}
