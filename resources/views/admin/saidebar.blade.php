@section('sidebar')
    <aside class="main-sidebar sidebar-dark-primary elevation-4">

        <div class="sidebar">
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="info">
                    <span class="d-block ms-5 text-white">Hello {{ Auth::user()->name }}</span>
                </div>
            </div>

            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                    <li class="nav-item menu-open">
                        <a href="{{ route('dashboard') }}" class="nav-link active">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Dashboard
                            </p>
                        </a>
                    </li>

                    @if (Auth::user()->role === 'admin')
                        <li class="nav-item">
                            <a href="{{ route('viewUserTable') }}" class="nav-link">
                                <i class="nav-icon fa-solid fa-users"></i>
                                <p>
                                    All Users
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('viewAdminTable') }}" class="nav-link">
                                <i class="nav-icon fa-solid fa-users"></i>
                                <p>
                                    All Dasboard User
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('viewAgentLeadlTable') }}" class="nav-link">
                                <i class="nav-icon fa-regular fa-user"></i>
                                <p>
                                    All Agent Lead Reports
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('viewAgentSaleTable') }}" class="nav-link">
                                <i class="nav-icon fa-solid fa-dollar-sign"></i>
                                <p>
                                    All Agent Sales Reports
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('viewMacExpiryData') }}" class="nav-link">
                                <i class="nav-icon fa-solid fa-exclamation"></i>
                                <p>
                                    Mac Expiry
                                </p>
                            </a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a href="{{ route('viewAgentTrialTable') }}" class="nav-link">
                            <i class="nav-icon far fa-image"></i>
                            <p>
                                All Agent Trial Reports
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('notServiceNumber') }}" class="nav-link">
                            <i class="nav-icon far fa-image"></i>
                            <p>
                                Not Service Numbers
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('viewPendingSale') }}" class="nav-link">
                            <i class="nav-icon fa-solid fa-dollar-sign"></i>
                            <p>
                                Pending Sale
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('viewHelpRequestTableDashboard') }}" class="nav-link">
                            <i class="nav-icon fa-solid fa-handshake-angle"></i>
                            <p>
                                Help Request
                            </p>
                        </a>
                    </li>

                    @if (Auth::user()->role === 'admin')
                        <li class="nav-item">
                            <a href="{{ route('viewCustomerNumber') }}" class="nav-link">
                                <i class="nav-icon fa-solid fa-phone"></i>
                                <p>
                                    Customer Response
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('viewNumbersTable') }}" class="nav-link">
                                <i class="nav-icon fa-solid fa-phone"></i>
                                <p>
                                    Numbers
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('viewOldNumber') }}" class="nav-link">
                                <i class="nav-icon fa-solid fa-phone"></i>
                                <p>
                                    Old Numbers
                                </p>
                            </a>
                        </li>
                    @endif

                    @if (Auth::user()->role === 'admin')
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa-solid fa-gear"></i>
                                <p>
                                    Support Team
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">

                                <li class="nav-item">
                                    <a href="{{ route('support.import') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Import Data</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                    @if (Auth::user()->role === 'admin')
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa-solid fa-gear"></i>
                                <p>
                                    Human Resource
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">

                                <li class="nav-item">
                                    <a href="{{ route('attendance.import') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Import Attendance</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('employee.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Manage Employee</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('attendance.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Employee Attendance</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('viewAllLeaveRecode') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Manage Leave</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('advance.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Manage Advances</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('payroll.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Manage Payroll</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('resignation.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Manage Resignation</p>
                                    </a>
                                </li>

                            </ul>
                        </li>
                    @endif
                    @if (Auth::user()->role === 'admin')
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fa-solid fa-gear"></i>
                                <p>
                                    Settings
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">

                                <li class="nav-item">
                                    <a href="{{ route('viewAdminUpdatePasswordForm') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Change Password</p>
                                    </a>
                                </li>

                            </ul>
                        </li>
                    @endif

                    <li class="nav-item">
                        <a href="{{ route('logout') }}" class="nav-link">
                            <i class="nav-icon fa-solid fa-right-from-bracket"></i>
                            <p>
                                Logout
                            </p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>
@endsection
