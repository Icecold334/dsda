<div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div>
            <x-card title="data umum">
                <table class="w-full border-separate border-spacing-y-4">
                    <tr>
                        <td class="font-semibold">
                            <label for="tanggal_permintaan" class="block mb-2 ">Tanggal
                                Permintaan
                                *</label>
                        </td>
                        <td>
                            <input type="date" id="tanggal_permintaan" wire:model.live="tanggal_permintaan"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            @error('tanggal_permintaan')
                                <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <td class="font-semibold"><label for="keterangan">Keterangan</label></td>
                        <td>
                            <div class="flex mb-3">
                                <textarea id="keterangan" wire:model.live="keterangan"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                    placeholder="Masukkan Keterangan" rows="4"></textarea>
                            </div>
                        </td>
                    </tr>
                </table>
            </x-card>
        </div>
        <div>
            <x-card title="unit kerja dan bagian">
                <table class="w-full border-separate border-spacing-y-4">
                    <tr>
                        <td class="w-1/3">
                            <label for="unit_id" class="block mb-2  font-semibold text-gray-900 dark:text-white">
                                Unit Kerja *</label>
                        </td>
                        <td>
                            <select wire:model.live="unit_id"
                                class="bg-gray-50 border border-gray-300  text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="">Pilih Unit Kerja</option>
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('unit_id')
                                <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <td class="w-1/3">
                            <label for="sub_unit_id" class="block mb-2  font-semibold text-gray-900 dark:text-white">
                                Sub Unit *</label>
                        </td>
                        <td>
                            <select wire:model.live="sub_unit_id" @disabled(!$unit_id)
                                class="bg-gray-50 border border-gray-300 {{ !$unit_id ? 'cursor-not-allowed ' : '' }}  text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="">Pilih Sub Unit Kerja</option>
                                @if ($unit_id)
                                    @foreach ($subUnits as $sub)
                                        <option value="{{ $sub->id }}">{{ $sub->nama }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('sub_unit_id')
                                <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                            @enderror
                        </td>
                    </tr>
                </table>
            </x-card>
        </div>
    </div>
    <livewire:list-permintaan-form>
</div>
