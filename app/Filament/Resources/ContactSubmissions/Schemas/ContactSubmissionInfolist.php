<?php

namespace App\Filament\Resources\ContactSubmissions\Schemas;

use App\Models\ContactSubmission;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContactSubmissionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make('Message')
                    ->columnSpan(2)
                    ->schema([
                        TextEntry::make('message')
                            ->hiddenLabel()
                            ->prose()
                            ->columnSpanFull(),
                    ]),
                Section::make('From')
                    ->columnSpan(1)
                    ->schema([
                        TextEntry::make('name'),
                        TextEntry::make('email')
                            ->copyable()
                            ->url(fn (ContactSubmission $record): string => 'mailto:'.$record->email),
                        TextEntry::make('company')
                            ->placeholder('-'),
                        TextEntry::make('service')
                            ->label('Interested in')
                            ->badge()
                            ->placeholder('Not sure yet'),
                        TextEntry::make('created_at')
                            ->label('Received')
                            ->dateTime('M j, Y · g:i A'),
                        TextEntry::make('ip_address')
                            ->label('IP address')
                            ->placeholder('-'),
                    ]),
            ]);
    }
}
