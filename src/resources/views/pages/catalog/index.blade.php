@extends('layouts.master')

@section('content')
    @push('css')
        <style>
            .select2-container .select2-selection--single,
            .select2-selection--multiple,
            .select2-search--dropdown .select2-search__field {
                border-color: #FFB344;
            }

            .select2-container .select2-selection--single:focus,
            .select2-search--dropdown .select2-search__field:focus {
                --tw-ring-color: #FFB344;
            }
        </style>
    @endpush
    <section>
        <div class="px-8 py-4 md:px-8 md:py-5 bg-[#F4F4F4] rounded-xl">
            <div class="flex justify-end w-full">
                <form action="{{ route('catalogs.index') }}" method="GET" class="flex flex-row space-x-2">
                    <div class="relative flex justify-start max-w-sm flex-shrink-0">
                        @csrf
                        <input name="title" type="text" id="title"
                            class="w-full pl-9 pr-2 py-2 bg-white border-1.5 border-yellow-0 rounded-xl text-sm shadow-sm placeholder-slate-400
          focus:outline-none focus:ring-1 focus:ring-yellow-0
          disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none
          invalid:border-pink-500 invalid:text-pink-600
          focus:invalid:border-pink-500 focus:invalid:ring-pink-500 text-gray-600"
                            placeholder="Search">
                        <i
                            class="fas fa-magnifying-glass h-6 w-6 absolute my-2.5 ml-3 pointer-events-none text-yellow-0"></i>

                    </div>
                    <button
                        class="inline-block status cursor-pointer px-6 py-2.5 font-medium text-sm leading-tight uppercase rounded-xl shadow-md hover:shadow-lg focus:shadow-lg focus:outline-none focus:ring-0 active:shadow-lg transition duration-150 ease-in-out bg-yellow-0 text-white hover:bg-yellow-1 focus:bg-yellow-1 active:bg-yellow-2">Cari</button>
                </form>
            </div>
            <div class="rounded-xl bg-white p-4 mt-6">
                <div id="accordion-flush" data-accordion="collapse"
                    data-active-classes="bg-white dark:bg-gray-900 text-gray-700 dark:text-white"
                    data-inactive-classes="text-gray-500 dark:text-gray-400">
                    {{-- <div id="search-results"></div> --}}
                    @foreach ($data as $catalogs)
                        <h2 id="accordion-catalog-head-{{ $loop->iteration }}">
                            <button type="button"
                                class="flex items-center justify-between w-full py-5 font-medium text-left border-b text-gray-500 border-gray-200 dark:border-gray-700 dark:text-gray-400"
                                data-accordion-target="#accordion-catalog-body-{{ $loop->iteration }}" aria-expanded="true"
                                aria-controls="accordion-catalog-body-{{ $loop->iteration }}">
                                <span>{{ $catalogs->title }}</span>
                                <span data-accordion-icon
                                    class="w-6 h-6 rotate-180 shrink-0 flex justify-center items-center">
                                    <i class="fas fa-angle-down"></i>
                                </span>
                            </button>
                        </h2>
                        <div id="accordion-catalog-body-{{ $loop->iteration }}" class="hidden"
                            aria-labelledby="accordion-catalog-head-{{ $loop->iteration }}">
                            <div class="py-5 font-light border-b border-gray-200 dark:border-gray-700">
                                <p class="mb-2 text-gray-500 font-normal dark:text-gray-400">{{ $catalogs->information }}</p>
                            </div>
                        </div>
                    @endforeach

                </div>

            </div>
        </div>
    </section>
    @push('scripts')
        @once
            <script type="text/javascript">
                // $(document).ready(function() {
                // })
            </script>
        @endonce
    @endpush
@endsection
