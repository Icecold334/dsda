<li id="dropdownNotificationButton" data-dropdown-toggle="dropdownNotification" data-dropdown-trigger="hover"
    class="hover:bg-primary-600 hover:text-white py-6 px-4 transition duration-200 uppercase relative">
    <i class="fas fa-bell"></i> <!-- Ikon Lonceng -->
    @if (auth()->user()->unreadNotifications->count() > 0)
        <!-- Badge untuk jumlah notifikasi -->
        <span class="absolute top-4 right-2 text-center bg-red-500 text-white text-xs font-bold rounded-full w-4 h-4">
            {{ auth()->user()->unreadNotifications->count() }}
        </span>
    @endif
    <!-- Dropdown Notifikasi -->
    <div id="dropdownNotification" class="z-10 hidden bg-white shadow-2xl max-w-md dark:bg-gray-700 w-80">
        <ul class="font-normal text-sm text-gray-700 dark:text-gray-200 capitalize max-h-60 overflow-y-auto"
            aria-labelledby="dropdownNotificationButton">
            @forelse (auth()->user()->notifications as $notification)
                <li>
                    <div wire:click="markAsRead('{{ $notification->id }}','{{ $notification->data['url'] }}')"
                        class="flex cursor-pointer group justify-between px-4 py-2 hover:bg-primary-950 transition duration-200 hover:text-white dark:hover:bg-gray-600 dark:hover:text-white">
                        <div>{!! $notification->data['message'] !!}</div>
                        <div>
                            @if (!$notification->read_at)
                                <span
                                    class="text-xs text-blue-500 group-hover:text-white font-semibold ml-2">Baru</span>
                            @endif
                        </div>


                    </div>
                </li>
            @empty
                <li class="px-4 py-3 text-gray-500 text-sm">Tidak ada notifikasi</li>
            @endforelse
        </ul>
        @if (auth()->user()->notifications->count() > 0)
            <div class="flex">
                <div class="border-t px-4 py-3 text-center group hover:bg-primary-600 transition duration-200">
                    <button wire:click="markAllAsRead"
                        class="text-sm font-semibold text-primary-500 group-hover:text-white transition duration-200">Tandai
                        Semua
                        Dibaca</button>
                </div>
                <div class="border-t px-4  py-3 text-center group hover:bg-danger-600">
                    <button wire:click="markAllAsRead"
                        class="text-sm  font-semibold text-danger-500 group-hover:text-white transition duration-200">Hapus
                        Semua</button>
                </div>
            </div>
        @endif
    </div>
</li>
