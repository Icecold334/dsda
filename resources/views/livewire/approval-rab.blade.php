<div>
    <div class="flex w-full justify-evenly border-t-4 py-6">
        <!-- Penulis -->
        <div>
            <div class="block font-semibold text-center mb-2 text-gray-900">Penulis</div>
            <div class="text-sm border-b-2">
                <div class="flex justify-between px-3">
                    <span class="mr-9 {{ $penulis->id == auth()->id() ? 'font-bold' : '' }}">
                        {{ false ? 'Anda' : $penulis->name }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Iterasi dinamis semua role -->
        @foreach ($roleLists as $roleKey => $users)
        <div>
            <div class="block font-semibold text-center mb-2 text-gray-900">
                {{-- {{ $roleKey }} --}}
                {{ ucwords(str_replace('-', ' ', $roleKey)) }}
            </div>
            <table class="w-full mt-3">
                @foreach ($users as $user)
                <tr class="text-sm border-b-2">
                    <td class="flex justify-between px-3">
                        <span class="mr-9 {{ $user->id == auth()->id() ? 'font-bold' : '' }}">
                            {{ $user->name }}
                        </span>
                        {{-- @dump($user->persetujuanPermintaan->where('detail_permintaan_id', $permintaan->id ?? 0))
                        --}}
                        <i class="my-1 fa-solid {{ is_null(
                                            optional($user->{" persetujuan{$tipe}"}->where('detail_' .
                            Str::lower($tipe) .
                            '_id', $permintaan->id ?? 0)->first())->status,
                            )
                            ? 'fa-circle-question text-secondary-600'
                            : (optional(
                            $user->{"persetujuan{$tipe}"}->where('detail_' . Str::lower($tipe) . '_id', $permintaan->id
                            ??
                            0)->first(),
                            )->status
                            ? 'fa-circle-check text-success-500'
                            : 'fa-circle-xmark text-danger-500') }}">
                        </i>

                    </td>
                </tr>
                @endforeach
            </table>
        </div>
        @endforeach
    </div>
</div>