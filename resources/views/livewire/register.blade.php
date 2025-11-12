<div>
    <form wire:submit.prevent="register">
        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-inputwire:model.live.debounce.500ms="name" id="name" class="block mt-1 w-full" type="text"
                name="name" required autofocus />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Unit -->
        <div class="mt-4">
            <x-input-label for="parent_id" :value="__('Unit Kerja Utama')" />
            <selectwire:model.live.debounce.500ms="parent_id" id="parent_id"
                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm ">
                <option value="">{{ __('Pilih Unit') }}</option>
                @foreach ($unitkerjas as $unitkerja)
                <option value="{{ $unitkerja->id }}">
                    {{ $unitkerja->nama }}
                </option>
                @endforeach
                </select>
                <x-input-error :messages="$errors->get('parent_id')" class="mt-2" />
        </div>
        @if ($parent_id)
        <div class="mt-4">
            <x-input-label for="sub_unit" :value="__('Sub Unit Kerja')" />
            <selectwire:model.live.debounce.500ms="sub_unit" id="sub_unit"
                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="">{{ __('Pilih Unit') }}</option>
                @foreach ($subUnits as $sub)
                <option value="{{ $sub->id }}">{{ $sub->nama }}
                </option>
                @endforeach
                </select>
                <x-input-error :messages="$errors->get('sub_unit')" class="mt-2" />
        </div>
        @endif

        <!-- Lokasi -->
        <div class="mt-4">
            <x-input-label for="lokasi_id" :value="__('Lokasi')" />
            <selectwire:model.live.debounce.500ms="lokasi_id" id="lokasi_id"
                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="">{{ __('Pilih Lokasi') }}</option>
                @foreach ($lokasis as $lokasi)
                <option value="{{ $lokasi['id'] }}">{{ $lokasi['nama'] }}</option>
                @endforeach
                </select>
                <x-input-error :messages="$errors->get('lokasi_id')" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-inputwire:model.live.debounce.500ms="email" id="email" class="block mt-1 w-full" type="email"
                required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-inputwire:model.live.debounce.500ms="password" id="password" class="block mt-1 w-full"
                type="password" required />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-inputwire:model.live.debounce.500ms="password_confirmation" id="password_confirmation"
                class="block mt-1 w-full" type="password" required />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</div>