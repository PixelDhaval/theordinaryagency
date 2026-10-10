<?php

namespace App\Filament\Resources\ContactSubmissions\Tables;

use App\Models\ContactSubmission;
use App\Support\ServiceCatalog;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class ContactSubmissionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->recordClasses(fn (ContactSubmission $record): ?string => $record->read_at === null ? 'font-semibold' : null)
            ->columns([
                IconColumn::make('read_at')
                    ->label('')
                    ->state(fn (ContactSubmission $record): bool => $record->read_at === null)
                    ->boolean()
                    ->trueIcon(Heroicon::OutlinedEnvelope)
                    ->falseIcon(Heroicon::OutlinedEnvelopeOpen)
                    ->trueColor('warning')
                    ->falseColor('gray')
                    ->tooltip(fn (ContactSubmission $record): string => $record->read_at === null ? 'Unread' : 'Read'),
                TextColumn::make('name')
                    ->searchable()
                    ->description(fn (ContactSubmission $record): ?string => $record->company),
                TextColumn::make('email')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('service')
                    ->badge()
                    ->color('gray')
                    ->placeholder('Not sure yet'),
                TextColumn::make('message')
                    ->limit(60)
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Received')
                    ->since()
                    ->dateTimeTooltip()
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('read_at')
                    ->label('Status')
                    ->nullable()
                    ->placeholder('All')
                    ->trueLabel('Read')
                    ->falseLabel('Unread'),
                SelectFilter::make('service')
                    ->options(ServiceCatalog::all()->pluck('name', 'name')->all()),
            ])
            ->recordActions([
                ViewAction::make(),
                Action::make('toggleRead')
                    ->label(fn (ContactSubmission $record): string => $record->read_at === null ? 'Mark read' : 'Mark unread')
                    ->icon(fn (ContactSubmission $record) => $record->read_at === null ? Heroicon::OutlinedEnvelopeOpen : Heroicon::OutlinedEnvelope)
                    ->color('gray')
                    ->action(fn (ContactSubmission $record) => $record->read_at === null ? $record->markAsRead() : $record->markAsUnread()),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('markRead')
                        ->label('Mark as read')
                        ->icon(Heroicon::OutlinedEnvelopeOpen)
                        ->action(fn (Collection $records) => $records->each->markAsRead())
                        ->deselectRecordsAfterCompletion(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
