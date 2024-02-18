<x-filament-panels::page>
    {!! QrCode::size(300)->generate($this->getRecord()->name); !!}
</x-filament-panels::page>
