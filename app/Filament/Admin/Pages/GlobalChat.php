<?php

namespace App\Filament\Admin\Pages;

use BackedEnum;
use Filament\Pages\Page;

class GlobalChat extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected string $view = 'filament.admin.pages.global-chat';

    protected static ?string $navigationLabel = 'Chat Global';

    protected static ?int $navigationSort = 1;

    public function getTitle(): string
    {
        return 'Chat Global';
    }
}
