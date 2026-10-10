<?php

namespace App\Filament\Resources\Projects\Tables;

use App\Models\Project;
use App\Support\ServiceCatalog;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                ImageColumn::make('cover_image')
                    ->label('')
                    ->disk('public')
                    ->visibility('public')
                    ->square(),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Project $record): string => $record->client),
                TextColumn::make('client')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('discipline')
                    ->badge()
                    ->color('gray'),
                ToggleColumn::make('is_featured')
                    ->label('Featured'),
                TextColumn::make('status')
                    ->state(fn (Project $record): string => match (true) {
                        $record->published_at === null => 'Draft',
                        $record->published_at->isFuture() => 'Scheduled',
                        default => 'Published',
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Published' => 'success',
                        'Scheduled' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('year')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('discipline')
                    ->options(ServiceCatalog::all()->pluck('name', 'name')->all()),
                TernaryFilter::make('is_featured')
                    ->label('Featured'),
                SelectFilter::make('status')
                    ->options(['published' => 'Published', 'scheduled' => 'Scheduled', 'draft' => 'Draft'])
                    ->query(fn (Builder $query, array $data): Builder => match ($data['value'] ?? null) {
                        'published' => $query->published(),
                        'scheduled' => $query->where('published_at', '>', now()),
                        'draft' => $query->whereNull('published_at'),
                        default => $query,
                    }),
            ])
            ->recordActions([
                Action::make('view')
                    ->label('View on site')
                    ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                    ->url(fn (Project $record): string => route('work.show', $record->slug))
                    ->openUrlInNewTab()
                    ->visible(fn (Project $record): bool => $record->isPublished()),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
