<?php

namespace App\Filament\Resources\Posts\Tables;

use App\Models\Post;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with('category'))
            ->defaultSort('published_at', 'desc')
            ->columns([
                ImageColumn::make('cover_image')
                    ->label('')
                    ->disk('public')
                    ->visibility('public')
                    ->square(),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(60)
                    ->description(fn (Post $record): ?string => $record->excerpt ? str($record->excerpt)->limit(80) : null),
                TextColumn::make('category.name')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('status')
                    ->state(fn (Post $record): string => self::status($record))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Published' => 'success',
                        'Scheduled' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('published_at')
                    ->label('Publish date')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->placeholder('—'),
                TextColumn::make('reading_time')
                    ->suffix(' min')
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('noindex')
                    ->label('Noindex')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->relationship('category', 'name')
                    ->preload(),
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
                    ->url(fn (Post $record): string => route('journal.show', $record->slug))
                    ->openUrlInNewTab()
                    ->visible(fn (Post $record): bool => $record->isPublished()),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    private static function status(Post $record): string
    {
        return match (true) {
            $record->published_at === null => 'Draft',
            $record->published_at->isFuture() => 'Scheduled',
            default => 'Published',
        };
    }
}
