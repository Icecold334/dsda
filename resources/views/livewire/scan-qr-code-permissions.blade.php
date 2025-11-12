<div>
    <x-card title="Scan QR-Code">
        <p class="text-sm text-gray-600 mb-4">
            Apa saja data yang muncul saat QR-Code discan menggunakan smartphone?
        </p>

        <!-- Permissions Form -->
        <form class="space-y-6">
            @foreach ($permissions as $category => $actions)
            <fieldset class="border border-gray-300 p-4 rounded-lg">
                <legend class="text-sm font-semibold text-gray-700">{{ $category }}</legend>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-2">
                    @if ($category === 'Riwayat')
                    <!-- Render Radio Buttons for Riwayat -->
                    @foreach ($actions as $action)
                    <div class="flex items-center">
                        <input type="radio" id="{{ $action }}" wire:model.live.debounce.500ms="selectedRiwayat"
                            value="{{ $action }}" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                        <label for="{{ $action }}" class="ml-2 text-sm text-gray-700">{{ $permissionLabels[$action] ??
                            $action }}</label>
                    </div>
                    @endforeach
                    @else
                    <!-- Render Checkboxes for Other Categories -->
                    @foreach ($actions as $action)
                    <div class="flex items-center">
                        <input type="checkbox" id="{{ $action }}" wire:model.live.debounce.500ms="selectedPermissions"
                            value="{{ $action }}"
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="{{ $action }}" class="ml-2 text-sm text-gray-700">{{ $permissionLabels[$action] ??
                            $action }}</label>
                    </div>
                    @endforeach
                    @endif
                </div>
            </fieldset>
            @endforeach
        </form>

    </x-card>
</div>