@extends('layouts.blank')
@section('content')
    <section>
        <div class="absolute flex items-center justify-center w-full mt-14">
            <span class="text-2xl font-bold text-center text-tosca-0">COMIT2027</span>
        </div>
        <div class="flex items-center justify-center w-full h-screen bg-grey-0">
            <div class="flex justify-center w-full h-auto">
                <div
                    class="grid w-9/12 px-8 py-8 bg-white border rounded-xl sm:grid-cols-1 sm:gap-y-8 md:gap-x-8 md:grid-cols-3">
                    <div class="w-full col-span-2">
                        <iframe src="https://www.youtube.com/embed/yWMxb5g_xqc" title="Comit Video" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen class="w-full h-full rounded-xl"></iframe>
                    </div>
                    <div class="w-full">
                        <div class="flex justify-center text-center">
                            <span class="text-2xl font-medium text-gray-600">Login</span>
                        </div>
                        <form action="{{ route($route) }}" method="post" id="form">
                            <div class="flex flex-col mt-6 space-y-2">
                                <div>
                                    <div class="text-start">
                                        <label for="username" class="text-base text-gray-600 whitespace-nowrap">NIK<sup><i
                                                    class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                                    </div>
                                    <div class="flex flex-col justify-end w-full">
                                        <div class="relative flex justify-end w-full">
                                            <input name="username" type="text" id="username"
                                                class="block w-full pl-2 pr-9 py-2 bg-white border-1.5 border-yellow-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                                                    focus:outline-none focus:ring-1 focus:ring-yellow-0
                                                    disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none
                                                    invalid:border-pink-500 invalid:text-pink-600
                                                    focus:invalid:border-pink-500 focus:invalid:ring-pink-500">
                                            <i
                                                class="fas fa-hashtag h-6 w-6 absolute my-2.5 mr-2 pointer-events-none text-yellow-0"></i>
                                        </div>
                                        {{-- @error('username') --}}
                                        <span class="text-sm text-pink-600 error inline-text" id="username-error"></span>
                                        {{-- @enderror --}}
                                    </div>
                                </div>
                                <div>
                                    <div class="text-start">
                                        <label for="password"
                                            class="text-base text-gray-600 whitespace-nowrap">Password<sup><i
                                                    class="w-1 h-1 scale-50 fas fa-star-of-life text-red-0"></i></sup></label>
                                    </div>
                                    <div class="flex flex-col justify-end w-full">
                                        <div class="relative flex justify-end w-full">
                                            <input name="password" type="password" id="password"
                                                class="block w-full pl-2 pr-9 py-2 bg-white border-1.5 border-yellow-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                                                    focus:outline-none focus:ring-1 focus:ring-yellow-0
                                                    disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none
                                                    invalid:border-pink-500 invalid:text-pink-600
                                                    focus:invalid:border-pink-500 focus:invalid:ring-pink-500">
                                            <i
                                                class="fas fa-key h-6 w-6 absolute my-2.5 mr-2 pointer-events-none text-yellow-0"></i>
                                        </div>
                                        {{-- @error('password') --}}
                                        <span class="text-sm text-pink-600 error inline-text" id="password-error"></span>
                                        {{-- @enderror --}}
                                    </div>
                                </div>
                                <div class="flex items-center justify-center w-full">
                                    <input id="is_enable" name="is_enable" type="checkbox" value="1" onclick="showPassword()"
                                        class="w-4 h-4 scale-75 rounded-md">
                                        <label for="is_enable" class="w-full ml-2 text-gray-600">Show Password</label>
                                </div>
                            </div>
                            <div class="mt-7">
                                <button type="submit"
                                    class="w-full px-4 py-2.5 border-2 text-base hover:border-tosca-0 hover:text-tosca-0 hover:bg-white font-medium leading-tight capitalize drop-shadow-md hover:drop-shadow-lg rounded-xl bg-tosca-0 text-white focus:outline-none focus:ring-0 transition duration-150 ease-in-out">Submit</button>
                            </div>
                        </form>
                        <div class="flex items-center mt-2">
                            <i class='fas fa-circle-info text-red-0'></i>
                            <span class="ml-1 text-sm text-left text-red-0">Silahkan login menggunakan akun LDAP Anda</span>
                        </div>
                        <div class="flex flex-col justify-center w-full mt-4">
                            <div class="block w-full text-center">
                                <span class="text-sm text-gray-600">Tentang</span>
                                <a href="{{ route('kebijakan-keamanan') }}" class="text-sm text-blue-600">Kebijakan &
                                    Keamanan</a>
                            </div>
                            <div class="block w-full mt-4 text-center">
                                <a href="{{ route('pusat-pengetahuan') }}" class="text-sm text-blue-600">Pusat
                                    Pengetahuan</a>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="block mx-auto mt-2">
                    <span class="text-xl font-medium text-center text-tosca-0">CTRL + F5</span>
                </div> --}}
            </div>
        </div>
    </section>
    <div class="fixed bottom-0 left-0 right-0 w-full mb-6 px-14">
        <div class="flex justify-between text-blue-600">
            <span class="p-2 w-fit h-fit rounded-xl backdrop-blur-sm">Copyright 2022</span>
            <span class="p-2 w-fit h-fit rounded-xl backdrop-blur-sm">Telkom Satelit Indonesia</span>
        </div>
    </div>
    @push('scripts')
        @once
            <script>
                $(document).ready(function() {
                    handler()
                })

                function showPassword() {
                    var x = document.getElementById("password");
                    if (x.type === "password") {
                        x.type = "text";
                    } else {
                        x.type = "password";
                    }
                }

                function handler() {
                    $('#form').validate({
                        submitHandler: function(form, e) {
                            e.preventDefault()

                            // grecaptcha.ready(function() {
                            //     grecaptcha.execute('<?= env('RECAPTCHA3_SITE_KEY') ?>', {
                            //         action: 'login'
                            //     }).then(function(token) {
                            //         $(form).find('input[name="token"], input[name="action"]').remove()

                                    // $(form).prepend('<input type="hidden" name="token" value="' +
                                    //     token + '">')
                                    $(form).prepend('<input type="hidden" name="action" value="login">')

                                    const url = $(form).attr('action')
                                    const method = $(form).attr('method')
                                    const formData = new FormData(form)

                                    $.ajax({
                                        url,
                                        type: method,
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                                'content')
                                        },
                                        cache: false,
                                        contentType: false,
                                        processData: false,
                                        data: formData,
                                        beforeSend: function() {
                                            $(form).find('.error').hide()
                                            $(form).find('button[type="submit"]').attr(
                                                'disabled', true).text('').append(
                                                '<i class="fas fa-circle-notch fa-spin fa-lg"></i>'
                                            );
                                        },
                                        success: function(response) {
                                            $(`button[type="submit"]`).text('Submit');
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
                                            }
                                        },
                                        error: function(response) {
                                            $(`button[type="submit"]`).text('Submit');
                                            const responseJSON = response?.responseJSON

                                            if (response.status === 422) {
                                                for (const [key, value] of Object.entries(
                                                        responseJSON?.data)) {
                                                    const type = $(form).find(
                                                        `[name="${key}"]`).attr('type')

                                                    if (type === 'file') {
                                                        $(form).find(`[name="${key}"]`)
                                                            .parent().siblings('.error')
                                                            .html(value).css('display',
                                                                'block')
                                                    } else {
                                                        $(form).find(`[name="${key}"]`)
                                                            .siblings('.error').html(value)
                                                            .css('display', 'block')
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
                                            $(form).find('button[type="submit"]').attr(
                                                'disabled', false)
                                        }
                                    })
                            //     })
                            // })
                        }
                    })
                }
            </script>
        @endonce
    @endpush
@endsection
