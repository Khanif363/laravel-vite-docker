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
    <section class="mt-10">
        {{-- <div class="my-4 text-start md:text-end">
            <span class="text-base font-medium text-gray-500">Sub Unit IT</span>
        </div> --}}
        <div class="flex justify-between">
            <div class="flex justify-center text-left flex-nowrap">
                <a data-status="open"
                    class="inline-block status cursor-pointer px-6 py-2.5 font-semibold text-sm leading-none uppercase rounded-xl focus:shadow-lg focus:outline-none focus:ring-0 active:shadow-lg transition duration-150 ease-in-out bg-yellow-0 text-white hover:bg-yellow-1 focus:bg-yellow-1 active:bg-yellow-2">Open</a>
                <a data-status="pending"
                    class="inline-block status cursor-pointer px-6 py-2.5 mx-2 font-medium text-sm leading-none uppercase rounded-xl focus:shadow-lg focus:outline-none focus:ring-0 active:shadow-lg transition duration-150 ease-in-out bg-grey-0 text-gray-500 hover:bg-grey-1 focus:bg-grey-1 active:bg-grey-2">Pending</a>
                <a data-status="closed"
                    class="inline-block status cursor-pointer px-6 py-2.5 font-medium text-sm leading-none uppercase rounded-xl focus:shadow-lg focus:outline-none focus:ring-0 active:shadow-lg transition duration-150 ease-in-out bg-grey-0 text-gray-500 hover:bg-grey-1 focus:bg-grey-1 active:bg-grey-2">Closed</a>
            </div>


            <div class="hidden md:flex">
                @if ($permission['role'] === 'Admin' || isset($access_with_department['Create Ticket']))
                    <a href="{{ route('tickets.create') }}"
                        class="inline-block px-4 py-2 text-sm font-medium leading-tight capitalize transition duration-150 ease-in-out border border-yellow-0 text-yellow-0 rounded-xl hover:bg-yellow-0 hover:text-white focus:outline-none focus:ring-0">
                        <span class="mr-1"><i class="fas fa-plus"></i></span>
                        <span>Create Ticket</span>
                    </a>
                @endif
                <button type="button" data-modal-toggle="modal-excel"
                    class="inline-block px-4 py-2 ml-2 text-sm font-medium leading-tight text-gray-400 capitalize transition duration-150 ease-in-out border border-gray-400 rounded-xl hover:bg-gray-400 hover:text-white focus:outline-none focus:ring-0">
                    <span class="mr-1"><i class="fas fa-download"></i></span>
                    <span>Export</span>
                </button>
                <button type="button" data-modal-toggle="filter"
                    class="inline-block px-4 py-2 ml-2 text-sm font-medium leading-tight text-gray-400 capitalize transition duration-150 ease-in-out border border-gray-400 rounded-xl hover:bg-gray-400 hover:text-white focus:outline-none focus:ring-0">
                    <span class="mr-1"><i class="fas fa-filter"></i></span>
                    <span>Filter</span>
                </button>
            </div>
        </div>

        <div class="absolute z-10 flex flex-col space-y-1 bottom-3 right-3 sm:hidden">
            <a href="{{ route('tickets.create') }}"
                class="flex items-center justify-center w-10 h-10 text-sm font-medium leading-tight text-white capitalize transition duration-150 ease-in-out border rounded-full shadow-md hover:border-red-0 hover:bg-white hover:text-red-0 hover:shadow-lg bg-red-0 focus:outline-none focus:ring-0">
                <span class="m-auto rounded-full"><i class="fas fa-plus"></i></span>
            </a>
            <button type="button" data-modal-toggle="modal-excel"
                class="flex items-center justify-center w-10 h-10 text-sm font-medium leading-tight text-white capitalize transition duration-150 ease-in-out border rounded-full shadow-md hover:border-red-0 hover:bg-white hover:text-red-0 hover:shadow-lg bg-red-0 focus:outline-none focus:ring-0">
                <span class="m-auto rounded-full"><i class="fas fa-download"></i></span>
            </button>
            <button type="button" data-modal-toggle="filter"
                class="flex items-center justify-center w-10 h-10 text-sm font-medium leading-tight text-white capitalize transition duration-150 ease-in-out border rounded-full shadow-md hover:border-red-0 hover:bg-white hover:text-red-0 hover:shadow-lg bg-red-0 focus:outline-none focus:ring-0">
                <i class="fas fa-filter"></i>
            </button>
        </div>
    </section>

    <section class="w-full h-full mt-4 mb-10">
        <table class="w-full text-sm text-center text-gray-600" id="dataTable">
            <thead class="font-medium text-white capitalize text-md bg-tosca-0">
                <tr>
                    <th class="px-10 py-6 text-center whitespace-nowrap">
                        Ticket ID
                    </th>
                    <th class="px-10 py-6 text-center whitespace-nowrap">
                        Prioritas
                    </th>
                    <th class="px-10 py-6 text-center whitespace-nowrap">
                        Jenis Tiket
                    </th>
                    {{-- <th class="px-10 py-6 text-center whitespace-nowrap">
                    Departemen
                </th>
                <th class="px-10 py-6 text-center whitespace-nowrap">
                    Layanan
                </th> --}}
                    <th class="px-10 py-6 text-center whitespace-nowrap">
                        Subjek
                    </th>
                    <th class="px-10 py-6 text-center whitespace-nowrap">
                        Progress Terakhir
                    </th>
                    {{-- <th class="px-10 py-6 text-center whitespace-nowrap">
                    Last Update by
                </th> --}}
                    {{-- <th class="px-10 py-6 text-center whitespace-nowrap">
                    Tanggal Open
                </th> --}}
                    <th class="px-10 py-6 text-center whitespace-nowrap">
                        Pembuat
                    </th>
                    {{-- <th class="px-10 py-6 text-center whitespace-nowrap">
                    Sumber Info Gangguan
                </th>
                <th class="px-10 py-6 text-center whitespace-nowrap">
                    Lokasi Kejadian
                </th>
                <th class="px-10 py-6 text-center whitespace-nowrap">
                    Selesai Dihandle
                </th>
                <th class="px-10 py-6 text-center whitespace-nowrap">
                    Durasi Berjalan
                </th> --}}
                    <th class="px-10 py-6 text-center whitespace-nowrap">
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
                // document.addEventListener('DOMContentLoaded', function() {
                //     Flowbite.init()
                // })
                $(document).ready(function() {
                    // console.log('{{ $status }}');
                    let filter = [];
                    let on_status = 'open';
                    let filter_status;
                    let department_filter;

                    const input_filter = {
                        'nomor_ticket': {
                            'type': 'input',
                            'trim': true,
                        },
                        'priority': {
                            'type': 'select',
                            'trim': false,
                        },
                        'type': {
                            'type': 'select',
                            'trim': false,
                        },
                        'department': {
                            'type': 'select',
                            'trim': false,
                        },
                        'service': {
                            'type': 'select',
                            'trim': false,
                        },
                        'subject': {
                            'type': 'input',
                            'trim': true,
                        },
                        'update_type': {
                            'type': 'select',
                            'trim': false,
                        },
                        'progress_inputer': {
                            'type': 'select',
                            'trim': false,
                        },
                        'created_date': {
                            'type': 'input',
                            'trim': false,
                        },
                        'last_updated_date': {
                            'type': 'input',
                            'trim': false,
                        },
                        'creator': {
                            'type': 'select',
                            'trim': false,
                        },
                        'source_info_trouble': {
                            'type': 'input',
                            'trim': true,
                        },
                        'event_location_id': {
                            'type': 'select',
                            'trim': false,
                        },
                        'technical_closed_date': {
                            'type': 'input',
                            'trim': true,
                        },
                        'status': {
                            'type': 'select',
                            'trim': false,
                        },
                    }

                    let ticket_table = $('#dataTable')
                        .DataTable({
                            processing: true,
                            orderable: true,
                            serverSide: true,
                            responsive: true,
                            stateSave: false,
                            scrollY: true,
                            scrollX: true,
                            autoWidth: false,
                            order: [
                                [0, 'desc']
                            ],
                            deferRender : true,
                            language: {
                                "processing": "<span class='text-gray-500 fa-stack icon-prosessing fa-md'><i class='fa fa-spinner fa-spin fa-stack-2x fa-fw'></i></span>",
                                "zeroRecords": "Tidak ditemukan data yang sesuai",
                                "loadingRecords": "Sedang memuat...",
                                "emptyTable": "Tidak ada data yang tersedia pada tabel ini",
                                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                                "infoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
                                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                            },
                            ajax: {
                                url: '{{ route('tickets.index') }}',
                                data: function(d) {
                                    d.request = {
                                        'nomor_ticket': filter[0],
                                        'priority': filter[1],
                                        'type': filter[2],
                                        'department': filter[3],
                                        'service': filter[4],
                                        'subject': filter[5],
                                        'update_type': filter[6],
                                        'progress_inputer': filter[7],
                                        'created_date': filter[8],
                                        'last_updated_date': filter[9],
                                        'creator': filter[10],
                                        'source_info_trouble': filter[11],
                                        'event_location_id': filter[12],
                                        'technical_closed_date': filter[13],
                                        'status': on_status
                                    };
                                },
                            },
                            columns: [{
                                    data: 'nomor_ticket',
                                    name: 'nomor_ticket',
                                },
                                {
                                    data: 'priority',
                                    render: function(data, type, full) {
                                        let view;
                                        if (data == 'Low') {
                                            view =
                                                `<span class="px-2 py-1 text-white rounded bg-green-0">${data}</span>`;
                                        } else if (data == 'Medium') {
                                            view =
                                                `<span class="px-2 py-1 text-white rounded bg-yellow-0">${data}</span>`;
                                        } else if (data == 'High') {
                                            view =
                                                `<span class="px-2 py-1 text-white rounded bg-red-0">${data}</span>`;
                                        } else {
                                            view = 'Belum ditentukan';
                                        }
                                        return view;
                                    }
                                },
                                {
                                    data: 'id',
                                    render: function(data, type, full) {
                                        return full.type;
                                    }
                                },
                                {
                                    data: 'subject',
                                },
                                {
                                    data: 'last_progress',
                                    name: 'ttp.update_type',
                                    render: function(data, type, full) {
                                        let view;

                                        if (data) {
                                            view = `<span>${data}</span>`;
                                        } else {
                                            view = `<span>Belum ada Progress</span>`;
                                        }

                                        return view;
                                    }
                                },
                                {
                                    data: 'creator_name',
                                    name: 'ctr.full_name',
                                },
                                {
                                    data: 'action',
                                    name: 'id',
                                    orderable: false,
                                },
                            ],
                        });

                    sessionStorage.setItem('update_type', null);

                    $('#dataTable').on('click', '[data-type="dropdown"]', function() {
                        const idDropdown = $(this).data('dropdown-toggle')

                        $(`#${idDropdown}`).toggleClass('hidden')
                    })

                    $('#dataTable').on('change', '#grid-state', function() {
                        let id = this.dataset.id;
                        let val = this.value.replace(':id', id);

                        switch (val) {
                            // case 'send-email':
                            //     // sendEmail(id);
                            //     break;
                            case 'delete-ticket':
                                deleteTicket(id)
                                break;
                            default:
                                window.location.href = val;
                        }
                        $('select#grid-state').val('');
                    })

                    $('.filter-apply').on('click', function() {
                        drawable();
                    })

                    function getByDepartment(department) {
                        department_filter = department
                        drawable();
                    }

                    function getByStatus(status) {
                        on_status = status;
                        let status_class = $('a.status');
                        let current_status_element = $(`a[data-status="${status}"]`)[0];
                        let compare_status = status_class.data('status');
                        if (status != compare_status) {
                            status_class.removeClass(
                                'bg-yellow-0 text-white hover:bg-yellow-1 font-semibold focus:bg-yellow-1 active:bg-yellow-2'
                            );
                            status_class.addClass(
                                'bg-grey-0 text-gray-500 hover:bg-grey-1 font-medium focus:bg-grey-1 active:bg-grey-2'
                            );
                            $(current_status_element).removeClass(
                                'bg-grey-0 text-gray-500 hover:bg-grey-1 font-medium focus:bg-grey-1 active:bg-grey-2'
                            );
                            $(current_status_element).addClass(
                                'bg-yellow-0 text-white hover:bg-yellow-1 font-semibold focus:bg-yellow-1 active:bg-yellow-2'
                            );
                        } else {
                            status_class.removeClass(
                                'bg-yellow-0 text-white hover:bg-yellow-1 font-semibold focus:bg-yellow-1 active:bg-yellow-2'
                            );
                            status_class.addClass(
                                'bg-grey-0 text-gray-500 hover:bg-grey-1 font-medium focus:bg-grey-1 active:bg-grey-2'
                            );
                            $(current_status_element).removeClass(
                                'bg-grey-0 text-gray-500 hover:bg-grey-1 font-medium focus:bg-grey-1 active:bg-grey-2'
                            );
                            $(current_status_element).addClass(
                                'bg-yellow-0 text-white hover:bg-yellow-1 font-semibold focus:bg-yellow-1 active:bg-yellow-2'
                            );
                        }
                        load();
                    }

                    if ('{{ $status }}') getByStatus('{{ $status }}')

                    if ('{{ $department }}') getByDepartment('{{ $department }}')

                    $('a.status').click(function() {
                        let status = $(this).data('status');
                        getByStatus(status)
                    });

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
                                if (key == 'department' && department_filter) {
                                    filter[num] = department_filter;
                                    form_filter.append(
                                        `<input name="${key}" type="text" value="${filter[num]}" hidden>`);
                                    num++;
                                    continue
                                }
                                filter[num] = $(`#${key}`).val();
                                form_filter.append(
                                    `<input name="${key}" type="text" value="${filter[num]}" hidden>`);
                                num++;
                            }
                            if (key == 'status') {
                                form_filter.append(
                                    `<input name="${key}" type="text" value="${on_status}" hidden>`);
                            }
                        }
                        ticket_table.draw();
                    }

                    load();

                    function load() {
                        drawable();
                        loadStatus();
                    }


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
                        $("form.form-filter :input[name!='format'][id!='export_status']").val("").removeAttr(
                            "checked").removeAttr("selected");
                        ticket_table.draw();
                    })

                    $('#export_status').change(function() {
                        loadStatus();
                    });

                    function loadStatus() {
                        let filter_status = $('#export_status').val();
                        if (filter_status != 'all') {
                            $('form.form-filter').append(
                                `<input name="status" type="text" value="${on_status}" hidden>`);
                        } else {
                            $('form.form-filter').append(
                                `<input name="status" type="text" value="${filter_status}" hidden>`);
                        }
                    }

                    async function selectReceiver() {
                        const {
                            value: receiver
                        } = await Swal.fire({
                            title: 'Silahkan pilih penerima notifikasi',
                            input: 'select',
                            inputOptions: {
                                manager: 'manager',
                                engineer: 'engineer',
                            },
                            inputPlaceholder: 'Pilih penerima',
                            showCancelButton: true,
                            inputValidator: (value) => {
                                return new Promise((resolve) => {
                                    if (!value) {
                                        resolve("Tidak boleh kosong!")
                                    }
                                    resolve()
                                })
                            }
                        })

                        return receiver
                    }

                    async function getContentPush() {
                        const {
                            value: text
                        } = await Swal.fire({
                            title: 'Masukkan Message',
                            input: 'textarea',
                            // inputLabel: 'Message',
                            inputPlaceholder: 'Type your message here...',
                            inputAttributes: {
                                'aria-label': 'Type your message here'
                            },
                            confirmButtonColor: '#00A19D',
                            showCancelButton: true,
                            inputValidator: (value) => {
                                if (!value) {
                                    return 'Konten tidak boleh kosong!'
                                }
                            }
                        })
                        return text
                    }


                    async function getReasonDelete() {
                        const {
                            value: text
                        } = await Swal.fire({
                            title: 'Masukkan Alasan',
                            input: 'textarea',
                            // inputLabel: 'Message',
                            inputPlaceholder: 'Type your reason here...',
                            inputAttributes: {
                                'aria-label': 'Type your reason here'
                            },
                            confirmButtonColor: '#00A19D',
                            showCancelButton: true,
                            inputValidator: (value) => {
                                if (!value) {
                                    return 'Konten tidak boleh kosong!'
                                }
                            }
                        })
                        return text
                    }


                    async function getTitlePush() {
                        const {
                            value: title
                        } = await Swal.fire({
                            title: 'Masukkan Subject',
                            input: 'text',
                            inputPlaceholder: 'Masukkan Subject',
                            confirmButtonColor: '#00A19D',
                            showCancelButton: true,
                        });

                        return title;
                    }

                    function push(url, title, content, receiver) {
                        setLoading()
                        apiCall({
                            url,
                            type: 'POST',
                            data: {
                                title: title,
                                content: content,
                                receiver: receiver
                            },
                            success: function(response) {
                                setNotLoading()
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
                            },
                            error: function() {
                                setNotLoading()
                            }
                        })
                    }

                    $('#dataTable').on('click', '.push-notification', async function() {
                        // console.log($(this).data('id'));
                        let id = $(this).data('id')
                        let url = `{{ route('tickets.push', ':id') }}`;
                        url = url.replace(':id', id);
                        // console.log(url);

                        let title = await getTitlePush();
                        if (!title) return

                        let push_content = await getContentPush();
                        if (!push_content) return

                        let receiver = await selectReceiver();
                        if (!receiver) return

                        push(url, title, push_content, receiver)

                    })

                    async function deleteTicket(id) {
                        let url = `{{ route('tickets.delete', ':id') }}`;
                        url = url.replace(':id', id);

                        let push_content = await getReasonDelete();
                        if (!push_content) return

                        setLoading()
                        apiCall({
                            url,
                            type: 'DELETE',
                            data: {
                                reason: push_content,
                            },
                            success: function(response) {
                                setNotLoading()
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
                            },
                            error: function() {
                                setNotLoading()
                            }
                        })
                    }
                });
            </script>
        @endonce
    @endpush
@endsection
@section('modal')
    <div id="filter" tabindex="-1"
        class="fixed left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto top-10 md:inset-0 h-modal md:h-full">
        <div class="relative w-full h-full max-w-xl md:h-auto inset-y-4">
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
                    <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                        <div class="space-y-2">
                            <div class="flex flex-col w-full space-y-1">
                                <label for="" class="text-gray-600">Nomor Ticket:</label>
                                <input type="text" id="nomor_ticket"
                                    class="w-full p-2 py-2 bg-white border-1.5 border-yellow-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                                            focus:outline-none focus:ring-1 focus:ring-yellow-0
                                            disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none"
                                    placeholder="Nomor Ticket">
                            </div>
                            <div class="flex flex-col w-full space-y-1">
                                <label for="" class="text-gray-600">Prioritas:</label>
                                <select type="text" id="priority" class="w-full">
                                    <option value="">-- Pilih Prioritas --</option>
                                    <option value="Low">Low</option>
                                    <option value="Medium">Medium</option>
                                    <option value="High">High</option>
                                </select>
                            </div>
                            <div class="flex flex-col w-full space-y-1">
                                <label for="" class="text-gray-600">Jenis:</label>
                                <select type="text" id="type">
                                    <option value="">-- Pilih Jenis --</option>
                                    @foreach ($data_view['types'] as $types)
                                        <option value="{{ $types }}"
                                            @if (old('type') == $types) {{ 'selected' }} @endif>
                                            {{ $types }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex flex-col w-full space-y-1">
                                <label for="" class="text-gray-600">Department:</label>
                                <select type="text" id="department">
                                    <option value="">-- Pilih Department --</option>
                                    @foreach ($data_view['departments'] as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex flex-col w-full space-y-1">
                                <label for="" class="text-gray-600">Service:</label>
                                <select type="text" id="service">
                                    <option value="">-- Pilih Service --</option>
                                    @foreach ($data_view['services'] as $services)
                                        <option value="{{ $services->id }}">{{ $services->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex flex-col w-full space-y-1">
                                <label for="subject" class="text-gray-600">Subject:</label>
                                <input type="text" id="subject"
                                    class="w-full p-2 py-2 bg-white border-1.5 border-yellow-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                                            focus:outline-none focus:ring-1 focus:ring-yellow-0
                                            disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none"
                                    placeholder="Subject">
                            </div>
                            <div class="flex flex-col w-full space-y-1">
                                <label for="event_location_id" class="text-gray-600">Lokasi Kejadian:</label>
                                <select type="text" id="event_location_id">
                                    <option value="">-- Pilih Lokasi --</option>
                                    @foreach ($data_view['locations'] as $locations)
                                        <option value="{{ $locations->id }}">{{ $locations->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex flex-col w-full space-y-1">
                                <label for="update_type" class="text-gray-600">Progress Terakhir:</label>
                                <select type="text" id="update_type">
                                    <option value="">-- Pilih Progress --</option>
                                    @foreach ($data_view['update_types'] as $update_types)
                                        <option value="{{ $update_types }}">{{ $update_types }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex flex-col w-full space-y-1">
                                <label for="" class="text-gray-600">Penginput Update:</label>
                                <select type="text" id="progress_inputer">
                                    <option value="">-- Pilih Penginput --</option>
                                    @foreach ($data_view['users'] as $engineers)
                                        <option value="{{ $engineers->id }}">{{ $engineers->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex flex-col w-full space-y-1">
                                <label for="" class="text-gray-600">Tanggal Open:</label>
                                <div class="relative flex justify-end w-full">
                                    <input datepicker datepicker-autohide type="text" id="created_date"
                                        class="w-full pl-2 pr-9 py-2 bg-white border-1.5 border-yellow-0 rounded-xl text-sm shadow-sm placeholder-slate-400
      focus:outline-none focus:ring-1 focus:ring-yellow-0
      disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none
      invalid:border-pink-500 invalid:text-pink-600
      focus:invalid:border-pink-500 focus:invalid:ring-pink-500"
                                        placeholder="Tanggal">
                                    <i
                                        class="fas fa-calendar-days h-6 w-6 absolute my-2.5 mr-2 pointer-events-none text-yellow-0"></i>
                                </div>
                            </div>
                            <div class="flex flex-col w-full space-y-1">
                                <label for="" class="text-gray-600">Tanggal Terakhir Update:</label>
                                <div class="relative flex justify-end w-full">
                                    <input datepicker datepicker-autohide type="text" id="last_updated_date"
                                        class="w-full pl-2 pr-9 py-2 bg-white border-1.5 border-yellow-0 rounded-xl text-sm shadow-sm placeholder-slate-400
      focus:outline-none focus:ring-1 focus:ring-yellow-0
      disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none
      invalid:border-pink-500 invalid:text-pink-600
      focus:invalid:border-pink-500 focus:invalid:ring-pink-500"
                                        placeholder="Tanggal">
                                    <i
                                        class="fas fa-calendar-days h-6 w-6 absolute my-2.5 mr-2 pointer-events-none text-yellow-0"></i>
                                </div>
                            </div>
                            <div class="flex flex-col w-full space-y-1">
                                <label for="" class="text-gray-600">Nama Pembuat:</label>
                                <select type="text" id="creator">
                                    <img src="/icons/search.svg" class="absolute w-10 mr-2" alt="Search Icon" />
                                    <option value="">-- Pilih Nama Pembuat --</option>
                                    @foreach ($data_view['users'] as $users)
                                        <option value="{{ $users->id }}">{{ $users->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex flex-col w-full space-y-1">
                                <label for="" class="text-gray-600">Sumber Info:</label>
                                <select type="text" id="source_info_trouble" class="">
                                    <option value="">-- Pilih Sumber --</option>
                                    @foreach ($data_view['sources_info_troubles'] as $sources_info_troubles)
                                        <option value="{{ $sources_info_troubles }}"
                                            @if (old('source_info_trouble') == $sources_info_troubles) {{ 'selected' }} @endif>
                                            {{ $sources_info_troubles }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex flex-col w-full space-y-1">
                                <label for="" class="text-gray-600">Waktu Diselesaikan:</label>
                                <div class="relative flex justify-end w-full">
                                    <input datepicker datepicker-autohide type="text" id="technical_closed_date"
                                        class="w-full pl-2 pr-9 py-2 bg-white border-1.5 border-yellow-0 rounded-xl text-sm shadow-sm placeholder-slate-400
      focus:outline-none focus:ring-1 focus:ring-yellow-0
      disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none
      invalid:border-pink-500 invalid:text-pink-600
      focus:invalid:border-pink-500 focus:invalid:ring-pink-500"
                                        placeholder="Waktu Diselesaikan:">
                                    <i
                                        class="fas fa-calendar-days h-6 w-6 absolute my-2.5 mr-2 pointer-events-none text-yellow-0"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button data-modal-toggle="filter" type="button"
                        class="filter-apply text-white bg-tosca-0 hover:bg-tosca-1 focus:bg-tosca-1 active:bg-tosca-2 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                        Filter
                    </button>
                    <button data-modal-toggle="filter" type="button"
                        class="text-gray-500 clear bg-white hover:bg-gray-100 focus:outline-none rounded-xl border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">
                        Clear
                    </button>
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
                    <form action="{{ route('tickets.export') }}" class="form-filter">
                        <div class="flex flex-col space-y-6">

                            <div class="flex justify-center w-full">
                                <div class="flex items-center justify-center w-2/4">
                                    <div class="flex flex-col items-center justify-center w-full space-y-2">
                                        <select type="text" id="export_status">
                                            <option value="" disabled>-- Pilih Status Ticket --</option>
                                            <option value="all" selected>Semua Ticket</option>
                                            <option value="tab">Tab Sekarang</option>
                                        </select>
                                        <select name="format" type="text" id="format">
                                            <option value="" disabled>-- Pilih Format --</option>
                                            <option value="xlsx" selected>Excel</option>
                                            <option value="csv">Csv</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-row items-center justify-center space-x-2">
                                <button data-modal-toggle="modal-excel" type="submit"
                                    class="text-white bg-tosca-0 hover:bg-tosca-1 focus:bg-tosca-1 active:bg-tosca-2 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                                    Download
                                </button>
                                <a data-modal-toggle="modal-excel"
                                    class="text-gray-500 cursor-pointer bg-white hover:bg-gray-100 focus:outline-none rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
