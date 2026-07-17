@section('front.nav')
    <nav class="w-full h-[80px] flex justify-between place-items-center px-5 bg-[#1D4ED8]">
        <div class="w-[19%]">
            <img class="w-[70%]" src="{{ asset('logo/logo.png') }}" alt="">
        </div>
        @if (Auth::user()->role == 'support')
            <div class="me-12">
                <ul class="flex justify-center place-items-center gap-[1rem] ">
                    <li class=""><a href="{{ route('supportNumbers') }}" class="text-white">Support</a></li>
                    <!-- daniyal number 
                        saad number 
                    view route show here -->
                    <li class=""><a href="{{ route('support.helpRequests') }}" class="text-white">Help Requests</a></li>
                    <li class=""><a href="{{ route('support.resolvedRequests') }}" class="text-white">Resolved Requests</a></li>
                </ul>
            </div>
        @else
            <div class="me-12">
                <ul class="flex justify-center place-items-center gap-[1rem] ">
                    <li class=""><a href="{{ route('viewHome') }}" class="text-white">Home</a></li>
                    <li class=""><a href="{{ route('customerSalesTable') }}" class="text-white">Sale Page</a></li>
                    <li class=""><a href="{{ route('customerLeadTable') }}" class="text-white">Lead Page</a></li>
                    <li class=""><a href="{{ route('customerDeniedTable') }}" class="text-white">Denied Page</a></li>
                    <li class=""><a href="{{ route('customerTrialTable') }}" class="text-white">Trial Page</a></li>
                    <li class=""><a href="{{ route('viewHelpTable') }}" class="text-white">Help </a></li>
                    <li class=""><a href="{{ route('help') }}" class="text-white">Help Request</a></li>
                    <li class=""><a href="{{ route('viewCunstomerNumberTable') }}" class="text-white"> Calling
                            Numbers</a>
                    <li class=""><a href="{{ route('viewSaleExpiry') }}" class="text-white">Renewal</a>
                    </li>
                </ul>
            </div>
        @endif
        <div class="flex place-items-center gap-6">
            {{-- <span class="font-bold text-white text-xl">{{ Auth::user()->name }} </span>
            <a href="{{ route('logout') }}" class="btn btn-light">Logout</a> --}}

            {{--  Notification icon new Code Start --}}

            <!-- 👇 NEW CODE: Notifications Bell Dropdown 👇 -->
    <div class="relative w-max mx-auto me-2">
        <button type="button" id="notificationToggle" class="relative p-2 text-white hover:text-gray-200 cursor-pointer outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            @if(Auth::user()->unreadNotifications->count() > 0)
                <span class="absolute top-1 right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-red-600 rounded-full">
                    {{ Auth::user()->unreadNotifications->count() }}
                </span>
            @endif
        </button>

        <div id="notificationMenu" class="absolute right-0 hidden shadow-lg bg-white py-2 z-[1000] w-80 rounded-lg max-h-96 overflow-auto border border-slate-200 mt-2">
            <div class="px-4 py-2 border-b border-slate-100 flex justify-between place-items-center">
                <span class="font-bold text-slate-700 text-sm">Notifications</span>
                @if(Auth::user()->unreadNotifications->count() > 0)
                    {{-- Yahan "Mark all read" aayega, jis mein $notification ki zaroorat nahi --}}
                    <a href="{{ route('notifications.markAsRead') }}" class="text-xs text-blue-600 hover:underline">Mark all read</a>
                @endif
            </div>
            <ul class="flex flex-col">
                {{-- Loop yahan se shuru hota hai, $notification sirf iske andar kaam karega --}}
                @forelse(Auth::user()->unreadNotifications as $notification)
                    <li class="border-b border-slate-50 last:border-0 hover:bg-slate-50">
                        {{-- Yahan single notification read wala naya route aayega --}}
                        <a href="{{ route('notification.read', $notification->id) }}" class="block px-4 py-3 text-sm text-slate-600">
                            {{ $notification->data['message'] }}
                            <span class="block text-xs text-slate-400 mt-1">{{ $notification->created_at->diffForHumans() }}</span>
                        </a>
                    </li>
                @empty
                    <li class="px-4 py-3 text-sm text-slate-500 text-center">No new notifications</li>
                @endforelse
            </ul>
        </div>
    </div>
    <!-- 👆 Notifications Bell Dropdown End 👆 -->

            {{--  Notification icon Code End Here   --}}

            <div class="relative w-max mx-auto">
                <button type="button" id="dropdownToggle"
                    class="px-4 py-2 flex items-center bg-white rounded-lg text-slate-900 text-sm font-medium border border-slate-300 outline-none hover:bg-slate-100 cursor-pointer">

                    {{-- User Profile Image --}}
                    @if (Auth::user()->employe && Auth::user()->employe->profile_img)
                        <img src="{{ asset(Auth::user()->employe->profile_img) }}"
                            class="w-7 h-7 mr-3 rounded-full shrink-0">
                    @else
                        <img src="{{ asset('logo/profile.png') }}" class="w-7 h-7 mr-3 rounded-full shrink-0">
                    @endif

                    {{-- User Name --}}
                    <span>{{ Auth::user()->name }}</span>

                    {{-- Down Arrow Icon --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 fill-slate-400 inline ml-3" viewBox="0 0 24 24">
                        <path fill-rule="evenodd"
                            d="M11.99997 18.1669a2.38 2.38 0 0 1-1.68266-.69733l-9.52-9.52a2.38 2.38 0 1 1 3.36532-3.36532l7.83734 7.83734 7.83734-7.83734a2.38 2.38 0 1 1 3.36532 3.36532l-9.52 9.52a2.38 2.38 0 0 1-1.68266.69734z"
                            clip-rule="evenodd" />
                    </svg>

                </button>



                <!-- 👇 Default: hidden -->
                <ul id="dropdownMenu"
                    class="absolute hidden shadow-lg bg-white py-2 z-[1000] min-w-full w-max rounded-lg max-h-96 overflow-auto">
                    <li
                        class="dropdown-item py-2.5 px-5 flex items-center hover:bg-slate-100 text-slate-600 font-medium text-sm cursor-pointer">
                        <a href="{{ route('employee.profile') }}" class="flex">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-4 h-4 mr-3"
                                viewBox="0 0 512 512">
                                <path
                                    d="M337.711 241.3a16 16 0 0 0-11.461 3.988c-18.739 16.561-43.688 25.682-70.25 25.682s-51.511-9.121-70.25-25.683a16.007 16.007 0 0 0-11.461-3.988c-78.926 4.274-140.752 63.672-140.752 135.224v107.152C33.537 499.293 46.9 512 63.332 512h385.336c16.429 0 29.8-12.707 29.8-28.325V376.523c-.005-71.552-61.831-130.95-140.757-135.223zM446.463 480H65.537V376.523c0-52.739 45.359-96.888 104.351-102.8C193.75 292.63 224.055 302.97 256 302.97s62.25-10.34 86.112-29.245c58.992 5.91 104.351 50.059 104.351 102.8zM256 234.375a117.188 117.188 0 1 0-117.188-117.187A117.32 117.32 0 0 0 256 234.375zM256 32a85.188 85.188 0 1 1-85.188 85.188A85.284 85.284 0 0 1 256 32z">
                                </path>
                            </svg>
                            View profile
                        </a>
                    </li>

                    <li
                        class="dropdown-item py-2.5 px-5 flex items-center hover:bg-slate-100 text-slate-600 font-medium text-sm cursor-pointer">
                        <a href="{{ route('leave.create') }}">
                            <i class="fa-solid fa-person-walking-arrow-right me-2"></i>
                            Request Leave
                        </a>
                    </li>

                    <li
                        class="dropdown-item py-2.5 px-5 flex items-center hover:bg-slate-100 text-slate-600 font-medium text-sm cursor-pointer">
                        <a href="{{ route('leave.index') }}">
                            <i class="fa-solid fa-leaf me-2"></i>
                            Manage Leave
                        </a>
                    </li>

                    <li
                        class="dropdown-item py-2.5 px-5 flex items-center hover:bg-slate-100 text-slate-600 font-medium text-sm cursor-pointer">
                        <a href="{{ route('resignation.create') }}">
                            <i class="fa-solid fa-leaf me-2"></i>
                            Request Resignation
                        </a>
                    </li>

                    <li
                        class="dropdown-item py-2.5 px-5 flex items-center hover:bg-slate-100 text-slate-600 font-medium text-sm cursor-pointer">
                        <a href="{{ route('logout') }}" class="flex">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-4 h-4 mr-3"
                                viewBox="0 0 6.35 6.35">
                                <path
                                    d="M3.172.53a.265.266 0 0 0-.262.268v2.127a.265.266 0 0 0 .53 0V.798A.265.266 0 0 0 3.172.53zm1.544.532a.265.266 0 0 0-.026 0 .265.266 0 0 0-.147.47c.459.391.749.973.749 1.626 0 1.18-.944 2.131-2.116 2.131A2.12 2.12 0 0 1 1.06 3.16c0-.65.286-1.228.74-1.62a.265.266 0 1 0-.344-.404A2.667 2.667 0 0 0 .53 3.158a2.66 2.66 0 0 0 2.647 2.663 2.657 2.657 0 0 0 2.645-2.663c0-.812-.363-1.542-.936-2.03a.265.266 0 0 0-.17-.066z">
                                </path>
                            </svg>
                            Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    {{-- <script>
        document.addEventListener('DOMContentLoaded', () => {
            let dropdownToggle = document.getElementById('dropdownToggle');
            let dropdownMenu = document.getElementById('dropdownMenu');

            function toggleDropdown() {
                dropdownMenu.classList.toggle('hidden');
                dropdownMenu.classList.toggle('block');
            }

            function hideDropdown() {
                dropdownMenu.classList.add('hidden');
                dropdownMenu.classList.remove('block');
            }

            dropdownToggle.addEventListener('click', (event) => {
                event.stopPropagation();
                toggleDropdown();
            });

            dropdownMenu.querySelectorAll('.dropdown-item').forEach((li) => {
                li.addEventListener('click', () => {
                    hideDropdown();
                });
            });

            document.addEventListener('click', (event) => {
                if (!dropdownMenu.contains(event.target) && event.target !== dropdownToggle) {
                    hideDropdown();
                }
            });
        });
    </script> --}}

    {{-- Testing Code  Start Notification & Dropdown Profile --}}

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        let dropdownToggle = document.getElementById('dropdownToggle');
        let dropdownMenu = document.getElementById('dropdownMenu');
        
        let notificationToggle = document.getElementById('notificationToggle');
        let notificationMenu = document.getElementById('notificationMenu');

        function toggleDropdown() {
            dropdownMenu.classList.toggle('hidden');
            dropdownMenu.classList.toggle('block');
            hideNotifications(); // Hide notification if profile is clicked
        }

        function hideDropdown() {
            dropdownMenu.classList.add('hidden');
            dropdownMenu.classList.remove('block');
        }

        function toggleNotifications() {
            notificationMenu.classList.toggle('hidden');
            notificationMenu.classList.toggle('block');
            hideDropdown(); // Hide profile if notification is clicked
        }

        function hideNotifications() {
            notificationMenu.classList.add('hidden');
            notificationMenu.classList.remove('block');
        }

        dropdownToggle.addEventListener('click', (event) => {
            event.stopPropagation();
            toggleDropdown();
        });

        notificationToggle.addEventListener('click', (event) => {
            event.stopPropagation();
            toggleNotifications();
        });

        document.addEventListener('click', (event) => {
            if (!dropdownMenu.contains(event.target) && event.target !== dropdownToggle) {
                hideDropdown();
            }
            if (!notificationMenu.contains(event.target) && event.target !== notificationToggle) {
                hideNotifications();
            }
        });
    });
</script>

{{-- End Testing Code For Notification & Dropdown Profile  --}}

@endsection
