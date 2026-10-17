<?php

namespace App\Filament\Resources\Projects\Schemas;

use App\Support\HtmlEmbed;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\CodeEditor;
use Filament\Forms\Components\CodeEditor\Enums\Language;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Support\Icons\Heroicon;

/** The ordered content blocks that make up a case study page. */
class ProjectContentBuilder
{
    private const IMAGE_TYPES = ['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/avif'];

    private const VIDEO_TYPES = ['video/mp4', 'video/webm'];

    private const MAX_IMAGE_KB = 8192;

    private const MAX_VIDEO_KB = 51200;

    public static function make(): Builder
    {
        return Builder::make('content')
            ->label('Story blocks')
            ->addActionLabel('Add block')
            ->collapsible()
            ->cloneable()
            ->blockNumbers(false)
            ->blocks([
                self::markdownBlock(),
                self::imageBlock(),
                self::videoBlock(),
                self::htmlBlock(),
            ]);
    }

    private static function markdownBlock(): Block
    {
        return Block::make('markdown')
            ->label('Text')
            ->icon(Heroicon::OutlinedBars3BottomLeft)
            ->schema([
                MarkdownEditor::make('body')
                    ->hiddenLabel()
                    ->required()
                    ->fileAttachmentsDisk('public')
                    ->fileAttachmentsDirectory('projects/attachments'),
            ]);
    }

    private static function imageBlock(): Block
    {
        return Block::make('image')
            ->label('Image')
            ->icon(Heroicon::OutlinedPhoto)
            ->columns(2)
            ->schema([
                FileUpload::make('image')
                    ->required()
                    ->image()
                    ->imageEditor()
                    ->disk('public')
                    ->visibility('public')
                    ->directory('projects/blocks')
                    ->acceptedFileTypes(self::IMAGE_TYPES)
                    ->maxSize(self::MAX_IMAGE_KB)
                    ->columnSpanFull(),
                TextInput::make('alt')
                    ->label('Alt text')
                    ->required()
                    ->maxLength(255)
                    ->helperText('Describe the image for screen readers and search engines.'),
                self::widthSelect(),
                TextInput::make('caption')
                    ->maxLength(255)
                    ->columnSpanFull(),
            ]);
    }

    private static function videoBlock(): Block
    {
        return Block::make('video')
            ->label('Video')
            ->icon(Heroicon::OutlinedFilm)
            ->columns(2)
            ->schema([
                FileUpload::make('video')
                    ->required()
                    ->disk('public')
                    ->visibility('public')
                    ->directory('projects/videos')
                    ->acceptedFileTypes(self::VIDEO_TYPES)
                    ->maxSize(self::MAX_VIDEO_KB)
                    ->helperText('MP4 or WebM, up to 50 MB. Compress before uploading - short, silent loops work best.'),
                FileUpload::make('poster')
                    ->label('Poster image')
                    ->image()
                    ->disk('public')
                    ->visibility('public')
                    ->directory('projects/videos/posters')
                    ->acceptedFileTypes(self::IMAGE_TYPES)
                    ->maxSize(self::MAX_IMAGE_KB)
                    ->helperText('Shown before the video loads.'),
                Select::make('playback')
                    ->options([
                        'loop' => 'Autoplay, muted loop (no controls)',
                        'controls' => 'Click to play, with controls',
                    ])
                    ->default('loop')
                    ->required(),
                self::widthSelect(),
                TextInput::make('caption')
                    ->maxLength(255)
                    ->columnSpanFull(),
            ]);
    }

    private static function htmlBlock(): Block
    {
        return Block::make('html')
            ->label('Custom HTML')
            ->icon(Heroicon::OutlinedCodeBracket)
            ->columns(3)
            ->schema([
                Select::make('mode')
                    ->options([
                        HtmlEmbed::MODE_ISOLATED => 'Isolated (sandboxed, recommended)',
                        HtmlEmbed::MODE_INLINE => 'Inline (runs in the page)',
                    ])
                    ->default(HtmlEmbed::MODE_ISOLATED)
                    ->required()
                    ->live()
                    ->helperText(fn (Get $get): string => $get('mode') === HtmlEmbed::MODE_INLINE
                        ? 'Inline code shares the page: it uses the site fonts, and its CSS is confined to this block, but its JavaScript can affect the whole page.'
                        : 'Runs in a sandboxed frame that resizes to fit. Safe from the rest of the site, but uses the system font unless you load your own.'),
                self::widthSelect(),
                TextInput::make('height')
                    ->label('Fixed height (px)')
                    ->numeric()
                    ->minValue(50)
                    ->maxValue(4000)
                    ->placeholder('Fit content')
                    ->visible(fn (Get $get): bool => $get('mode') !== HtmlEmbed::MODE_INLINE),
                FileUpload::make('assets')
                    ->label('Assets')
                    ->multiple()
                    ->reorderable()
                    ->appendFiles()
                    ->disk('public')
                    ->visibility('public')
                    ->directory('projects/embeds')
                    ->acceptedFileTypes([...self::IMAGE_TYPES, ...self::VIDEO_TYPES])
                    ->maxSize(self::MAX_VIDEO_KB)
                    ->storeFileNamesIn('asset_names')
                    ->live()
                    ->columnSpanFull(),
                Text::make(function (Get $get): string {
                    $names = array_values($get('asset_names') ?? []);

                    return $names === []
                        ? 'Upload images or videos above, then reference them in your code as [[asset:file-name.jpg]].'
                        : 'Available assets: '.collect($names)->map(fn (string $name) => "[[asset:{$name}]]")->implode('  ·  ');
                })->columnSpanFull(),
                CodeEditor::make('html')
                    ->label('HTML')
                    ->language(Language::Html)
                    ->required()
                    ->columnSpanFull(),
                CodeEditor::make('css')
                    ->label('CSS')
                    ->language(Language::Css)
                    ->columnSpanFull(),
                CodeEditor::make('js')
                    ->label('JavaScript')
                    ->language(Language::JavaScript)
                    ->columnSpanFull(),
            ]);
    }

    private static function widthSelect(): Select
    {
        return Select::make('width')
            ->options([
                'contained' => 'Contained (text width)',
                'wide' => 'Wide (page width)',
                'full' => 'Full bleed (edge to edge)',
            ])
            ->default('wide')
            ->required();
    }
}
