<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;

class GenerateQrCode extends Page
{
    use InteractsWithRecord;

    protected static string $resource = StudentResource::class;

    protected static string $view = 'filament.resources.student-resource.pages.generate-qr-code';

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
        static::authorizeResourceAccess();
    }

    public function export()
    {
        if (!Auth::user()->can('export_students')) {
            abort(403, 'Você não tem permissão para realizar esta ação.');
        }
        // Lógica de exportação
    }
}
