<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Card extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public $title)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return <<<'blade'
    <div
    {{ $attributes->merge(['class' => 'w-full bg-white border border-gray-200 rounded-lg shadow overflow-hidden dark:bg-gray-800 dark:border-gray-700']) }}>
        <div
            class="bg-primary-100 flex flex-wrap text-sm rounded-t-lg font-medium text-center text-gray-500 border-b border-gray-200 dark:border-gray-700 dark:text-gray-400 dark:bg-gray-800">
            <h1
                class="inline-block uppercase px-4 py-2 text-primary-900 text-xl font-bold dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-blue-500">
                {{ $title }}</h1>
        </div>
        <div>
            <div class=" p-4 bg-white rounded-lg md:p-8 dark:bg-gray-800">
                {{ $slot }}
            </div>
        </div>
    </div>
blade;
    }
}
