<header class="bg-white dark:bg-gray-800 shadow-sm p-4 flex justify-between items-center">
    <div>
        <h1 class="text-lg font-semibold text-gray-800 dark:text-white">@yield('title', 'Dashboard')</h1>
    </div>
    <div class="flex items-center space-x-4">
        <button @click="toggle()" class="p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none">
            <i class="fas" :class="isDark ? 'fa-sun text-yellow-400' : 'fa-moon text-gray-500'"></i>
        </button>
        <span class="text-sm text-gray-600 dark:text-gray-400">{{ Auth::user()->name }}</span>
        <x-dropdown align="right" width="48">
            <x-slot name="trigger">
                <button class="w-8 h-8 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center focus:outline-none">
                    <i class="fas fa-user text-gray-600 dark:text-gray-300 text-sm"></i>
                </button>
            </x-slot>
            <x-slot name="content">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                        Logout
                    </x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>
    </div>
</header>
