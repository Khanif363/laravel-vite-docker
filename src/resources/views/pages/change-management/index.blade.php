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

            textarea.select2-search__field {
                resize: none;
            }
        </style>
    @endpush
    <section>
        {{-- <div class="my-4 text-start md:text-end">
            <span class="text-base font-medium text-gray-600">Department IT</span>
        </div> --}}
        <div class="flex justify-between">
            <div class="flex justify-center text-left flex-nowrap">
                @if (!in_array($permission['role'], ['Manager', 'General Manager']))
                <a data-status="draft"
                    class="inline-block status cursor-pointer px-6 py-2.5 mr-2 font-semibold text-sm leading-none uppercase rounded-xl focus:shadow-lg focus:outline-none focus:ring-0 active:shadow-lg transition duration-150 ease-in-out bg-yellow-0 text-white hover:bg-yellow-1 focus:bg-yellow-1 active:bg-yellow-2">Draft</a>
                @endif
                <a data-status="completed"
                    class="inline-block status cursor-pointer px-6 py-2.5 mr-2 font-medium text-sm leading-none uppercase rounded-xl focus:shadow-lg focus:outline-none focus:ring-0 active:shadow-lg transition duration-150 ease-in-out bg-grey-0 text-gray-500 hover:bg-grey-1 focus:bg-grey-1 active:bg-grey-2">Completed</a>
                <a data-status="verification"
                    class="inline-block status cursor-pointer px-6 py-2.5 mr-2 font-medium text-sm leading-none uppercase rounded-xl focus:shadow-lg focus:outline-none focus:ring-0 active:shadow-lg transition duration-150 ease-in-out bg-grey-0 text-gray-500 hover:bg-grey-1 focus:bg-grey-1 active:bg-grey-2">Verification</a>
                <a data-status="approval"
                    class="inline-block status cursor-pointer px-6 py-2.5 font-medium text-sm leading-none uppercase rounded-xl focus:shadow-lg focus:outline-none focus:ring-0 active:shadow-lg transition duration-150 ease-in-out bg-grey-0 text-gray-500 hover:bg-grey-1 focus:bg-grey-1 active:bg-grey-2">Approval</a>
            </div>

            <div class="hidden md:flex">
                @if ($permission['role'] === 'Admin' || isset($access_with_department['Create Changes']))
                    <a href="{{ route('change-managements.create') }}"
                        class="inline-block px-4 py-2 text-sm font-medium leading-tight capitalize transition duration-150 ease-in-out border border-yellow-0 text-yellow-0 rounded-xl hover:bg-yellow-0 hover:text-white focus:outline-none focus:ring-0">
                        <span class="mr-1"><i class="fas fa-plus"></i></span>
                        <span>Create Changes</span>
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

        <div class="absolute z-10 bottom-3 right-3 sm:hidden">
            <button type="button" data-modal-toggle="modal-excel"
                class="flex items-center justify-center w-10 h-10 text-sm font-medium leading-tight text-white capitalize transition duration-150 ease-in-out border-2 rounded-full shadow-md hover:border-red-0 hover:bg-white hover:text-red-0 hover:shadow-lg bg-red-0 focus:outline-none focus:ring-0">
                <span class="m-auto rounded-full"><i class="fas fa-download"></i></span>
            </button>
            <button type="button" data-modal-toggle="filter"
                class="flex items-center justify-center w-10 h-10 text-sm font-medium leading-tight text-white capitalize transition duration-150 ease-in-out border-2 rounded-full shadow-md hover:border-red-0 hover:bg-white hover:text-red-0 hover:shadow-lg bg-red-0 focus:outline-none focus:ring-0">
                <i class="fas fa-filter"></i>
            </button>
        </div>
    </section>

    <section class="w-full h-full mt-4 mb-10">
        <table class="w-full text-sm text-center text-gray-600" id="dataTable">
            <thead class="font-medium text-white capitalize text-md bg-blue-0">
                <tr>
                    <th class="px-10 py-6 text-center whitespace-nowrap">
                        Data Changes
                    </th>
                    {{-- <th class="px-10 py-6 text-center whitespace-nowrap">
                        Deskripsi
                    </th> --}}
                    <th class="px-10 py-6 text-center whitespace-nowrap">
                        Waktu/Tanggal Aksi
                    </th>
                    <th class="px-10 py-6 text-center whitespace-nowrap">
                        Progress Terakhir
                    </th>
                    <th class="px-10 py-6 text-center whitespace-nowrap">
                        Status
                    </th>
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
                $(document).ready(function() {
                    let filter = [];
                    let on_status = 'draft';

                    const input_filter = {
                        'nomor_changes': {
                            'type': 'input',
                            'trim': true,
                        },
                        'title': {
                            'type': 'input',
                            'trim': true,
                        },
                        'type': {
                            'type': 'select',
                            'trim': false,
                        },
                        'priority': {
                            'type': 'select',
                            'trim': false,
                        },
                        'datetime_action': {
                            'type': 'input',
                            'trim': false,
                        },
                        'pic_telkomsat': {
                            'type': 'input',
                            'trim': true,
                        },
                        'pic_nontelkomsat': {
                            'type': 'input',
                            'trim': true,
                        },
                        'agenda': {
                            'type': 'input',
                            'trim': true,
                        },
                        'approval_level1_id': {
                            'type': 'select',
                            'trim': false,
                        },
                        'approval_level2_id': {
                            'type': 'select',
                            'trim': false,
                        },
                        'status_approval1': {
                            'type': 'select',
                            'trim': false,
                        },
                        'status_approval2': {
                            'type': 'select',
                            'trim': false,
                        },
                        'last_updated_date': {
                            'type': 'input',
                            'trim': false,
                        },
                        'closed_date': {
                            'type': 'input',
                            'trim': false,
                        },
                        'location_id': {
                            'type': 'select',
                            'trim': false,
                        },
                        'creator_id': {
                            'type': 'select',
                            'trim': false,
                        },
                        'created_date': {
                            'type': 'input',
                            'trim': false,
                        },
                    }

                    let change_table = $('#dataTable')
                        .DataTable({
                            searching: false,
                            processing: true,
                            orderable: true,
                            serverSide: true,
                            responsive: true,
                            // stateSave: false,
                            scrollY: true,
                            scrollX: true,
                            autoWidth: false,
                            order: [0, 'desc'],
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
                                url: '{{ route('change-managements.index') }}',
                                data: function(d) {
                                    d.request = {
                                        'nomor_changes': filter[0],
                                        'title': filter[1],
                                        'type': filter[2],
                                        'priority': filter[3],
                                        'datetime_action': filter[4],
                                        'pic_telkomsat': filter[5],
                                        'pic_nontelkomsat': filter[6],
                                        'agenda': filter[7],
                                        'approval_level1_id': filter[8],
                                        'approval_level2_id': filter[9],
                                        'status_approval1': filter[10],
                                        'status_approval2': filter[11],
                                        'last_updated_date': filter[12],
                                        'closed_date': filter[13],
                                        'location_id': filter[14],
                                        'creator_id': filter[15],
                                        'created_date': filter[16],
                                        'status': on_status
                                    };
                                },
                            },
                            columns: [{
                                    data: 'id',
                                    // render: function(data, type, full) {
                                    //     let nomor_ticket =
                                    //         `<span class="text-lg font-semibold ${full.status === 'Open' ? 'text-nomor-1' : 'text-nomor-4'}">${full.nomor_changes}</span>`
                                    //     let view =
                                    //         `<div class="text-left"><span class="text-base">${nomor_ticket}</span><br>
                                    //     <span class="text-base font-semibold">${full.title}</span><br>
                                    //     <span class="pt-2 text-base font-semibold">${full.type ?? '<span class="font-bold text-red-0">-</span>'}</span><br>
                                    //     <span class="text-base">${limitText(full.agenda, 50) ?? '<span class="font-bold text-red-0">-</span>'}</span><br>
                                    //     <span class="text-base">${full.datetime_action ?? '<span class="font-bold text-red-0">-</span>'}</span></div>`
                                    //     return view;
                                    // }
                                },
                                {
                                    data: 'datetime_action',
                                    render: function(data, type, full) {
                                        return data ?? '-'
                                    }
                                },
                                {
                                    data: 'last_progress',
                                    name: 'cmp.progress_type',
                                    render: function(data, type, full) {
                                        let view = `<span>${data ?? 'Belum ada progress'}</span>`
                                        return view;
                                    }
                                },
                                {
                                    data: 'status_approval',
                                    orderable: false,
                                },
                                {
                                    data: 'action',
                                    name: 'id',
                                    orderable: false,
                                }
                            ],
                            columnDefs: [{
                                "targets": [2],
                                "searchable": false
                            }]
                        });

                    $('.filter-apply').on('click', function() {
                        drawable();
                    });

                    function drawable() {
                        console.log(filter);
                        let num = 0;
                        let form_filter = $('form.form-filter');
                        for (const [key, {
                                trim
                            }] of Object.entries(input_filter)) {
                            console.log(num + ':' + key);
                            if (key === 'type' && $(`#${key}`).val() != '') {
                                filter[num] = $(`#${key}`).val().trim();
                                num++;
                                continue
                            } else if (key === 'type') {
                                filter[num] = []
                                num++;
                                continue
                            }
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
                            if (key == 'status') {
                                form_filter.append(
                                    `<input name="${key}" type="text" value="${on_status}" hidden>`);
                            }
                        }
                        change_table.draw();
                    }

                    load();

                    $('.clear').on('click', function() {
                        for (const [key, {
                                type
                            }] of Object.entries(input_filter)) {
                            if (type == 'select') {
                                $(`#${key}`).val(null).trigger('change');
                            } else {
                                $(`#${key}`).val(null);
                            }
                        }
                        filter.length = 0;
                        $("form.form-filter :input[name!='format'][id!='export_status']").val("").removeAttr("checked").removeAttr("selected");
                        change_table.draw();
                    });

                    $('#dataTable').on('change', '#grid-state', function() {
                        let val = this.value;
                        let status = 0;
                        let url = `{{ route('change-managements.verif', ':id') }}`;
                        let id = $(this).data('id');
                        let approval;
                        url = url.replace(':id', id);
                        if (val == 'approval1') {
                            approval = 'Approval By Manager';
                            updateVerif(url, id, approval);
                        } else if (val == 'approval2') {
                            approval = 'Approval By GM';
                            updateVerif(url, id, approval);
                        } else if (val == 'submit') {
                            submitChanges(id);
                        } else if (val == 'delete-changes') {
                            deleteChanges(id)
                        } else {
                            window.location.href = val;
                        }
                        $('select#grid-state').val('');
                    })

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

                    async function deleteChanges(id) {
                        let url = `{{ route('change-managements.delete', ':id') }}`;
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
                                        change_table.draw();
                                    })
                                }
                            },
                            error: function() {
                                setNotLoading()
                            }
                        })
                    }

                    function submitChanges(id) {
                        Swal.fire({
                            title: 'Apakah yakin ingin submit?',
                            // showDenyButton: true,
                            showCancelButton: true,
                            confirmButtonText: 'Iya',
                            // denyButtonText: `Tidak Disetujui`,
                            confirmButtonColor: '#00A19D',
                            // denyButtonColor: '#E05D5D',
                            cancelButtonColor: '#E05D5D',
                        }).then(async (result) => {
                            if (result.isConfirmed) {
                                let url = `{{ route('change-managements.update-by-column', ':id') }}`;
                                let approval;
                                url = url.replace(':id', id);
                                const key = 'is_draft';
                                const value = 0;
                                const foR = 'submit-changes';
                                setLoading()
                                apiCall({
                                    url,
                                    type: 'PUT',
                                    data: {
                                        key: key,
                                        value: value,
                                        id: id,
                                        for: foR
                                    },
                                    success: function(response) {
                                        if (response?.success) {
                                            setNotLoading()
                                            Swal.fire({
                                                icon: 'success',
                                                title: response?.message,
                                                showConfirmButton: false,
                                                timer: 1500
                                            }).then(() => {
                                                // window.location.href = response.data
                                                //     ?.route

                                                change_table.draw();
                                            })
                                        }
                                    },
                                    error: function() {
                                        setNotLoading()
                                    }
                                })
                            }
                        })
                    }

                    async function textareaSwal() {
                        const {
                            value: text
                        } = await Swal.fire({
                            input: 'textarea',
                            inputLabel: 'Message',
                            inputPlaceholder: 'Type your message here...',
                            inputAttributes: {
                                'aria-label': 'Type your message here'
                            },
                            confirmButtonColor: '#00A19D',
                            showCancelButton: true,
                            inputValidator: (value) => {
                                if (!value) {
                                    return 'Alasan tidak boleh kosong!'
                                }
                            }
                        })
                        return text
                        // return text;
                    }

                    function updateVerif(url, id, approval) {
                        let status;
                        let reject_content;

                        Swal.fire({
                            title: 'Silahkan tentukan status persetujuan!',
                            showDenyButton: true,
                            showCancelButton: true,
                            confirmButtonText: 'Setuju',
                            denyButtonText: `Tidak Setuju`,
                            confirmButtonColor: '#00A19D',
                            denyButtonColor: '#E05D5D',
                        }).then(async (result) => {
                            /* Read more about isConfirmed, isDenied below */
                            if (result.isConfirmed) {
                                status = 1;
                            } else if (result.isDenied) {
                                status = 2;
                                reject_content = await textareaSwal();
                                if (!reject_content) {
                                    return false;
                                }
                            }

                            if (result.isConfirmed || result.isDenied) {
                                setLoading()
                                apiCall({
                                    url,
                                    type: 'PUT',
                                    data: {
                                        status_approval: status,
                                        progress_type: approval,
                                        reject_content: reject_content
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
                                                // window.location.href = response.data
                                                //     ?.route
                                                change_table.draw();
                                            })
                                        }
                                    },
                                    error: function() {
                                        setNotLoading()
                                    }
                                })
                            }
                        })
                    }

                    function load() {
                        drawable();
                        loadStatus();
                    }

                    $('#export_status').change(function() {
                        loadStatus();
                    });

                    function loadStatus() {
                        let filter_status = $('#export_status').val();
                            $('form.form-filter').append(
                                `<input name="status" type="text" value="${on_status}" hidden>`);
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


                    getByStatus('{{$permission["role"] == "Manager" ? "verification" : ($permission["role"] == "General Manager" ? "approval" : "draft")}}')

                    $('a.status').click(function() {
                        let status = $(this).data('status');
                        getByStatus(status)
                    });
                });
            </script>
        @endonce
    @endpush
@endsection
@section('modal')
    <!-- Small Modal -->
    <div id="filter" tabindex="-1"
        class="fixed left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto top-10 bottom-10 md:inset-0 h-modal md:h-full">
        <div class="relative w-full h-full max-w-xl md:h-auto inset-y-4">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700 top-36">
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
                <div class="p-6 space-y-2">
                    <div class="flex flex-col w-full space-y-1">
                        <label for="title" class="text-gray-600">Title:</label>
                        <input type="text" name="title" id="title"
                            class="w-full p-2 py-2 bg-white border-1.5 border-yellow-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                                    focus:outline-none focus:ring-1 focus:ring-yellow-0
                                    disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                    </div>
                    {{-- <div class="grid grid-cols-1 gap-y-8 md:grid-cols-2 md:gap-x-8"> --}}
                    <div class="grid grid-cols-1 gap-y-2 md:grid-cols-2 md:gap-x-8">
                        <div class="flex flex-col w-full space-y-1">
                            <label for="nomor_changes" class="text-gray-600">Nomor Changes:</label>
                            <input type="text" name="nomor_changes" id="nomor_changes"
                                class="w-full p-2 py-2 bg-white border-1.5 border-yellow-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                                            focus:outline-none focus:ring-1 focus:ring-yellow-0
                                            disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                        </div>
                        <div class="flex flex-col w-full space-y-1">
                            <label for="location_id" class="text-gray-600">Lokasi:</label>
                            <select name="location_id" id="location_id" type="text" class="w-full">
                                <option value="">-- Pilih Lokasi --</option>
                                @foreach ($data_view['locations'] as $location)
                                    <option value="{{ $location->id }}"
                                        @if (old('type') == $location->id) {{ 'selected' }} @endif>
                                        {{ $location->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex flex-col w-full space-y-1">
                            <label for="creator_id" class="text-gray-600">Pembuat:</label>
                            <select name="creator_id" id="creator_id" type="text" class="w-full">
                                <option value="">-- Pilih Nama --</option>
                                @foreach ($data_view['users'] as $user_cr)
                                    <option value="{{ $user_cr->id }}"
                                        @if (old('type') == $user_cr->id) {{ 'selected' }} @endif>
                                        {{ $user_cr->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex flex-col w-full space-y-1">
                            <label for="priority" class="text-gray-600">Prioritas:</label>
                            <select name="priority" id="priority" type="text" class="w-full">
                                <option value="">-- Pilih Prioritas --</option>
                                @foreach ($data_view['priorities'] as $priority)
                                    <option value="{{ $priority }}"
                                        @if (old('type') == $priority) {{ 'selected' }} @endif>
                                        {{ $priority }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex flex-col w-full space-y-1">
                            <label for="datetime_action" class="text-gray-600">Tanggal Eksekusi:</label>
                            <div class="relative flex justify-end w-full">
                                <input datepicker datepicker-autohide type="text" id="datetime_action"
                                    class="w-full pl-2 pr-9 py-2 bg-white border-1.5 border-yellow-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                                        focus:outline-none focus:ring-1 focus:ring-yellow-0
                                        disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none
                                        invalid:border-pink-500 invalid:text-pink-600
                                        focus:invalid:border-pink-500 focus:invalid:ring-pink-500">
                                <i
                                    class="fas fa-calendar-days h-6 w-6 absolute my-2.5 mr-2 pointer-events-none text-yellow-0"></i>
                            </div>
                        </div>
                        <div class="flex flex-col w-full space-y-1">
                            <label for="last_updated_date" class="text-gray-600">Tanggal Terakhir Update:</label>
                            <div class="relative flex justify-end w-full">
                                <input datepicker datepicker-autohide type="text" id="last_updated_date"
                                    class="w-full pl-2 pr-9 py-2 bg-white border-1.5 border-yellow-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                                        focus:outline-none focus:ring-1 focus:ring-yellow-0
                                        disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none
                                        invalid:border-pink-500 invalid:text-pink-600
                                        focus:invalid:border-pink-500 focus:invalid:ring-pink-500">
                                <i
                                    class="fas fa-calendar-days h-6 w-6 absolute my-2.5 mr-2 pointer-events-none text-yellow-0"></i>
                            </div>
                        </div>
                        <div class="flex flex-col w-full space-y-1">
                            <label for="created_date" class="text-gray-600">Tanggal Dibuat:</label>
                            <div class="relative flex justify-end w-full">
                                <input datepicker datepicker-autohide type="text" id="created_date"
                                    class="w-full pl-2 pr-9 py-2 bg-white border-1.5 border-yellow-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                                        focus:outline-none focus:ring-1 focus:ring-yellow-0
                                        disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none
                                        invalid:border-pink-500 invalid:text-pink-600
                                        focus:invalid:border-pink-500 focus:invalid:ring-pink-500">
                                <i
                                    class="fas fa-calendar-days h-6 w-6 absolute my-2.5 mr-2 pointer-events-none text-yellow-0"></i>
                            </div>
                        </div>
                        <div class="flex flex-col w-full space-y-1">
                            <label for="closed_date" class="text-gray-600">Tanggal Closed:</label>
                            <div class="relative flex justify-end w-full">
                                <input datepicker datepicker-autohide type="text" id="closed_date"
                                    class="w-full pl-2 pr-9 py-2 bg-white border-1.5 border-yellow-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                                        focus:outline-none focus:ring-1 focus:ring-yellow-0
                                        disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none
                                        invalid:border-pink-500 invalid:text-pink-600
                                        focus:invalid:border-pink-500 focus:invalid:ring-pink-500">
                                <i
                                    class="fas fa-calendar-days h-6 w-6 absolute my-2.5 mr-2 pointer-events-none text-yellow-0"></i>
                            </div>
                        </div>
                        <div class="flex flex-col w-full space-y-1">
                            <label for="approval_level1_id" class="text-gray-600">Manager:</label>
                            <select name="approval_level1_id" id="approval_level1_id" type="text" class="w-full">
                                <option value="">-- Pilih Nama --</option>
                                @foreach ($data_view['managers'] as $user_cr)
                                    <option value="{{ $user_cr->id }}"
                                        @if (old('type') == $user_cr->id) {{ 'selected' }} @endif>
                                        {{ $user_cr->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex flex-col w-full space-y-1">
                            <label for="approval_level2_id" class="text-gray-600">General Manager:</label>
                            <select name="approval_level2_id" id="approval_level2_id" type="text" class="w-full">
                                <option value="">-- Pilih Nama --</option>
                                @foreach ($data_view['general_managers'] as $user_cr)
                                    <option value="{{ $user_cr->id }}"
                                        @if (old('type') == $user_cr->id) {{ 'selected' }} @endif>
                                        {{ $user_cr->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-y-2 md:grid-cols-2 md:gap-x-8">
                        <div class="flex flex-col w-full row-span-2 space-y-1">
                            <label for="type" class="text-gray-600">Tipe Changes:</label>
                            <select name="type[]" id="type" type="text" class="w-full" multiple="multiple">
                                <option>-- Pilih Tipe --</option>
                                @foreach ($data_view['cr_types'] as $cr_type)
                                    <option value="{{ $cr_type }}"
                                        @if (old('type') == $cr_type) {{ 'selected' }} @endif>
                                        {{ $cr_type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex flex-col w-full space-y-1">
                            <label for="status_approval1" class="text-gray-600">Disetujui Manager?</label>
                            <select type="text" id="status_approval1" name="status_approval1">
                                <option value="">-- Pilih Status --</option>
                                <option value="1">Sudah</option>
                                <option value="0">Belum</option>
                                <option value="2">Tidak</option>
                            </select>
                        </div>
                        <div class="flex flex-col w-full space-y-1">
                            <label for="status_approval2" class="text-gray-600">Disetujui GM?</label>
                            <select type="text" id="status_approval2" name="status_approval2">
                                <option value="">-- Pilih Status --</option>
                                <option value="1">Sudah</option>
                                <option value="0">Belum</option>
                                <option value="2">Tidak</option>
                            </select>
                        </div>
                        <div class="flex flex-col w-full space-y-1">
                            <label for="pic_telkomsat" class="text-gray-600">PIC Telkomsat:</label>

                            <textarea name="pic_telkomsat" id="pic_telkomsat"
                                class="w-full bg-white border-1.5 border-yellow-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                                focus:outline-none focus:ring-1 focus:ring-yellow-0
                                disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none"
                                rows="3"></textarea>
                        </div>
                        <div class="flex flex-col w-full space-y-1">
                            <label for="pic_nontelkomsat" class="text-gray-600">PIC Non Telkomsat:</label>
                            <textarea name="pic_nontelkomsat" id="pic_nontelkomsat"
                                class="w-full bg-white border-1.5 border-yellow-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                                focus:outline-none focus:ring-1 focus:ring-yellow-0
                                disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none"
                                rows="3"></textarea>
                        </div>
                    </div>

                    <div class="flex flex-col w-full space-y-1">
                        <label for="agenda" class="text-gray-600">Agenda:</label>
                        <textarea name="agenda" id="agenda"
                            class="w-full bg-white border-1.5 border-yellow-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                                    focus:outline-none focus:ring-1 focus:ring-yellow-0
                                    disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none"
                            rows="3"></textarea>
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
                    <form action="{{ route('change-managements.export', 'excel') }}" class="form-filter">
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
                                    class="cursor-pointer text-gray-500 bg-white hover:bg-gray-100 focus:outline-none rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
