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

            table.dataTable thead .sorting {
                background-image: url('{{ asset('assets/img/sort.png') }}') !important;
                background-repeat: no-repeat;
                background-position: center right 15px;
                /* scale: 2; */
            }

            table.dataTable thead .sorting_desc {
                background-image: url('{{ asset('assets/img/sort-down.png') }}') !important;
                background-repeat: no-repeat;
                background-position: center right 15px;
            }

            table.dataTable thead .sorting_asc {
                background-image: url('{{ asset('assets/img/sort-up.png') }}') !important;
                background-repeat: no-repeat;
                background-position: center right 15px;
            }

            table.dataTable tbody tr {
                border-bottom: 1px solid #CFCFCF;
            }

            table.dataTable tbody tr.even {
                background-color: #eee
            }

            table.dataTable tbody td {
                padding: 10px;
            }

            .dataTables_length {
                float: left;
                margin-left: 10px;
                margin: 15px 0;
                color: rgb(75 85 99);
            }

            .dataTables_filter {
                float: right;
                margin-right: 10px;
                margin: 15px 0;
                color: rgb(75 85 99);
                bottom: 0;
                */
            }

            .dataTables_info {
                float: left;
                margin-left: 10px;
                color: rgb(75 85 99);
                margin: 15px 0;
            }

            .dataTables_paginate {
                float: right;
                margin-right: 10px;
                color: rgb(75 85 99);
                margin: 15px 0;
            }

            .dataTables_length select[name='dataTable_length'] {
                appearance: none;
                padding: 2px 10px;
                width: 75px;
                border-width: 1px;
                border-color: #00A19D;
                border-radius: 10px;
                transition: transform 1s;
            }

            .dataTables_filter input[type='search'] {
                padding: 2px 10px;
                width: 200px;
                border-width: 1px;
                border-color: #00A19D;
                border-radius: 10px;
                margin-left: 5px;
                transition: transform 1s;
            }

            .dataTables_filter input[type='search']:focus,
            .dataTables_length select[name='dataTable_length']:focus {
                border-width: 2px;
                border-color: #00A19D !important;
                outline: none;
            }

            td.dataTables_empty {
                position: flex;
                align-items: center;
                text-align: center;
                color: rgb(75 85 99);
                padding: 15px !important;
            }

            .paginate_button {
                /* border-width: 1px; */
                /* border-color: black; */
                border-radius: 10px;
                padding: 3px 8px;
                cursor: pointer;
                margin: 3px;
            }

            a.paginate_button.current {
                border-radius: 10px;
                padding: 3px 8px;
                cursor: pointer;
                margin: 3px;
                color: #00A19D;
                size: 120px
            }

            .dataTables_paginate .previous {
                border-radius: 10px;
                padding: 3px 8px;
                cursor: pointer;
                background-color: #00A19D;
                color: white;
                margin-rigth: 3px;
            }

            .dataTables_paginate .next {
                border-radius: 10px;
                padding: 3px 8px;
                cursor: pointer;
                background-color: #00A19D;
                color: white;
                margin-left: 3px;
            }

            .dataTables_paginate .disabled {
                border-radius: 10px;
                padding: 3px 8px;
                cursor: pointer;
                background-color: #CFCFCF;
                color: white;
                margin: 3px;
            }

            th {
                cursor: pointer;
            }

            .select2-container--default .select2-selection--single .select2-selection__arrow b {
                color: #00A19D !important;
            }

            .dataTables_processing {
                position: relative;
                text-align: center;
                display: flex;
                justify-content: center;
            }

            .icon-prosessing {
                width: 100%;
                position: absolute;
                margin: auto;
                display: flex;
                justify-content: center !important;
            }
        </style>
    @endpush
    <section>
        <div class="flex justify-end">
            <div class="hidden md:flex">
                <button type="button" data-modal-toggle="modal-excel"
                    class="inline-block px-4 py-2 border ml-2 border-gray-400 text-gray-400 font-medium text-sm leading-tight capitalize rounded-xl hover:bg-gray-400 hover:text-white focus:outline-none focus:ring-0 transition duration-150 ease-in-out">
                    <span class="mr-1"><i class="fas fa-download"></i></span>
                    <span>Export</span>
                </button>
            </div>

        </div>


        <div class="absolute bottom-3 flex flex-col space-y-1 right-3 z-10 sm:hidden">
            <button type="button" data-modal-toggle="modal-excel"
                class="flex w-10 h-10 border-2 hover:border-yellow-0 hover:bg-white hover:text-yellow-0 font-medium text-sm leading-tight capitalize shadow-md hover:shadow-lg rounded-full bg-yellow-0 text-white focus:outline-none focus:ring-0 transition duration-150 ease-in-out justify-center items-center">
                <span class="rounded-full m-auto"><i class="fas fa-download"></i></span>
            </button>
            <a href="{{ route('departments.create') }}"
                class="flex w-10 h-10 border-2 hover:border-yellow-0 hover:bg-white hover:text-yellow-0 font-medium text-sm leading-tight capitalize shadow-md hover:shadow-lg rounded-full bg-yellow-0 text-white focus:outline-none focus:ring-0 transition duration-150 ease-in-out justify-center items-center">
                <i class="fas fa-plus"></i>
            </a>
        </div>
    </section>

    <section class="w-full h-full mt-4 mb-10 overflow-x-auto">
        <table class="w-full text-sm text-center text-gray-600" id="dataTable">
            <thead class="text-md text-white font-medium capitalize bg-yellow-0">
                <tr>
                    <th class="py-6 px-10 text-center whitespace-nowrap rounded-tl-md rounded-bl-md">
                        No.
                    </th>
                    <th class="py-6 px-10 text-center whitespace-nowrap">
                        Nama Department
                    </th>
                    <th class="py-6 px-10 text-center whitespace-nowrap">
                        Status
                    </th>
                    <th class="py-6 px-10 text-center whitespace-nowrap">
                        Info
                    </th>
                    <th class="py-6 px-10 text-center whitespace-nowrap rounded-tr-md rounded-br-md">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $departments)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $departments->name }}</td>
                        <td><span
                                class="rounded text-white {{ $departments->status == 'Active' ? 'bg-green-0' : 'bg-grey-2' }} py-1 px-2">{{ $departments->status }}</span>
                        </td>
                        <td>
                            <div class="flex whitespace-nowrap flex-row space-x-2 items-center"><i
                                    class="fas {{ $departments->is_desk ? 'fa-check text-green-500' : 'fa-xmark text-pink-500' }}"></i><span>Service
                                    Desk</span></div>
                            <div class="flex whitespace-nowrap flex-row space-x-2 items-center"><i
                                    class="fas {{ $departments->is_core ? 'fa-check text-green-500' : 'fa-xmark text-pink-500' }}"></i><span>Core</span>
                            </div>
                        </td>
                        <td>
                            <div class="flex flex-row space-x-2 justify-center">
                                @if (($permission['role'] ?? null) == 'Admin' || in_array('Update Department', (array) $submenu_middleware))
                                    <a href="{{ route('departments.edit', $departments->id) }}"
                                        class="inline-block px-3 py-1.5 border-2 ml-2 border-yellow-0 text-yellow-0 font-medium text-sm leading-tight capitalize shadow-md hover:shadow-lg rounded-md hover:bg-yellow-0 hover:text-white focus:outline-none focus:ring-0 transition duration-150 ease-in-out">
                                        <span class="mr-1"><i class="fas fa-pen-to-square"></i></span>
                                        <span>Edit</span>
                                    </a>
                                @endif
                                @if (($permission['role'] ?? null) == 'Admin' || in_array('Delete Department', $submenu_middleware))
                                    <form action="{{ route('departments.delete', $departments->id) }}" method="POST" class="delete">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit"
                                            class="flex items-center px-3 py-1.5 border-2 ml-2 border-red-0 text-red-0 font-medium text-sm leading-tight capitalize shadow-md hover:shadow-lg rounded-md hover:bg-red-0 hover:text-white focus:outline-none focus:ring-0 transition duration-150 ease-in-out">
                                            <span class="mr-1"><i class="fas fa-pen-to-square"></i></span>
                                            <span>Delete</span>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
    @push('scripts')
        @once
            <script type="text/javascript">
                let id = 0;
                $(document).ready(function() {
                    $('#dataTable').DataTable({
                        columnDefs: [{
                            orderable: false,
                            targets: 4
                        }],
                    });
                });
            </script>
        @endonce
    @endpush
@endsection
@section('modal')
    <div id="modal-excel" tabindex="-1"
        class="fixed top-0 left-0 right-0 z-50 hidden p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
        <div class="relative w-full h-full max-w-md md:h-auto">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="p-6 text-center">
                    <h3 class="block mb-5 text-xl font-normal text-gray-500 dark:text-gray-400">Silahkan pilih format</h3>
                    <form action="{{ route('departments.export') }}" class="form-filter">
                        <div class="flex flex-col space-y-6">
                            <div class="flex justify-center">
                                <div class="flex justify-center items-center w-2/4">
                                    <select type="text" name="format">
                                        <option value="" disabled>-- Pilih Format --</option>
                                        <option value="xlsx" selected>Excel</option>
                                        <option value="csv">Csv</option>
                                    </select>
                                </div>
                            </div>
                            <div class="flex flex-row justify-center items-center space-x-2">
                                <button data-modal-toggle="modal-excel" type="submit"
                                    class="text-white bg-tosca-0 hover:bg-tosca-1 focus:bg-tosca-1 active:bg-tosca-2 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                                    Download
                                </button>
                                <a data-modal-toggle="modal-excel"
                                    class="cursor-pointer text-gray-500 bg-white hover:bg-gray-100 focus:outline-none rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
