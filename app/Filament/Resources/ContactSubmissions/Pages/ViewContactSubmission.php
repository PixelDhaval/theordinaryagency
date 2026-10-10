<?php

namespace App\Filament\Resources\ContactSubmissions\Pages;

use App\Filament\Resources\ContactSubmissions\ContactSubmissionResource;
use App\Models\ContactSubmission;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

class ViewContactSubmission extends ViewRecord
{
    protected static string $resource = ContactSubmissionResource::class;

    public function mount(int|string $record): void
    {
        parent::mount($record);

        /** @var ContactSubmission $submission */
        $submission = $this->getRecord();

        if ($submission->read_at === null) {
            $submission->markAsRead();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('reply')
                ->label('Reply by email')
                ->icon(Heroicon::OutlinedEnvelope)
                ->url(fn (ContactSubmission $record): string => 'mailto:'.$record->email),
            Action::make('markUnread')
                ->label('Mark as unread')
                ->icon(Heroicon::OutlinedEnvelopeOpen)
                ->color('gray')
                ->action(function (ContactSubmission $record): void {
                    $record->markAsUnread();
                    $this->redirect(ContactSubmissionResource::getUrl('index'));
                }),
            DeleteAction::make(),
        ];
    }
}
