<header class="flex-shrink-0 border-b">
    <div class="flex items-center justify-between p-2 bg-tosca-0">
        <!-- Navbar left -->
        <div class="flex items-center space-x-3">
            @auth
                <span class="p-2 text-xl font-semibold tracking-wider uppercase text-white lg:hidden">Logo</span>
                <!-- Toggle sidebar button -->
                <button @click="toggleSidbarMenu()" class="p-2 rounded-md focus:outline-none focus:ring-2 focus:ring-white">
                    <svg class="w-4 h-4 text-white" :class="{ 'transform transition-transform -rotate-180': isSidebarOpen }"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                    </svg>
                </button>
            @endauth
            <div class="justify-self-start self-center" :class="{ 'hidden': isSidebarOpen }"
                @guest style="margin-left: 50px; height: 45px; display: flex; align-items: center;" @endguest>
                <span class="text-left text-white font-semibold text-2xl uppercase">COMIT2027</span>
            </div>
        </div>

        <div class="flex">
            <!-- Navbar right -->
            <div class="relative flex items-center space-x-3">
                <!-- Search button -->
                <button @click="isSearchBoxOpen = true"
                    class="p-2 bg-gray-100 rounded-full md:hidden focus:outline-none focus:ring hover:bg-gray-200">
                    <svg class="w-6 h-6 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
                @auth
                    <div class="items-center hidden space-x-3 md:flex">
                        <!-- User Menu -->
                        <div class="relative" x-data="{ isOpen: false }">

                            <button data-modal-toggle="receiver-notif"
                                class="p-1 bg-gray-200 rounded-full focus:outline-none focus:ring">
                                {{-- <img src="{{ asset('assets/img/profile.png') }}" class="object-cover w-8 h-8 rounded-full"
                            alt=""> --}}
                                <div class="flex justify-center items-center">
                                    <span
                                        class="fas fa-bell object-cover text-gray-500 w-8 h-8 rounded-full flex justify-center items-center"></span>
                                </div>
                            </button>
                            <!-- green dot -->
                            {{-- <div class="absolute right-0 p-1 bg-green-400 rounded-full bottom-3 animate-ping"></div>
                    <div class="absolute right-0 p-1 bg-green-400 border border-white rounded-full bottom-3">
                    </div> --}}

                            <div @click.away="isOpen = false" x-show.transition.opacity="isOpen"
                                class="absolute z-50 min-w-800 right-0 mt-3 transform bg-white rounded-md shadow-lg -translate-x-3/4">
                                {{-- <ul class="flex flex-col p-2 my-2 space-y-1">

                                    @forelse(auth()->user()->notifications as $notification)
                                        @if ($notification->notificationable->nomor_ticket ?? null)
                                            <a class="text-blue-600"
                                                href="{{ route('tickets.detail', $notification->notificationable->id) }}">{{ $notification->notificationable->nomor_ticket }}</a>
                                        @endif
                                        <li>
                                            <div class="font-bold text-sm">Read</div>
                                            <h5>{{ $notification->title }}Lorem ipsum is placeholder text commonly used in
                                                the graphic, print, and publishing industries for previewing layouts and
                                                visual mockups.

                                            </h5>
                                            <p>{{ $notification->content }}</p>
                                        </li>

                                    @empty
                                        <span class="text-gray-500">Tidak ada</span>
                                    @endforelse

                                </ul> --}}
                            </div>
                        </div>
                    </div>
                @endauth
            </div>
            <!-- Navbar right -->
            {{-- <div class="relative flex items-center space-x-3">
                <button @click="isSearchBoxOpen = true"
                    class="p-2 bg-gray-100 rounded-full md:hidden focus:outline-none focus:ring hover:bg-gray-200">
                    <svg class="w-6 h-6 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
                @auth
                    <div class="items-center hidden space-x-3 md:flex">
                        <div class="relative" x-data="{ isOpen: false }">

                            <button @click="isOpen = !isOpen"
                                class="p-1 bg-gray-200 rounded-full focus:outline-none focus:ring">
                                <div class="flex justify-center items-center">
                                    <span
                                        class="fas fa-user object-cover text-gray-500 w-8 h-8 rounded-full flex justify-center items-center"></span>
                                </div>
                            </button>


                            <div @click.away="isOpen = false" x-show.transition.opacity="isOpen"
                                class="absolute z-50 w-48 max-w-md mt-3 transform bg-white rounded-md shadow-lg -translate-x-3/4 min-w-max">
                                <ul class="flex flex-col p-2 my-2 space-y-1">
                                    <li>
                                        <button data-modal-toggle="password"
                                            class="block px-2 py-1 transition rounded-md text-gray-600 hover:bg-gray-100">Ubah
                                            Password</button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endauth
            </div> --}}
        </div>
</header>
<!-- Small Modal -->
<div id="password" tabindex="-1"
    class="fixed top-10 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
    <div class="relative w-full h-full max-w-xl md:h-auto inset-y-4">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-medium text-gray-900 dark:text-white">
                    Update Password
                </h3>
                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                    data-modal-toggle="password">
                    <div>
                        <span class="sr-only">Close modal</span>
                        <i class="fas fa-xmark fa-xl"></i>
                    </div>
                </button>
            </div>
            <form id="formGantiPassword">
                <!-- Modal body -->
                <div class="p-6 space-y-6">
                    <div class="space-y-2">
                        <div class="space-y-1 w-full flex flex-col">
                            <label for="" class="text-gray-600">Password Lama:</label>
                            <input type="password" id="old_password" name="old_password"
                                class="w-full p-2 py-2 bg-white border-1.5 border-yellow-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                                            focus:outline-none focus:ring-1 focus:ring-yellow-0
                                            disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                            <label for="old_password" class="error"></label>

                        </div>
                        <div class="grid grid-cols-1 xl:grid-cols-2 gap-2">
                            <div class="space-y-1 w-full flex flex-col">
                                <label for="" class="text-gray-600">Password Baru:</label>
                                <input type="password" id="new_password" name="new_password"
                                    class="w-full p-2 py-2 bg-white border-1.5 border-yellow-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                                                focus:outline-none focus:ring-1 focus:ring-yellow-0
                                                disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                                <label for="new_password" class="error"></label>
                            </div>
                            <div class="space-y-1 w-full flex flex-col">
                                <label for="" class="text-gray-600">Konfirmasi Password Baru:</label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="w-full p-2 py-2 bg-white border-1.5 border-yellow-0 rounded-xl text-sm shadow-sm placeholder-slate-400
                                                focus:outline-none focus:ring-1 focus:ring-yellow-0
                                                disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none">
                                <label for="password_confirmation" class="error"></label>
                            </div>
                        </div>
                    </div>
                    {{-- </div> --}}
                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button type="submit" data-modal-toggle="password"
                        class="text-white bg-tosca-0 hover:bg-tosca-1 focus:bg-tosca-1 active:bg-tosca-2 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                        Save
                    </button>
                    <button type="button" data-modal-toggle="password"
                        class="text-gray-500 clear bg-white hover:bg-gray-100 focus:outline-none rounded-xl border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<div id="receiver-notif" tabindex="-1"
    class="fixed top-10 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0">
    <div class="relative w-full h-full max-w-xl md:h-auto inset-y-4">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-medium text-gray-900 dark:text-white">
                    Notifikasi
                </h3>
                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                    data-modal-toggle="receiver-notif">
                    <div>
                        <span class="sr-only">Close modal</span>
                        <i class="fas fa-xmark fa-xl"></i>
                    </div>
                </button>
            </div>
            <div class="p-6 space-y-2">
                <ul class="flex flex-col p-2 my-2 space-y-1 text-gray-600">
                    @if (auth()->id() && ($permission ?? null))
                        @if ($permission['role'] === 'Admin')
                            @forelse(\App\Models\Notification::groupBy('notificationable_id')->get(); as $notification)
                                <li>
                                    <h5
                                        class="{{ $notification->is_read === 0 ? 'text-gray-300' : 'text-gray-600' }} font-semibold">
                                        {{ $notification->title }}</h5>
                                    <p class="{{ $notification->is_read === 0 ? 'text-gray-300' : 'text-gray-600' }}">
                                        {{ $notification->content }}</p>
                                    @if ($notification->notificationable->nomor_ticket ?? null)
                                        <span class="mr-2 text-gray-500">No. Ticket
                                            <a class="text-blue-600"
                                                href="{{ route('tickets.detail', $notification->notificationable->id) }}">{{ $notification->notificationable->nomor_ticket }}</a>
                                        </span>
                                        @if ($notification->is_read === 0)
                                            <div class="font-bold text-sm text-blue-600 cursor-pointer pin-read w-fit"
                                                data-id="{{ $notification->id }}">Read</div>
                                        @endif
                                    @endif
                                    {{-- @if (!$loop->first) --}}
                                    <hr>
                                    {{-- @endif --}}
                                </li>
                            @empty
                                <li class="">Tidak ada notifikasi</li>
                            @endforelse
                        @else
                            @forelse(auth()->user()->notifications as $notification)
                                @if ($notification->is_read === 0)
                                    <li>
                                        <h5
                                            class="{{ $notification->is_read === 0 ? 'text-gray-300' : 'text-gray-600' }} font-semibold">
                                            {{ $notification->title }}</h5>
                                        <p
                                            class="{{ $notification->is_read === 0 ? 'text-gray-300' : 'text-gray-600' }}">
                                            {{ $notification->content }}</p>
                                        @if ($notification->notificationable->nomor_ticket ?? null)
                                            <span class="mr-2 text-gray-500">No. Ticket
                                                <a class="text-blue-600"
                                                    href="{{ route('tickets.detail', $notification->notificationable->id) }}">{{ $notification->notificationable->nomor_ticket }}</a>
                                            </span>
                                            @if ($notification->is_read === 0)
                                                <div class="font-bold text-sm text-blue-600 cursor-pointer pin-read w-fit"
                                                    data-id="{{ $notification->id }}">Read</div>
                                            @endif
                                        @endif
                                        {{-- @if (!$loop->first) --}}
                                        <hr>
                                        {{-- @endif --}}
                                    </li>
                                @endif
                            @empty
                                <li class="">Tidak ada notifikasi</li>
                            @endforelse
                        @endif
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>


@push('scripts')
    @once
        <script type="text/javascript">
            $(document).ready(function() {
                $("#formGantiPassword").validate({
                    rules: {
                        old_password: {
                            required: true
                        },
                        new_password: {
                            required: true,
                            minlength: 8,
                            strongPassword: true
                        },
                        password_confirmation: {
                            required: true,
                            equalTo: "#new_password"
                        }
                    },
                    messages: {
                        old_password: {
                            required: "Masukkan password lama"
                        },
                        new_password: {
                            required: "Masukkan password baru",
                            minlength: "Password minimal 8 karakter",
                            strongPassword: "Password harus campuran antara huruf besar dan huruf kecil"
                        },
                        password_confirmation: {
                            required: "Masukkan konfirmasi password baru",
                            equalTo: "Password konfirmasi tidak sama"
                        }
                    },
                    submitHandler: function(form) {
                        apiCall({
                            url: '{{ route('users.update-password') }}',
                            data: {
                                old_password: $('#old_password').val(),
                                new_password: $('#new_password').val(),
                                password_confirmation: $('#password_confirmation').val()
                            },
                            type: 'POST',
                            success: function(response) {
                                if (response?.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: response?.message,
                                        showConfirmButton: false,
                                        timer: 1500
                                    })
                                }
                            }
                        })
                    }
                });

                $.validator.addMethod("strongPassword", function(value, element) {
                    return this.optional(element) ||
                        /^(?=.*[a-z])(?=.*[A-Z]).{8,}$/.test(value);
                }, "Password harus campuran antara huruf besar dan huruf kecil");

                $('.pin-read').click(function() {
                    // console.log($(this).data('id'));
                    let id = $(this).data('id')
                    let url = `{{ route('notif.read', ':id') }}`;
                    url = url.replace(':id', id);
                    // console.log(url);

                    apiCall({
                        url,
                        type: 'POST',
                        data: {
                            is_read: 1,
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
                        }
                    })

                })
            });
        </script>
    @endonce
@endpush
