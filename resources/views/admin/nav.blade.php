@section('nav')
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">
            <!-- Notifications Dropdown -->
            <div class="dropdown me-3 mt-1">
                <button class="btn btn-sm btn-light dropdown-toggle position-relative" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-bell"></i>
                    @if(Auth::user()->unreadNotifications->count() > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
                            {{ Auth::user()->unreadNotifications->count() }}
                        </span>
                    @endif
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="notificationDropdown" style="width: 300px; max-height: 400px; overflow-y: auto;">
                    <li><h6 class="dropdown-header">Notifications</h6></li>
                    @if(Auth::user()->unreadNotifications->count() > 0)
                        <li><a class="dropdown-item text-primary small" href="{{ route('notifications.markAsRead') }}">Mark all as read</a></li>
                        <li><hr class="dropdown-divider"></li>
                    @endif
                    @forelse(Auth::user()->unreadNotifications as $notification)
                        <li>
                            <a class="dropdown-item" href="{{ route('notification.read', $notification->id) }}" style="white-space: normal;">
                                <div class="small">{!! nl2br(e($notification->data['message'])) !!}</div>
                                <div class="text-muted" style="font-size: 0.75rem;">{{ $notification->created_at->diffForHumans() }}</div>
                            </a>
                        </li>
                    @empty
                        <li><span class="dropdown-item-text text-muted text-center small">No new notifications</span></li>
                    @endforelse
                </ul>
            </div>

            <div class="dropdown me-5">
                <button class="btn btn-sm btn-secondary dropdown-toggle " type="button" id="dropdownMenuButton1"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    {{Auth::user()->name }}
                </button>
                <ul class="dropdown-menu me-5" aria-labelledby="dropdownMenuButton1">
                    <li><a class="dropdown-item" href="{{ route('logout') }}">Logout</a></li>
                </ul>
            </div>
        </ul>
    </nav>
@endsection
