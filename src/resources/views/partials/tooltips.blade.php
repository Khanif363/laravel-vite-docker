@foreach ($nav as $nav_value)
    <div id="{{ $nav_value['tooltip'] }}" role="tooltip" :class="{ 'hidden': isSidebarOpen }"
        class="inline-block absolute invisible z-50 py-2 px-3 text-sm font-medium text-white bg-tosca-0 border-[1.5px] rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
        {{ $nav_value['nama'] }}
        <div class="tooltip-arrow" data-popper-arrow></div>
    </div>
@endforeach
