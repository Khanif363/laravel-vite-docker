<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>COMIT2027</title>
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/Chart.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/icon/app.css') }}" />
    <x-head.tinymce-config />
    {{-- <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/sweetalert2@9.17.2/dist/sweetalert2.min.css"> --}}
    @stack('css')
    <style>
        html {
            height: 100%;
            width: 100%;
            /* margin-top: 0%;
            padding: 0%; */
            margin: 0px;
            padding: 0px;
            overflow-y: hidden;
            overflow-x: hidden;
        }

        /* body {
            overflow-x: hidden;
        } */

        .text-nomor-1 {
            color: #008000;
        }

        .text-nomor-2 {
            color: #FFA200;
        }

        .text-nomor-3 {
            color: #A11D33;
        }

        .text-nomor-4 {
            color: #495057;
        }

        .text-nomor-5 {
            color: #000000;
        }

        .swal2-popup button.swal2-confirm {
            border-radius: 0.5rem !important;
            font-size: 0.875rem !important;
            line-height: 1.25rem !important;
            padding: 0.625rem 1.25rem !important;
            align-items: center !important;
        }

        .swal2-popup button.swal2-cancel,
        .swal2-popup button.swal2-deny {
            border-radius: 0.5rem !important;
            font-size: 0.875rem !important;
            line-height: 1.25rem !important;
            padding: 0.625rem 1.25rem !important;
            align-items: center !important;
        }

        .swal2-popup button.swal2-confirm:focus {
            border: none;
            outline: none;
            box-shadow: none;
        }

        .swal2-popup button.swal2-cancel:focus,
        .swal2-popup button.swal2-deny:focus {
            border: none;
            outline: none;
            box-shadow: none;
        }

        [type='text']:focus,
        [type='email']:focus,
        [type='url']:focus,
        [type='password']:focus,
        [type='number']:focus,
        [type='date']:focus,
        [type='datetime-local']:focus,
        [type='month']:focus,
        [type='search']:focus,
        [type='tel']:focus,
        [type='time']:focus,
        [type='week']:focus,
        [multiple]:focus,
        textarea:focus,
        select:focus {
            outline-offset: none;
            /* --tw-ring-inset: none; */
            /* --tw-ring-offset-width: none; */
            /* --tw-ring-offset-color: none; */
            /* --tw-ring-color: none; */
            --tw-ring-offset-shadow: none;
            --tw-ring-shadow: none;
            box-shadow: none;
            border-color: none;
        }

        span.select2.select2-container.select2-container--default {
            width: 100% !important;
        }

        input:disabled,
        textarea:disabled {
            background-color: rgb(209 213 219) !important;
            border-color: #00A19D !important;
        }

        .tox-editor-header .tox-promotion,
        .tox-statusbar__text-container .tox-statusbar__branding {
            display: none
        }

        .clock-timepicker {
            width: 100%;
        }
    </style>
    <script src="{{ asset('assets/js/vendor/alpine.js') }}" defer></script>

</head>

<body class="overflow-y-hidden antialiased text-gray-900 bg-white" id="app">
    {{-- <div class="flex h-screen overflow-y-hidden bg-white"> --}}
        {{-- <div class='loading fixed inset-0 z-[100000] flex items-center justify-center bg-gray-300 bg-opacity-30 backdrop-filter backdrop-blur-[1px]'><span class='text-gray-500 fa-3x'><i class="fa-solid fa-circle-notch fa-spin"></i></span></div>     --}}
        <div class="flex h-screen overflow-y-hidden bg-white" x-data="setup()" x-init="$refs.loading.classList.add('hidden')">
        <!-- Loading screen -->
        {{-- <div x-ref="loading"
            class="fixed inset-0 z-[200] flex items-center justify-center text-white bg-black bg-opacity-50"
            style="backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px)">
            Loading...
        </div> --}}
        <div x-ref="loading" class='loading fixed inset-0 z-[100000] flex items-center justify-center bg-gray-300 bg-opacity-30 backdrop-filter backdrop-blur-[1px]'><span class='text-gray-500 fa-3x'><i class='fa fa-spinner fa-spin'></i></span></div>



        {{-- END PHP Variable --}}
        @include('partials.tooltips')
        {{-- SIDEBAR SESSION --}}
        @include('partials.sidebar')

        {{-- END SIDEBAR SESSION --}}

        <div class="flex flex-col flex-1 h-full overflow-hidden">
            {{-- NAVBAR SESSION --}}
            @include('partials.navbar')
            {{-- END NAVBAR SESSION --}}

            <!-- Main content -->
            <main class="flex-1 max-h-full p-5 overflow-hidden overflow-y-scroll bg-white">
                {{-- bg-[#F4F4F4] --}}
                <nav class="flex justify-between w-full rounded-md" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="#"
                                class="inline-flex items-center text-xl font-medium text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                                {{ $menu['utama'] }}
                            </a>
                        </li>
                        @if (!empty($menu['sub']))
                            <li>
                                <div class="flex items-center"><i
                                        class="self-center mx-2 text-gray-400 fas fa-angle-right justify-self-center"></i>
                                    <button id="dropdownDefault" data-dropdown-toggle="dropdown"
                                        data-dropdown-placement="right-start"
                                        class="text-white bg-tosca-20 hover:bg-tosca-0 focus:outline-none font-medium rounded-lg text-sm px-4 py-1.5 text-center inline-flex items-center"
                                        type="button">{{ $menu['sub'] ?? null }}
                                        @if ($menu['opsi'] ?? null)
                                            <i class="ml-2 fas fa-caret-right"></i>
                                        @endif
                                    </button>
                                    <!-- Dropdown menu -->
                                    @if ($menu['opsi'] ?? null)
                                        <div id="dropdown"
                                            class="z-10 hidden bg-white divide-y divide-gray-100 rounded shadow w-44 dark:bg-gray-700">
                                            <ul class="py-1 text-sm text-gray-700 dark:text-gray-200"
                                                aria-labelledby="dropdownDefault">
                                                {{-- @php
                                            permission_filtered = preg_grep('/^Edit\s.*/', $submenu_middleware);
                                        @endphp --}}
                                                {{-- @dd($permission_filtered) --}}
                                                @if (!empty($menu['opsi']))
                                                    @foreach ($menu['opsi'] as $opsis)
                                                        @if (($permission['role'] ?? null) == 'Admin')
                                                            <li>
                                                                <a href="{{ $opsis['link'] }}"
                                                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">{{ $opsis['nama'] }}</a>
                                                            </li>
                                                        @elseif (
                                                            $permission['role'] != null &&
                                                                (in_array($opsis['nama'], $submenu_middleware) ||
                                                                    ($opsis['nama'] === 'Create Ticket' && in_array('Create Changes', $submenu_middleware))))
                                                            <li>
                                                                <a href="{{ $opsis['link'] }}"
                                                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">{{ $opsis['nama'] }}</a>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                @endif

                                            </ul>
                                        </div>
                                    @endif
                            </li>
                        @endif
                    </ol>
                    @php
                        $date_now = Carbon\Carbon::now()
                            ->locale('id')
                            ->isoFormat('dddd, D MMMM YYYY');
                        $time_now = Carbon\Carbon::now()
                            ->locale('id')
                            ->isoFormat('hh:mm');
                        $current_user = auth()->user()->full_name;
                    @endphp
                    <ol class="items-center hidden list-reset md:flex">
                        <li class="mx-4"><i class="mr-2 text-gray-500 fas fa-calendar-days"></i><span
                                class="text-gray-500">{{ $date_now }}</span></li>
                        <li class="mx-4"><i class="mr-2 text-gray-500 fas fa-clock"></i><span
                                class="text-gray-500">{{ $time_now }}</span></li>
                        <li class="ml-4"><i class="mr-2 text-gray-500 fa-solid fa-user"></i><span
                                class="font-semibold text-gray-500">{{ $current_user }}</span></li>
                    </ol>
                </nav>

                {{-- CONTENT SESSION --}}
                <section class="mt-10">
                    @yield('content')
                    @yield('modal')


                    @if ($message = Session::get('success'))
                        <x-alert-success :message="$message" />
                    @endif

                    @if ($message = Session::get('error'))
                        <x-alert-error :message="$message" />
                    @endif
                </section>
                {{-- END CONTENT SESSION --}}
            </main>


            {{-- FOOTER SESSION --}}
            @include('partials.footer')
            {{-- END FOOTER SESSION --}}
        </div>
    </div>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/Chart.bundle.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="https://unpkg.com/flowbite@1.5.4/dist/flowbite.js"></script>
    <script src="https://unpkg.com/flowbite@1.5.5/dist/datepicker.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.19/dist/sweetalert2.all.min.js"></script>
    <script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>


    <script type="text/javascript">
        let limitSession = {!! session()->get('limitSession') !!};
        const rawCheckAlert = '{!! session()->get('last_check')
            ? session()->get('last_check')->format('Y-m-d H:i:s')
            : null !!}';
        let lastCheckAlert = rawCheckAlert ? new Date(rawCheckAlert) : null;
        const permission = {!! json_encode($permission ?? []) !!};

        $(document).ready(function() {
            globalInit()
            globalHandler()
            handler()
            $('.ckeditor').ckeditor();
        })

        function setLoading() {
            $('body').append(
                "<div class='loading fixed inset-0 z-[100000] flex items-center justify-center bg-gray-300 bg-opacity-30 backdrop-filter backdrop-blur-[1px]'><span class='text-gray-500 fa-3x'><i class='fa fa-spinner fa-spin'></i></span></div>"
            )
        }

        function setNotLoading() {
                $('body .loading').remove()
        }

        function globalInit() {
            const now = new Date()
            if (lastCheckAlert) {
                const timeoutNotUpdate = (lastCheckAlert.getTime() + (2 * 60 * 60 * 1000)) - now.getTime()
                const timeoutGangguan = (lastCheckAlert.getTime() + (2 * 60 * 60 * 1000) + 5_000) - now.getTime()
                const timeoutGamas = (lastCheckAlert.getTime() + (60 * 60 * 1000) + 10_000) - now.getTime()

                // Check Not Update
                setTimeout(() => {
                    checkAlert('not-update')
                    setInterval(() => {
                        checkAlert('not-update')
                    }, 2 * 60 * 60 * 1000)
                }, timeoutNotUpdate)

                // Check Alert Gangguan
                setTimeout(() => {
                    checkAlert('gangguan')
                    setInterval(() => {
                        checkAlert('gangguan')
                    }, 2 * 60 * 60 * 1000)
                }, timeoutGangguan)

                // Check Alert Gamas
                setTimeout(() => {
                    checkAlert('gamas')
                    setInterval(() => {
                        checkAlert('gamas')
                    }, 60 * 60 * 1000)
                }, timeoutGamas)
            } else {
                // Check Not Update
                checkAlert('not-update')
                setInterval(() => {
                    checkAlert('not-update')
                }, 2 * 60 * 60 * 1000)

                // Check Alert Gangguan
                setTimeout(() => {
                    checkAlert('gangguan')
                    setInterval(() => {
                        checkAlert('gangguan')
                    }, 2 * 60 * 60 * 1000)
                }, 5_000)

                // Check Alert Gamas
                setTimeout(() => {
                    checkAlert('gamas')
                    setInterval(() => {
                        checkAlert('gamas')
                    }, 60 * 60 * 1000)
                }, 10_000)
            }

            countDownSession()
        }

        function checkAlert(type = 'gangguan') {
            apiCall({
                url: '{{ route('tickets.alert') }}',
                data: {
                    type
                },
                success: function(response) {
                    if (response?.success && response?.data) {
                        Swal.fire({
                            icon: 'info',
                            title: response?.data,
                            showConfirmButton: false
                        })
                    }
                },
                error: function(response) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal load data!',
                        showConfirmButton: false
                    })
                }
            })
        }

        function globalHandler() {
            $('#logout').on('click', function(e) {
                e.preventDefault()

                const url = $(this).attr('href')

                Swal.fire({
                    title: 'Are you sure?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Yes, logout!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        apiCall({
                            url,
                            success: function(response) {
                                if (response?.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: response?.message,
                                        showConfirmButton: false,
                                        timer: 1500
                                    }).then(() => {
                                        window.location.href = response.data?.route
                                    })
                                }
                            }
                        })
                    }
                })
            })
        }

        async function callApiWithForm(form, options = {}) {
            const url = $(form).attr('action')
            const method = $(form).attr('method')
            const formData = new FormData(form)

            $(form).find('input[type="checkbox"]').each(function(_, element) {
                if (!$(element).data('not-find')) {
                    formData.set($(element).attr('name'), $(element).is(':checked') ? 1 : 0)
                }
            })

            return apiCall({
                url,
                type: method,
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                beforeSend: function() {
                    $(form).find('.error').hide()
                    $(form).find('button[type="submit"]').attr('disabled', true)
                },
                success: function(response) {
                    if (response?.success) {
                        Swal.fire({
                            icon: 'success',
                            title: response?.message,
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                },
                error: function(response) {
                    const responseJSON = response?.responseJSON

                    if (response?.status === 422) {
                        for (const [key, value] of Object.entries(responseJSON?.data)) {
                            const type = $(form).find(`[name="${key}"]`).attr('type')

                            if (type === 'file') {
                                $(form).find(`[name="${key}"]`).parent().siblings('.error').html(value).css(
                                    'display', 'block')
                            } else {
                                $(form).find(`[name="${key}"]`).siblings('.error').html(value).css(
                                    'display', 'block')
                            }
                        }
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: responseJSON?.message,
                            showConfirmButton: false,
                            timer: 1000
                        })
                    }
                },
                complete: function() {
                    $(form).find('button[type="submit"]').attr('disabled', false)
                },
                ...options
            })
        }

        async function apiCall(options = {}) {
            return $.ajax({
                success: function(response) {
                    if (response?.success) {
                        Swal.fire({
                            icon: 'success',
                            title: response?.message,
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                },
                error: function(response) {
                    Swal.fire({
                        icon: 'error',
                        title: response?.responseJSON?.message,
                        showConfirmButton: false,
                        timer: 1500
                    })
                },
                ...options,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    ...options?.headers
                },
            })
        }

        function handler() {
            $('#form').on('submit', function(e) {
                e.preventDefault()

                const form = this

                return Swal.fire({
                    title: 'Apakah data yang akan disimpan sudah benar?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#00A19D',
                    cancelButtonColor: '#E05D5D',
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(form)[0]?.submit()
                    }
                })
            })
            $('.delete').on('submit', function(e) {
                e.preventDefault()

                const form = this

                return Swal.fire({
                    title: 'Apakah anda yakin ingin menghapus data ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#E05D5D',
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(form)[0]?.submit()
                    }
                })
            })

            $('#form-ajax').validate({
                submitHandler: function(form, e) {
                    e.preventDefault()

                    return Swal.fire({
                        title: 'Apakah anda sudah yakin?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#00A19D',
                        cancelButtonColor: '#E05D5D',
                        confirmButtonText: 'Ya',
                        cancelButtonText: 'Tidak'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            callApiWithForm(form, {
                                success: function(response) {
                                    if (response?.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: response?.message,
                                            showConfirmButton: false,
                                            timer: 1500
                                        }).then(() => {
                                            window.location.href = response
                                                ?.data?.route
                                        })

                                        $(form).trigger('reset')
                                    }
                                },
                            })
                        }
                    })
                }
            })
        }

        function countDownSession(data = {}) {
            const countDownSessionInterval = setInterval(() => {

                const currentTime = Date.now() / 1000 | 0;

                if (limitSession - currentTime <= 0) {

                    clearInterval(countDownSessionInterval);

                    Swal.close()

                    Swal.fire({
                        icon: 'warning',
                        title: 'Waktu sesi Anda telah habis. Mohon login kembali.',
                        showConfirmButton: true,
                        confirmButtonText: 'Tutup',
                        confirmButtonColor: '#C4C4C4'
                    }).then(() => window.location.href = '{{ route('auth.login.show') }}')
                } else if (!data.warning1 && limitSession - currentTime <= 600) {

                    clearInterval(countDownSessionInterval);

                    Swal.fire({
                        icon: 'warning',
                        title: 'Waktu sesi Anda akan berakhir 10 menit lagi.',
                        showConfirmButton: true,
                        showDenyButton: true,
                        confirmButtonText: 'Perpanjang sesi',
                        denyButtonText: `Tutup`,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            apiCall({
                                url: '{{ route('auth.refresh.session') }}',
                                success: function(response) {
                                    limitSession = response

                                    countDownSession({
                                        warning1: false
                                    })
                                },
                                error: function(response) {
                                    // do nothing
                                }
                            })
                        } else {
                            countDownSession({
                                warning1: true
                            })

                            // do something
                        }
                    })

                    countDownSession({
                        warning1: true
                    })
                }
            }, 1000);
        }
    </script>
    @stack('scripts')
    <script>
        const setup = () => {
            return {
                loading: true,
                isSidebarOpen: false,
                toggleSidbarMenu() {
                    this.isSidebarOpen = !this.isSidebarOpen
                },
                isSettingsPanelOpen: false,
                isSearchBoxOpen: false,
                isActive: this.isSidebarOpen,
                open: false
            }
        }
    </script>
</body>

</html>
