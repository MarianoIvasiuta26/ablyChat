<?php

namespace App\Filament\Admin\Pages;

use BackedEnum;
use Filament\Pages\Page;

class PrivateMessages extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected string $view = 'filament.admin.pages.private-messages';

    protected static ?string $navigationLabel = 'Mensajes Privados';

    protected static ?int $navigationSort = 2;

    public function getTitle(): string
    {
        return 'Mensajes Privados';
    }
}
