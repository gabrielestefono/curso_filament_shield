<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Announcement extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';

    protected static ?string $navigationGroup = 'Avisos';

    protected static ?string $navigationLabel = 'Avisos';

    protected static ?int $navigationSort = 101;

    protected static ?string $label = 'Avisos';

    protected static ?string $pluralLabel = 'Avisos';

    protected static string $view = 'filament.pages.announcement';

    protected static ?string $title = 'Avisos';

}
