<div>
    <!-- Permissions -->
    <x-card title="Perizinan">
        {{-- <div class="flex justify-end mt-2">
            <button wire:click="savePermissions"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                Simpan
            </button>
        </div> --}}
        <div class="space-y-4">
            @foreach ($permissions as $category => $actions)
            <div>
                <h4 class="text-sm font-semibold text-gray-800 border-b pb-2">{{ $category }}</h4>
                @if (count($actions) > 1)
                <div class="flex justify-end space-x-2">
                    <button type="button" wire:click="selectAllForCategory('{{ $category }}')"
                        class="text-sm text-primary-600 hover:underline focus:outline-none">
                        Select All
                    </button>

                    @if ($this->isCategoryFullySelected($category))
                    <button type="button" wire:click="resetAllForCategory('{{ $category }}')"
                        class="text-sm text-red-600 hover:underline focus:outline-none">
                        Reset All
                    </button>
                    @endif
                </div>
                @endif
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                    @foreach ($actions as $action)
                    @php
                    $permissionKey = "$action";
                    @endphp
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" id="{{ $permissionKey }}"
                            wire:model.live.debounce.500ms="selectedPermissions" value="{{ $permissionKey }}"
                            class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <label for="{{ $permissionKey }}" class="text-sm text-gray-600">{{ Str::ucfirst(str_replace('_',
                            ' ', Str::after($action, '_'))) }}</label>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>



    </x-card>
</div>