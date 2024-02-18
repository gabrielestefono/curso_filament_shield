<x-filament-panels::page>
    @php
        $announcement = App\Models\Announcement::where('status', 1)->get();
    @endphp
	<div class="w-screen mx-auto py-6 sm:px-6 lg:px-8">
		<div class="mt-6 text-gray-500">
			@foreach ($announcement as $item)
				<div class="mb-4">
					<div class="text-xl font-bold text-white">{{ $item->title }}</div>
					<div class="text-sm text-gray-100">{{ $item->description }}</div>
					<div class="text-xs text-gray-400">{{ $item->created_at->diffForHumans() }}</div>
				</div>
			@endforeach
		</div>
</x-filament-panels::page>
