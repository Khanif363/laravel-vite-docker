<!-- Sidebar backdrop -->
<div x-show.in.out.opacity="isSidebarOpen" class="fixed inset-0 z-10 bg-black bg-opacity-20 lg:hidden"
    style="backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px)"></div>
<!-- Sidebar -->
<aside x-transition:enter="transition transform duration-300"
    x-transition:enter-start="-translate-x-full opacity-30  ease-in"
    x-transition:enter-end="translate-x-0 opacity-100 ease-out" x-transition:leave="transition transform duration-300"
    x-transition:leave-start="translate-x-0 opacity-100 ease-out"
    x-transition:leave-end="-translate-x-full opacity-0 ease-in"
    class="fixed inset-y-0 z-10 flex flex-col flex-shrink-0 w-64 max-h-screen overflow-hidden transition-all transform bg-white border-r shadow-lg lg:z-auto lg:static lg:shadow-none"
    :class="{ '-translate-x-full lg:translate-x-0 lg:w-20': !isSidebarOpen }">
    <!-- sidebar header -->
    <div class="flex items-center justify-between flex-shrink-0 p-2" :class="{ 'lg:justify-center': !isSidebarOpen }">
        <span class="p-2 text-xl font-semibold leading-8 tracking-wider uppercase whitespace-nowrap text-gray-500">
            <span><i class="fas fa-bars-progress"></i></span>
            <span class="ml-2" :class="{ 'lg:hidden': !isSidebarOpen }">COMIT2027</span>
        </span>
        <button @click="toggleSidbarMenu()" class="p-2 rounded-md lg:hidden">
            <svg class="w-6 h-6 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    <!-- Sidebar links -->
    <nav class="flex-1 overflow-hidden hover:overflow-y-auto">
        <ul class="p-2 overflow-hidden">
            @php
                $search_str = ['View List ', 'View Dashboard'];
                $replace_str = ['', 'Dashboard'];
                $sidebar_menu = json_decode(str_replace($search_str, $replace_str, $permission['access_right'] ?? null), true);
            @endphp
            @foreach ($nav as $nav_value)
                @if ($permission['role'] != null &&
                    ($permission['role'] == 'Admin' || in_array($nav_value['nama'], $sidebar_menu)))
                    <li>
                        <a href="{{ $nav_value['link'] }}" data-tooltip-target="{{ $nav_value['tooltip'] }}"
                            data-tooltip-placement="right"
                            class="flex items-center py-2 px-4 space-x-2 hover:scale-105 rounded-md transition duration-200 {{ $nav_value['nama'] == $menu['utama'] ? 'text-gray-100 hover:text-gray-100 bg-tosca-0 hover:bg-tosca-0' : 'text-gray-500 hover:bg-gray-200' }}"
                            :class="{ 'justify-center': !isSidebarOpen }">
                            <span class="text-center transition duration-200 hover:scale-125 min-w-20">
                                <i class="{{ $nav_value['icon'] }}"></i>
                            </span>
                            <span class="text-sm whitespace-nowrap"
                                :class="{ 'lg:hidden': !isSidebarOpen }">{{ $nav_value['nama'] }}</span>
                        </a>
                    </li>
                @endif
            @endforeach

        </ul>
    </nav>
    <!-- Sidebar footer -->
    <div class="flex-shrink-0 p-2 border-t max-h-14">
        <a href="{{ route('auth.logout') }}" id="logout">
            <button
                class="flex items-center justify-center w-full px-4 py-2 space-x-1 font-medium tracking-wider uppercase bg-gray-100 border rounded-md focus:outline-none focus:ring">
                <span class="text-gray-500">
                    <i class="fas fa-arrow-right-from-bracket w-6 h-6"></i>
                </span>
                <span :class="{ 'lg:hidden': !isSidebarOpen }" class="text-gray-500"> Logout </span>
            </button>
        </a>
    </div>
</aside>
