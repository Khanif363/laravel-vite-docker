@extends('layouts.master')

@section('content')
    <section>
        <h1>Comment</h1>
        <form id="form-comment" action="{{ route('change-managements.comment.create', $change) }}" method="post">
            @method('put')
            <div class="mb-3">
                <textarea id="comment" rows="4" name="comment"
                    class="block p-2.5 w-full text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Enter a comment ..." required></textarea>
                <label id="comment-error" class="error text-xs text-red-500" for="comment"></label>
            </div>
            <div class="flex justify-end mb-3">
                <button type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
                    Submit
                </button>
            </div>
        </form>
        <hr class="my-2 border-gray-200">
        <div id="list-comment" class="flex flex-col items-center justify-center antialiased bg-gray-100 w-full rounded-xl">
        </div>

        @push('scripts')
            @once
                <script type="text/javascript">
                    $(document).ready(function() {
                        init()
                        handler()
                    })

                    function init() {
                        initComment()
                    }

                    function initComment() {
                        $('#list-comment').empty()

                        apiCall({
                            url: '{{ route('change-managements.comment.all', $change) }}',
                            success: function(response) {
                                if (response?.success) {
                                    const comments = response.data.map(item => {
                                        return `
                                        <div class="flex-col w-full py-4 bg-white border-b-2 border-r-2 border-gray-200 sm:px-4 sm:py-4 md:px-4 sm:rounded-lg sm:shadow-sm mb-2">
                                            <div class="flex flex-row">
                                                <img class="object-cover w-12 h-12 border-2 border-gray-300 rounded-full"
                                                     alt="Noob master's avatar"
                                                     src="https://images.unsplash.com/photo-1517070208541-6ddc4d3efbcb?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&faces=1&faceindex=1&facepad=2.5&w=500&h=500&q=80">
                                                <div class="flex-col mt-1">
                                                    <div
                                                        class="flex items-center flex-1 px-4 font-bold leading-tight">${item.creator}
                                                        <span
                                                            class="ml-2 text-xs font-normal text-gray-500">${item.created_at}</span>
                                                    </div>
                                                    <div class="flex-1 px-2 ml-2 text-sm font-medium leading-loose text-gray-600">${item.comment}</div>
                                                </div>
                                            </div>
                                        </div>
                                        `
                                    }).join('')

                                    $('#list-comment').append(comments)
                                }
                            },
                            error: function(response) {
                                // Do nothing
                            }
                        })
                    }

                    function handler() {
                        $('#form-comment').validate({
                            submitHandler: function(form, e) {
                                e.preventDefault()

                                callApiWithForm(form, {
                                    success: function(response) {
                                        if (response?.success) {
                                            Swal.fire({
                                                icon: 'success',
                                                title: response.message,
                                                showConfirmButton: false,
                                                timer: 1500
                                            })

                                            $(form).trigger('reset')
                                            initComment()
                                        }
                                    },
                                })
                            }
                        })
                    }
                </script>
            @endonce
        @endpush
    @endsection
