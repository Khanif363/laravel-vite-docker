@extends('layouts.blank')
@section('content')

    <section>

        @include('partials.navbar')

        <div
            class="p-5 bg-white border-2 border-tosca-0 rounded-xl shadow-md flex flex-col space-y-3 max-w-xl m-10 text-gray-700">
            <form id="form" action="{{ route('rating.save') }}" method="post">
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="font-semibold">
                    <span class="block">Dear Customer, penilaian anda sangat berarti untuk kami.</span>
                    <span>Berikan penilaian anda di bawah ini!</span>
                </div>
                <div class="flex items-center">
                    <input type="radio" class="transform scale-75 mr-2" name="rate" id="rate1" value="20" required>
                    <label for="rate1">Sangat tidak puas</label>
                </div>
                <div class="flex items-center">
                    <input type="radio" class="transform scale-75 mr-2" name="rate" id="rate2" value="40" required>
                    <label for="rate2">Tidak puas</label>
                </div>
                <div class="flex items-center">
                    <input type="radio" class="transform scale-75 mr-2" name="rate" id="rate3" value="60" required>
                    <label for="rate3">Cukup puas</label>
                </div>
                <div class="flex items-center">
                    <input type="radio" class="transform scale-75 mr-2" name="rate" id="rate4" value="80" required>
                    <label for="rate4">Puas</label>
                </div>
                <div class="flex items-center">
                    <input type="radio" class="transform scale-75 mr-2" name="rate" id="rate5" value="100" required>
                    <label for="rate5">Sangat Puas</label>
                </div>
                <label id="rate-error" class="error text-sm text-red-500" for="rate"></label>
                <div class="flex justify-start">
                    <button type="submit"
                            class="inline-block px-4 py-2 border-2 border-tosca-0 text-tosca-0 font-medium text-sm leading-tight capitalize shadow-md hover:shadow-lg rounded-xl hover:bg-tosca-0 hover:text-white focus:outline-none focus:ring-0 transition duration-150 ease-in-out">
                        <span>Submit</span>
                    </button>
                </div>
                <div class="font-semibold">
                    <span>Terimakasih</span>
                    <span class="text-xl">😊</span>
                </div>
            </form>
        </div>
    </section>

@endsection

@push('scripts')

    <script>
        $(document).ready(function () {
            handler()
        })

        function handler() {
            $('#form').validate({
                submitHandler: function (form, e) {
                    e.preventDefault()

                    const url = $(form).attr('action')
                    const method = $(form).attr('method')
                    const formData = new FormData(form)

                    $(form).find('input[type="checkbox"]').each(function (_, element) {
                        if (!$(element).data('not-find')) {
                            formData.set($(element).attr('name'), $(element).is(':checked') ? 1 : 0)
                        }
                    })

                    $.ajax({
                        url,
                        type: method,
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        beforeSend: function () {
                            $(form).find('.error').hide()
                            $(form).find('button[type="submit"]').attr('disabled', true)
                        },
                        success: function (response) {
                            if (response?.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: response?.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    window.close()
                                    window.location.reload()
                                })
                            }
                        },
                        error: function (response) {
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
                                    showConfirmButton: false
                                })
                            }
                        },
                        complete: function () {
                            $(form).find('button[type="submit"]').attr('disabled', false)
                        }
                    })
                }
            })
        }
    </script>

@endpush

