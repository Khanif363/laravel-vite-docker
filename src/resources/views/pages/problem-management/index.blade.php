@extends('layouts.master')

@section('content')
    @push('css')
        <style>
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

            .select2-container .select2-selection--single,
            .select2-selection--multiple,
            .select2-search--dropdown .select2-search__field {
                border-color: #FFB344;
            }

            .select2-container .select2-selection--single:focus,
            .select2-search--dropdown .select2-search__field:focus {
                --tw-ring-color: #FFB344;
            }

            .dataTables_scrollHead {
                border-radius: 6px;
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
    <section class="mt-10">
        {{-- <div class="text-start md:text-end my-4">
            <span class="text-gray-600 font-medium text-base">Department IT</span>
        </div> --}}
        <div class="flex justify-end">
            <div class="hidden md:flex">
                <button type="button" data-modal-toggle="modal-excel"
                    class="inline-block px-4 py-2 border ml-2 border-gray-400 text-gray-400 font-medium text-sm leading-tight capitalize rounded-xl hover:bg-gray-400 hover:text-white focus:outline-none focus:ring-0 transition duration-150 ease-in-out">
                    <span class="mr-1"><i class="fas fa-download"></i></span>
                    <span>Export</span>
                </button>
                <button type="button" data-modal-toggle="filter"
                    class="inline-block px-4 py-2 border ml-2 border-gray-400 text-gray-400 font-medium text-sm leading-tight capitalize rounded-xl hover:bg-gray-400 hover:text-white focus:outline-none focus:ring-0 transition duration-150 ease-in-out">
                    <span class="mr-1"><i class="fas fa-filter"></i></span>
                    <span>Filter</span>
                </button>
            </div>
        </div>

        <div class="absolute bottom-3 right-3 z-10 sm:hidden">
            <button type="button" data-modal-toggle="modal-excel"
                class="flex w-10 h-10 border-2 hover:border-red-0 hover:bg-white hover:text-red-0 font-medium text-sm leading-tight capitalize shadow-md hover:shadow-lg rounded-full bg-red-0 text-white focus:outline-none focus:ring-0 transition duration-150 ease-in-out justify-center items-center">
                <span class="rounded-full m-auto"><i class="fas fa-download"></i></span>
            </button>
            <button type="button" data-modal-toggle="filter"
                class="flex w-10 h-10 border-2 hover:border-red-0 hover:bg-white hover:text-red-0 font-medium text-sm leading-tight capitalize shadow-md hover:shadow-lg rounded-full bg-red-0 text-white focus:outline-none focus:ring-0 transition duration-150 ease-in-out justify-center items-center">
                <i class="fas fa-filter"></i>
            </button>
        </div>
    </section>

    <section class="w-full h-full mt-4 mb-10">
        <table class="w-full text-sm text-center text-gray-600" id="dataTable">
            <thead class="text-md text-white font-medium capitalize bg-tosca-0">
                <tr>
                    <th class="py-6 px-10 text-center whitespace-nowrap">
                        RCA Request ID
                    </th>
                    <th class="py-6 px-10 text-center whitespace-nowrap">
                        Trouble Ticket ID
                    </th>
                    <th class="py-6 px-10 text-center whitespace-nowrap">
                        Subject
                    </th>
                    <th class="py-6 px-10 text-center whitespace-nowrap">
                        Content
                    </th>
                    <th class="py-6 px-10 text-center whitespace-nowrap">
                        Result
                    </th>
                    <th class="py-6 px-10 text-center whitespace-nowrap">
                        Status
                    </th>
                    <th class="py-6 px-10 text-center whitespace-nowrap">
                        Terakhir Update
                    </th>
                    <th class="py-6 px-10 text-center whitespace-nowrap">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </section>
    @push('scripts')
        @once
            <script type="text/javascript">
                $(document).ready(function() {
                    let filter = [];

                    const input_filter = {
                        'nomor_problem': {
                            'type': 'input',
                            'trim': true,
                        },
                        'nomor_ticket': {
                            'type': 'input',
                            'trim': true,
                        },
                        'subject': {
                            'type': 'input',
                            'trim': true,
                        },
                        'content': {
                            'type': 'input',
                            'trim': true,
                        },
                        'result': {
                            'type': 'input',
                            'trim': true,
                        },
                        'status': {
                            'type': 'select',
                            'trim': false,
                        },
                        'last_update': {
                            'type': 'select',
                            'trim': false,
                        },
                    }

                    let problem_table = $('#dataTable')
                        .DataTable({
                            processing: true,
                            orderable: true,
                            serverSide: true,
                            responsive: true,
                            stateSave: false,
                            scrollY: true,
                            scrollX: true,
                            autoWidth: false,
                            order: [0, 'desc'],
                            language: {
                                "processing": "<span class='fa-stack icon-prosessing fa-md text-gray-500'><i class='fa fa-spinner fa-spin fa-stack-2x fa-fw'></i></span>",
                                "zeroRecords": "Tidak ditemukan data yang sesuai",
                                "loadingRecords": "Sedang memuat...",
                                "emptyTable": "Tidak ada data yang tersedia pada tabel ini",
                                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                                "infoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
                                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                            },
                            ajax: {
                                url: '{{ route('problem-managements.index') }}',
                                data: function(d) {
                                    d.nomor_problem = filter[0];
                                    d.nomor_ticket = filter[1];
                                    d.subject = filter[2];
                                    d.content = filter[3];
                                    d.result = filter[4];
                                    d.status = filter[5];
                                    d.last_update = filter[6];
                                },
                            },
                            columns: [{
                                    data: 'nomor_problem',
                                    name: 'problem_manages.nomor_problem',
                                    render: function(data, type, full) {
                                        let view = `<span class="text-base font-semibold">${data}</span>`
                                        return view;
                                    }
                                },
                                {
                                    data: 'nomor_ticket',
                                    name: 'tt.nomor_ticket',
                                    render: function(data, type, full) {
                                        let view = '';
                                        if (data) {
                                            view = `<span class="text-base font-semibold">${data}</span>`
                                        } else {
                                            view = `<span>Tidak ada</span>`
                                        }
                                        return view;
                                    }
                                },
                                {
                                    data: 'subject',
                                    name: 'tt.subject'
                                },
                                {
                                    data: 'content',
                                    render: function(data, type, full) {
                                        return data
                                    }
                                },
                                {
                                    data: 'result',
                                    name: 'pmp_result.information',
                                    render: function(data, type, full) {
                                        let view = '';
                                        if (data) {
                                            view = data;
                                        } else {
                                            view = `<span class="whitespace-nowrap">Belum ada result</span>`
                                        }
                                        return view;
                                    }
                                },
                                {
                                    data: 'is_verified',
                                    render: function(data, type, full) {
                                        let view, verif1;
                                        if (data == 1) {
                                            verif1 =
                                                `<span class="space-x-2"><i class="fas fa-square-check text-green-0"></i><span>Manager</span></span>`
                                        } else if (data == 0) {
                                            verif1 =
                                                `<span class="space-x-2"><i class="far fa-square text-green-0"></i><span>Manager</span></span>`
                                        } else if (data == 2) {
                                            verif1 =
                                                `<span class="space-x-2"><i class="fas fa-square-xmark text-red-0"></i><span>Manager</span></span>`
                                        }

                                        view = `${verif1}`

                                        return view;
                                    }
                                },
                                {
                                    data: 'last_progress',
                                    name: 'pmp.progress_type',
                                    render: function(data, type, full) {
                                        let view = '';
                                        if (data) {
                                            view = data;
                                        } else {
                                            view = `<span class="whitespace-nowrap">Belum ada progress</span>`
                                        }
                                        return view;
                                    }
                                },
                                {
                                    data: 'action',
                                    name: 'id',
                                    orderable: false,
                                }
                            ],
                        });

                    $('.filter-apply').on('click', function() {
                        drawable();
                    });

                    $('#dataTable').on('change', '#grid-state', function() {
                        let val = this.value;
                        if (val == 'verif') {
                            let status = 0;
                            let url = `{{ route('problem-managements.verif', ':id') }}`;
                            let id = $(this).data('id');
                            url = url.replace(':id', id);
                            updateVerif(url, id, status);
                        } else {
                            window.location.href = val;
                        }
                        $('select#grid-state').val('');
                    })

                    function updateVerif(url, id, status) {
                        Swal.fire({
                            title: 'Silahkan tentukan status persetujuan!',
                            showDenyButton: true,
                            showCancelButton: true,
                            confirmButtonText: 'Disetujui',
                            denyButtonText: `Tidak Disetujui`,
                            confirmButtonColor: '#00A19D',
                            denyButtonColor: '#E05D5D',
                        }).then((result) => {
                            /* Read more about isConfirmed, isDenied below */
                            if (result.isConfirmed) {
                                status = 1;
                            } else if (result.isDenied) {
                                status = 2;
                            }
                            if (result.isConfirmed || result.isDenied) {
                                apiCall({
                                    url,
                                    type: 'PUT',
                                    data: {
                                        status: status
                                    },
                                    success: function(response) {
                                        if (response?.success) {
                                            Swal.fire({
                                                icon: 'success',
                                                title: response?.message,
                                                showConfirmButton: false,
                                                timer: 1500
                                            }).then(() => {
                                                window.location.href = response.data
                                                    ?.route
                                            })
                                        }
                                    }
                                })
                            }
                        })
                    }

                    function drawable() {
                        let num = 0;
                        let form_filter = $('form.form-filter');
                        for (const [key, {
                                trim
                            }] of Object.entries(input_filter)) {
                            if (trim == true) {
                                filter[num] = $(`#${key}`).val().trim();
                                form_filter.append(
                                    `<input name="${key}" type="text" value="${filter[num]}" hidden>`);
                                num++;
                            } else {
                                filter[num] = $(`#${key}`).val();
                                form_filter.append(
                                    `<input name="${key}" type="text" value="${filter[num]}" hidden>`);
                                num++;
                            }
                        }
                        problem_table.draw();
                    }

                    drawable();

                    $('.clear').on('click', function() {
                        for (const [key, {
                                type
                            }] of Object.entries(input_filter)) {
                            if (type == 'select') {
                                $(`#${key}`).val('').trigger('change');
                            } else {
                                $(`#${key}`).val('');
                            }
                        }
                        filter.length = 0;
                        $("form.form-filter :input[name!='format']").val("").removeAttr("checked").removeAttr("selected");
                        problem_table.draw();
                    });

                    $('.verif').click(function() {
                        let id = 3;
                        verifProblem(id);
                    })

                    function verifProblem(id) {
                        let url = `{{ route('problem-managements.verif', ':id') }}`;
                        url = url.replace(':id', id);
                        Swal.fire({
                            title: 'Konfirmasi?',
                            text: "Setelah dikonfirmasi, tidak bisa dirollback!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ya, Konfirmasi'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: url,
                                    method: 'PUT',
                                    data: {
                                        _token: '{{ csrf_token() }}'
                                    },
                                    success: function(response) {
                                        Swal.fire(
                                            `${response.title}`,
                                            `${response.text}`,
                                            `${response.type}`
                                        )
                                        drawable();
                                    },
                                    error: function(xhr) {
                                        Swal.fire(
                                            `${response.title}`,
                                            `${response.text}`,
                                            `${response.type}`
                                        );
                                    }
                                });
                            }
                        });
                    }
                });
            </script>
        @endonce
    @endpush
@endsection
@section('modal')
    <!-- Small Modal -->
    <div id="filter" tabindex="-1"
        class="fixed top-10 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
        <div class="relative w-full h-full max-w-lg md:h-auto inset-y-4">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-medium text-gray-900 dark:text-white">
                        Filter
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                        data-modal-toggle="filter">
                        <div>
                            <span class="sr-only">Close modal</span>
                            <i class="fas fa-xmark fa-xl"></i>
                        </div>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-6 space-y-6">
                    <div class="space-y-2">
                        <div class="space-y-1 w-full flex flex-col">
                            <label for="nomor_problem" class="text-gray-600">Nomor Problem:</label>
                            <input type="text" name="nomor_problem" id="nomor_problem"
                                class="w-full p-2 py-2 bg-white border-1.5 border-yellow-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                                            focus:outline-none focus:ring-1 focus:ring-yellow-0
                                            disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        </div>
                        <div class="space-y-1 w-full flex flex-col">
                            <label for="nomor_ticket" class="text-gray-600">Nomor Ticket:</label>
                            <input type="text" name="nomor_ticket" id="nomor_ticket"
                                class="w-full p-2 py-2 bg-white border-1.5 border-yellow-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                                            focus:outline-none focus:ring-1 focus:ring-yellow-0
                                            disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        </div>
                        <div class="space-y-1 w-full flex flex-col">
                            <label for="subject" class="text-gray-600">Subject:</label>
                            <input type="text" name="subject" id="subject"
                                class="w-full p-2 py-2 bg-white border-1.5 border-yellow-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                                            focus:outline-none focus:ring-1 focus:ring-yellow-0
                                            disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        </div>
                        <div class="space-y-1 w-full flex flex-col">
                            <label for="content" class="text-gray-600">Content:</label>
                            <input type="text" name="content" id="content"
                                class="w-full p-2 py-2 bg-white border-1.5 border-yellow-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                                            focus:outline-none focus:ring-1 focus:ring-yellow-0
                                            disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        </div>
                        <div class="space-y-1 w-full flex flex-col">
                            <label for="result" class="text-gray-600">Result:</label>
                            <input type="text" name="result" id="result"
                                class="w-full p-2 py-2 bg-white border-1.5 border-yellow-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                                            focus:outline-none focus:ring-1 focus:ring-yellow-0
                                            disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        </div>
                        <div class="space-y-1 w-full flex flex-col">
                            <label for="status" class="text-gray-600">Status:</label>
                            <select type="number" name="status" id="status" class="w-full">
                                <option value="">-- Pilih Status --</option>
                                <option value="1">Approved</option>
                                <option value="0">Await Approval</option>
                                <option value="2">DisApproved</option>
                            </select>
                        </div>
                        <div class="space-y-1 w-full flex flex-col">
                            <label for="last_update" class="text-gray-600">Last Update:</label>
                            <select type="text" name="last_update" id="last_update" class="w-full">
                                <option value="">-- Pilih Prioritas --</option>
                                <option value="Analysis">Analysis</option>
                                <option value="Result">Result</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button data-modal-toggle="filter" type="button"
                        class="filter-apply text-white bg-tosca-0 hover:bg-tosca-1 focus:bg-tosca-1 active:bg-tosca-2 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">Filter</button>
                    <button data-modal-toggle="filter" type="button"
                        class="text-gray-500 clear bg-white hover:bg-gray-100 focus:outline-none rounded-xl border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">Clear</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-excel" tabindex="-1"
        class="fixed top-0 left-0 right-0 z-50 hidden p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
        <div class="relative w-full h-full max-w-md md:h-auto">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="p-6 text-center">
                    <h3 class="block mb-5 text-xl font-normal text-gray-500 dark:text-gray-400">Silahkan lengkapi form
                        berikut</h3>
                    <form action="{{ route('problem-managements.export') }}" class="form-filter">
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
