@extends('layouts.master')

@section('content')
    <section class="px-5">
        <section class="flex justify-center w-full">
            {{-- <div class="my-4">
            <span class="text-xl font-semibold">Total Ticket Open & Closed Perdepartment</span>
        </div> --}}

            @php
                $status = [
                    [
                        'nama' => 'Open',
                        'value' => '0',
                        'color' => 'red-0',
                    ],
                    [
                        'nama' => 'Pending',
                        'value' => '0',
                        'color' => 'yellow-0',
                    ],
                    [
                        'nama' => 'Closed',
                        'value' => '0',
                        'color' => 'green-0',
                    ],
                ];

                // departments_core = ['Cyber Security', 'IP Core Network', 'IT Integration'];
                // $department_mttr = ['Cyber Security', 'IP Core Network', 'IT Integration', 'Service Desk'];
            @endphp


            <div class="flex flex-col w-full space-y-6 text-gray-700">
                <div class="grid w-full grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($data_view['departments_core'] as $departments)
                        <div id="card-total-{{ str_replace(' ', '-', strtolower(str_replace('IT & Cyber Security', 'Cyber Security', $departments->name))) }}"
                            class="flex justify-center w-full">
                            <div
                                class="w-full block rounded-2xl shadow-lg bg-white text-center border-[1px] border-[#AAAAAA] hover:shadow-2xl hover:scale-105 transition duration-150 ease-in-out">
                                <div class="p-6">
                                    <h5 class="px-4 mb-2 text-xl font-medium text-gray-800"><span class="block">Total Ticket
                                            Departement</span>
                                        {{ $departments->name }}
                                    </h5>
                                    <div class="p-1 mt-14">
                                        <ol class="flex items-center justify-center h-full list-reset">
                                            @foreach ($status as $val)
                                                <li>
                                                    <a href="{{ route('tickets.index', ['status' => strtolower($val['nama']), 'department' => str_replace(' ', '-', strtolower($departments->id))]) }}"
                                                        class="mx-2 p-2 bg-{{ $val['color'] }} w-[101px] h-full rounded-lg flex items-center justify-center shadow-md hover:shadow-2xl hover:scale-105 transition duration-150 ease-in-out">
                                                        <div
                                                            class="text-center {{ $val['color'] == 'grey-0' ? 'text-gray-500' : 'text-white' }}">
                                                            <span class="block text-[40px] font-bold leading-tight"
                                                                data-type="value-{{ $val['nama'] }}">{{ $val['value'] }}</span>
                                                            <span
                                                                class="block text-[20px] font-semibold leading-tight">{{ $val['nama'] }}</span>
                                                        </div>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="grid w-full grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($data_view['departments'] as $departments)
                        <div id="card-mttr-{{ str_replace(' ', '-', strtolower(str_replace('IT & Cyber Security', 'Cyber Security', $departments->name))) }}"
                            class="flex justify-center w-full">
                            <div
                                class="w-full block rounded-2xl shadow-lg bg-white text-center border-[1px] border-[#AAAAAA] hover:shadow-2xl hover:scale-105 transition duration-150 ease-in-out">
                                <div class="p-6">
                                    <div class="min-h-[80px]">
                                        <h5 class="px-4 mb-2 text-xl font-medium text-gray-800"><span
                                                class="block">MTTRecovery</span>
                                            {{ $departments->name }}
                                        </h5>
                                    </div>
                                    <canvas
                                        id="chart-mttr-month-{{ str_replace(' ', '-', strtolower(str_replace('IT & Cyber Security', 'Cyber Security', $departments->name))) }}"></canvas>
                                    <div id="current_mttr_month{{ str_replace(' ', '-', strtolower(str_replace('IT & Cyber Security', 'Cyber Security', $departments->name))) }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if (
                            $departments->name === 'IP Core Network' &&
                                ($permission['role'] === 'Admin' || in_array(auth()->user()->department_id, [2, 4])))
                            <div class="flex justify-center w-full">
                                <div
                                    class="w-full block rounded-2xl shadow-lg bg-white text-center border-[1px] border-[#AAAAAA] hover:shadow-2xl hover:scale-105 transition duration-150 ease-in-out">
                                    <div class="p-6">
                                        <div class="min-h-[80px]">
                                            <h5 class="px-4 mb-2 text-xl font-medium text-gray-800"><span
                                                    class="block">MTTRecovery</span>
                                                {{ str_replace('Cyber Security', 'IT & Cyber Security', $departments->name) }}
                                            </h5>
                                        </div>
                                        <canvas
                                            id="chart-mttr-week-{{ str_replace(' ', '-', strtolower($departments->name)) }}"></canvas>
                                        <div id="current_mttr_week{{ str_replace(' ', '-', strtolower($departments->name)) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                    <div id="card-mtta" class="flex justify-center w-full">
                        <div
                            class="w-full block rounded-2xl shadow-lg bg-white text-center border-[1px] border-[#AAAAAA] hover:shadow-2xl hover:scale-105 transition duration-150 ease-in-out">
                            <div class="p-6">
                                <div class="min-h-[80px]">
                                    <h5 class="px-4 mb-2 text-xl font-medium text-gray-800">Time To Respond
                                    </h5>
                                </div>
                                <canvas id="chart-mtta-month"></canvas>
                                <div id="current_mtta_month"></div>
                            </div>
                        </div>
                    </div>
                    @if ($permission['role'] === 'Admin' || in_array(auth()->user()->department_id, [2, 4]))
                        <div class="flex justify-center w-full">
                            <div
                                class="w-full block rounded-2xl shadow-lg bg-white text-center border-[1px] border-[#AAAAAA] hover:shadow-2xl hover:scale-105 transition duration-150 ease-in-out">
                                <div class="p-6">
                                    <div class="min-h-[80px]">
                                        <h5 class="px-4 mb-2 text-xl font-medium text-gray-800">Time To Respond
                                        </h5>
                                    </div>
                                    <canvas id="chart-mtta-week"></canvas>
                                    <div id="current_mtta_week"></div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>

        <section class="mt-6 flex justify-center max-w-[750px]">
            <div class="grid w-full grid-cols-1 lg:gap-x-6 gap-y-6 lg:grid-cols-3">
                <div class="flex justify-center w-full col-span-2">
                    <div
                        class="w-full rounded-2xl shadow-lg bg-white text-center border-[1px] border-[#AAAAAA] hover:shadow-2xl hover:scale-105 transition duration-150 ease-in-out">
                        <div class="px-6 py-10">
                            <div class="text-center">
                                <h5 class="text-2xl font-bold text-gray-800">TOP 3
                                </h5>
                                <p class="text-gray-500">Total Tiket Terbanyak Berdasarkan Layanan</p>
                            </div>
                            <div id="card-top-3" class="flex flex-col mt-5 space-y-3"></div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-center">
                    <div class="grid grid-cols-2 md:grid-cols-1 gap-y-8 gap-x-2">
                        <div
                            class="block rounded-2xl shadow-lg bg-white max-w-sm text-center border-[1px] border-[#AAAAAA] hover:shadow-2xl hover:scale-105 transition duration-150 ease-in-out">
                            <div class="p-6">
                                <h5 class="text-xl text-gray-600">Total Open Ticket lebih dari 3 hari</h5>
                                <ol class="flex items-center justify-center h-full mt-3 list-reset">
                                    <li class="flex items-center justify-center w-32 h-20 shadow-md bg-red-0 rounded-3xl">
                                        <div id="card-total-open-ticket-more-than-3-days" class="text-center text-white">
                                            <span class="block text-[35px] leading-none" data-type="value-total">0</span>
                                            <span class="block text-[20px] leading-none">Open</span>
                                        </div>
                                    </li>
                                </ol>
                            </div>
                        </div>
                        <div
                            class="block rounded-2xl shadow-lg bg-white max-w-sm text-center border-[1px] border-[#AAAAAA] hover:shadow-2xl hover:scale-105 transition duration-150 ease-in-out">
                            <div class="p-6">
                                <h5 class="text-xl text-gray-600">Total Skor Kepuasan Pelanggan</h5>
                                <div id="card-total-skor-kepuasan-pelanggan"
                                    class="flex items-center justify-center h-full mt-3">
                                    <div
                                        class="flex items-center justify-center w-32 h-20 shadow-md bg-yellow-0 rounded-3xl">
                                        <span class="block text-[35px] text-white leading-none" data-type="value-total">0
                                            %</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </section>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"
        integrity="sha256-+8RZJua0aEWg+QVVKg4LEzEEm/8RFez5Tb4JBNiV5xA=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            init()
        })

        function init() {
            initTotal()
            initChartMTTR()
            initChartMTTA()
            initTopTicket()
            initTotalOpenTicketMoreThan3Days()
            initTotalSkorKepuasanPelanggan()
        }

        async function initTotal() {
            const listDepartment = ['Cyber Security', 'IP Core Network', 'IT Integration']

            for (const department of listDepartment) {
                try {
                    const data = await apiCall({
                        url: '{{ route('tickets.total') }}',
                        data: {
                            department
                        },
                        success: function(response) {
                            // Do nothing
                        },
                        error: function(response) {
                            Swal.fire({
                                icon: 'error',
                                title: response?.responseJSON?.message,
                                showConfirmButton: false,
                                timer: 1500
                            })
                        },
                    })

                    const departmentSnakeCase = department.toLowerCase().replaceAll(' ', '-')
                    const cardDepartment = $(`#card-total-${departmentSnakeCase}`)

                    cardDepartment.find('[data-type="value-Open"]').html(data?.data?.open || 0)
                    cardDepartment.find('[data-type="value-Pending"]').html(data?.data?.pending || 0)
                    cardDepartment.find('[data-type="value-Closed"]').html(data?.data?.closed || 0)
                } catch (err) {
                    // TODO: Handle error
                }
            }
        }

        async function initChartMTTR() {
            const listDepartment = ['Cyber Security', 'IP Core Network', 'IT Integration', 'Service Desk']

            for (const department of listDepartment) {
                const departmentSnakeCase = department.toLowerCase().replaceAll(' ', '-')
                // const chartDepartment = `chart-mttr-month-${departmentSnakeCase}`
                let data = {}

                try {
                    const resData = await apiCall({
                        url: '{{ route('tickets.mttr') }}',
                        data: {
                            department
                        },
                        success: function(response) {
                            // Do nothing
                        },
                        error: function(response) {
                            Swal.fire({
                                icon: 'error',
                                title: response?.responseJSON?.message,
                                showConfirmButton: false,
                                timer: 1500
                            })
                        },
                    })
                    // console.log(resData?.data?.mttr_month);

                    let dataMTTA = {
                        'mttr_month': {
                            'data': resData?.data?.mttr_month,
                            'XTitle': 'Month',
                            'YTitle': 'Recovery',
                            'id1': `chart-mttr-month-${departmentSnakeCase}`,
                            'id2': `current_mttr_month${departmentSnakeCase}`
                        },
                    }

                    if (department === 'IP Core Network') {
                        dataMTTA = {
                            ...dataMTTA,
                            'mttr_week': {
                                'data': resData?.data?.mttr_week,
                                'XTitle': 'Week',
                                'YTitle': 'Recovery',
                                'id1': `chart-mttr-week-${departmentSnakeCase}`,
                                'id2': `current_mttr_week${departmentSnakeCase}`
                            },
                        }
                    }

                    for (let key in dataMTTA) {
                        let data = dataMTTA[key].data;
                        let xTitle = dataMTTA[key].XTitle;
                        let yTitle = dataMTTA[key].YTitle;
                        let id1 = dataMTTA[key].id1;
                        let id2 = dataMTTA[key].id2;

                        let scales = {
                            x: {
                                title: {
                                    text: xTitle
                                }
                            },
                            y: {
                                title: {
                                    text: yTitle
                                }
                            }
                        }

                        let labels = data.mttr.map(item => item?.label || '') || [];
                        let mttrData = data.mttr.map(item => parseInt(item?.data.substring(0, 2)) || 0) || [];

                        let chartData = {
                            ...data,
                            labels: labels,
                            datasets: [{
                                label: 'Mean Time',
                                data: mttrData,
                                borderColor: 'rgb(54, 162, 235)',
                            }]
                        }
                        $(`#${id2}`).text(
                            `${xTitle == 'Month' ? 'Bulan' : 'Minggu'} ini ${data?.current_mttr ? data?.current_mttr : '-'}`
                        )
                        initLineChart(id1, chartData, scales);
                    }


                } catch (err) {
                    // TODO: Handle error
                }
            }
        }

        async function initChartMTTA() {
            // const chartMTTAMonth = 'chart-mtta-month'
            // const chartMTTAWeek = 'chart-mtta-week'
            let data = {}

            try {
                const resData = await apiCall({
                    url: '{{ route('tickets.mtta') }}',
                    success: function(response) {
                        // Do nothing
                    },
                    error: function(response) {
                        Swal.fire({
                            icon: 'error',
                            title: response?.responseJSON?.message,
                            showConfirmButton: false,
                            timer: 1500
                        })
                    },
                })
                // console.log(resData);

                let dataMTTA = {
                    'mtta_month': {
                        'data': resData?.data?.mtta_month,
                        'XTitle': 'Month',
                        'YTitle': 'Response',
                        'id1': 'chart-mtta-month',
                        'id2': 'current_mtta_month'
                    },
                    'mtta_week': {
                        'data': resData?.data?.mtta_week,
                        'XTitle': 'Week',
                        'YTitle': 'Response',
                        'id1': 'chart-mtta-week',
                        'id2': 'current_mtta_week'
                    },
                }


                for (let key in dataMTTA) {
                    let data = dataMTTA[key].data;
                    let xTitle = dataMTTA[key].XTitle;
                    let yTitle = dataMTTA[key].YTitle;
                    let id1 = dataMTTA[key].id1;
                    let id2 = dataMTTA[key].id2;

                    let scales = {
                        x: {
                            title: {
                                text: xTitle
                            }
                        },
                        y: {
                            title: {
                                text: yTitle
                            }
                        }
                    }

                    let labels = data.mtta.map(item => item?.label || '') || [];
                    let mttaData = data.mtta.map(item => parseInt(item?.data.substring(0, 2)) || 0) || [];

                    let chartData = {
                        ...data,
                        labels: labels,
                        datasets: [{
                            label: 'Mean Time',
                            data: mttaData,
                            borderColor: 'rgb(54, 162, 235)',
                        }]
                    }
                    $(`#${id2}`).text(
                        `${xTitle == 'Month' ? 'Bulan' : 'Minggu'} ini ${data?.current_mtta ? data?.current_mtta : '-'}`
                    )
                    initLineChart(id1, chartData, scales);
                }

            } catch (err) {
                // TODO: Handle error
            }

        }

        async function initTopTicket() {
            const cardTop3 = $('#card-top-3').empty()

            const data = await apiCall({
                url: '{{ route('tickets.top-ticket') }}',
                success: function(response) {
                    // Do nothing
                },
                error: function(response) {
                    // TODO: Handle Error
                },
            })

            if (data?.success) {
                for (const datum of data?.data) {
                    const item = `
                        <ol class="flex items-center justify-center h-full list-reset">
                            <li class="flex items-center justify-center w-auto h-full px-8 py-2 shadow-md bg-tosca-0 md:px-16 rounded-2xl">
                                <div class="text-center">
                                    <span class="block text-[40px] text-white whitespace-nowrap leading-snug">${datum?.total || 0} Tiket</span>
                                    <span class="block text-[20px] text-white whitespace-nowrap leading-snug">${datum?.layanan || ''} </span>
                                </div>
                            </li>
                        </ol>`

                    cardTop3.append(item)
                }
            }
        }

        async function initTotalOpenTicketMoreThan3Days() {
            const cardTotalOpenTicketMoreThan3Days = $('#card-total-open-ticket-more-than-3-days')

            const data = await apiCall({
                url: '{{ route('tickets.total-open-ticket-more-than-3-days') }}',
                success: function(response) {
                    // Do nothing
                },
                error: function(response) {
                    // TODO: Handle Error
                },
            })

            if (data?.success) {
                cardTotalOpenTicketMoreThan3Days.find('[data-type="value-total"]').html(data?.data?.total || 0)
            }
        }

        async function initTotalSkorKepuasanPelanggan() {
            const cardTotalSkorKepuasanPelanggan = $('#card-total-skor-kepuasan-pelanggan')

            const data = await apiCall({
                url: '{{ route('tickets.total-skor-kepuasan-pelanggan') }}',
                success: function(response) {
                    // Do nothing
                },
                error: function(response) {
                    // TODO: Handle Error
                },
            })

            if (data?.success) {
                cardTotalSkorKepuasanPelanggan.find('[data-type="value-total"]').html(`${data?.data?.total || 0} %`)
            }
        }

        function initLineChart(element, data, dataScales, options = {}) {
            const config = {
                type: 'line',
                data: data,
                options: {
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: dataScales.x.title.text
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: dataScales.y.title.text
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },

                        tooltip: {
                            // enabled: true,
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';

                                    if (label) {
                                        const mttr = data.mttr ? data.mttr[context.dataIndex]?.data : null;
                                        const mtta = data.mtta ? data.mtta[context.dataIndex]?.data : null;

                                        if (mttr !== null) {
                                            label += `: ${mttr}`;
                                        }

                                        if (mtta !== null) {
                                            label += `: ${mtta}`;
                                        }

                                        const mttr_max_ticket_number = data.mttr ? data.mttr[context.dataIndex]
                                            ?.max_ticket_number : null;
                                        const mtta_max_ticket_number = data.mtta ? data.mtta[context.dataIndex]
                                            ?.max_ticket_number : null;

                                        if (mttr_max_ticket_number !== null) {
                                            label += `, Highest Ticket: ${mttr_max_ticket_number}`;
                                        }

                                        if (mtta_max_ticket_number !== null) {
                                            label += `, Highest Ticket: ${mtta_max_ticket_number}`;
                                        }
                                    }


                                    return label;

                                    // console.log('context');
                                },
                            }
                        }
                    }
                },
                ...options
            };

            const chartStatus = Chart.getChart(element);

            if (chartStatus !== undefined) chartStatus.destroy()

            const ctx = document.getElementById(element).getContext('2d')

            return new Chart(ctx, config)
        }
    </script>
@endpush
