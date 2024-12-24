<div class="{{ $showNav ? '' : 'hidden' }}">
    @if ($showNav)
        @if (count($child) > 0)
            <li id="dropdown{{ $title }}Button" data-dropdown-toggle="dropdown{{ $title }}"
                data-dropdown-trigger="hover"
                class="hover:bg-primary-600 hover:text-white py-6 px-4 transition duration-200 uppercase">
                {!! $title !!} <!-- Dropdown menu -->
                <div id="dropdown{{ $title }}"
                    class="z-10 hidden bg-white py-3  shadow-2xl max-w-max dark:bg-gray-700">
                    <ul class=" font-normal text-sm text-gray-700 dark:text-gray-200 capitalize"
                        aria-labelledby="dropdown{{ $title }}Button">
                        @foreach ($child as $item)
                            <li>
                                <a href="{{ $item['href'] }}"
                                    class="block px-4 py-4 -my-3  hover:bg-primary-950 transition duration-200 hover:text-white dark:hover:bg-gray-600 dark:hover:text-white">{{ $item['title'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </li>
        @else
            <a href="{{ $href }}">
                <li class="hover:bg-primary-600 hover:text-white py-6 px-4 transition duration-200 uppercase ">
                    {!! $title !!} </li>
            </a>
        @endif

    @endif
</div>
