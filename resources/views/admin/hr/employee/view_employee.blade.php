@extends('layout.app')
@extends('admin.nav')
@extends('admin.saidebar')

@section('content')
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-light: #3b82f6;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #06b6d4;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --bg-light: #f9fafb;
            --border-color: #e5e7eb;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 10px 30px rgba(0, 0, 0, 0.12);
        }

        .content-wrapper {
            background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
            min-height: 100vh;
            padding: 30px 0;
        }

        /* Enhanced Profile Card */
        .profile-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 30px;
            box-shadow: var(--shadow-lg);
            border: 1px solid rgba(0, 0, 0, 0.04);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }

        .profile-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);
        }

        /* Profile Image Section */
        .profile-img-wrapper {
            position: relative;
            display: inline-block;
            margin-bottom: 20px;
        }

        .profile-img {
            width: 140px;
            height: 140px;
            object-fit: cover;
            border-radius: 50%;
            border: 5px solid var(--primary-color);
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.25);
            transition: transform 0.3s ease;
        }

        .profile-img:hover {
            transform: scale(1.05);
        }

        .profile-status-badge {
            position: absolute;
            bottom: 10px;
            right: 10px;
            width: 20px;
            height: 20px;
            background: var(--success-color);
            border: 3px solid white;
            border-radius: 50%;
            box-shadow: var(--shadow-sm);
        }

        /* Employee Header */
        .employee-header {
            text-align: center;
            margin-bottom: 25px;
        }

        .employee-name {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .employee-type-badge {
            display: inline-block;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white;
            padding: 6px 20px;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        /* Info Box Redesign */
        .info-box {
            background: linear-gradient(135deg, #f9fafb 0%, #ffffff 100%);
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 18px;
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column
        }

        .info-box:hover {
            box-shadow: var(--shadow-md);
            border-color: var(--primary-color);
        }

        .contact-info-item {
            display: flex;
            align-items: start;
            margin-bottom: 16px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--border-color);
        }

        .contact-info-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .contact-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            flex-shrink: 0;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.2);
        }

        .contact-icon svg {
            width: 20px;
            height: 20px;
            color: white;
        }

        .contact-details {
            flex: 1;
        }

        .contact-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .contact-value {
            font-size: 0.95rem;
            color: var(--text-primary);
            word-break: break-word;
            font-weight: 500;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 12px;
            margin-top: 25px;
        }

        .btn-custom {
            flex: 1;
            padding: 12px 20px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            box-shadow: var(--shadow-sm);
        }

        .btn-back {
            background: white;
            color: var(--text-secondary);
            border: 2px solid var(--border-color);
        }

        .btn-back:hover {
            background: var(--bg-light);
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateY(-2px);
        }

        .btn-edit {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white;
        }

        .btn-edit:hover {
            background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
        }

        /* Details Section Header */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--border-color);
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
        }

        .joined-date {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-secondary);
            font-size: 0.875rem;
            background: var(--bg-light);
            padding: 8px 16px;
            border-radius: 8px;
        }

        /* Stats Cards */
        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
            height: 100%;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }

        .stats-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            box-shadow: var(--shadow-sm);
        }

        .stats-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .stats-value {
            font-size: 1.75rem;
            font-weight: 700;
            margin: 0;
        }

        .stats-value.success {
            color: var(--success-color);
        }

        .stats-value.primary {
            color: var(--primary-color);
        }

        .stats-value.warning {
            color: var(--warning-color);
        }

        .stats-value.danger {
            color: var(--danger-color);
        }

        .stats-value.info {
            color: var(--info-color);
        }

        .icon-success {
            background: linear-gradient(135deg, #10b981, #34d399);
        }

        .icon-primary {
            background: linear-gradient(135deg, #2563eb, #3b82f6);
        }

        .icon-warning {
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
        }

        .icon-danger {
            background: linear-gradient(135deg, #ef4444, #f87171);
        }

        .icon-info {
            background: linear-gradient(135deg, #06b6d4, #22d3ee);
        }

        /* Performance Section */
        .performance-section {
            margin-top: 30px;
        }

        .subsection-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .subsection-title::before {
            content: '';
            width: 4px;
            height: 24px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            border-radius: 2px;
        }

        /* Document Cards */
        .document-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            border: 2px solid var(--border-color);
            margin-bottom: 15px;
            transition: all 0.3s ease;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .document-card:hover {
            border-color: var(--primary-color);
            box-shadow: var(--shadow-md);
            transform: translateX(5px);
        }

        .document-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .document-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.2);
        }

        .document-icon svg {
            width: 24px;
            height: 24px;
            color: white;
        }

        .document-details {
            display: flex;
            flex-direction: column;
        }

        .document-title {
            font-weight: 700;
            font-size: 1rem;
            color: var(--text-primary);
            margin-bottom: 4px;
        }

        .document-status {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .document-status.available {
            color: var(--success-color);
            font-weight: 600;
        }

        .document-actions {
            display: flex;
            gap: 10px;
        }

        .btn-document {
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-view {
            background: white;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }

        .btn-view:hover {
            background: var(--primary-color);
            color: white;
        }

        .btn-download {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white;
        }

        .btn-download:hover {
            background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.4);
        }

        /* User Info Table */
        .user-info-section {
            margin-top: 30px;
            background: linear-gradient(135deg, #f9fafb 0%, #ffffff 100%);
            border-radius: 12px;
            padding: 25px;
            border: 1px solid var(--border-color);
        }

        .user-info-table {
            width: 100%;
            margin-top: 15px;
        }

        .user-info-table tr {
            border-bottom: 1px solid var(--border-color);
        }

        .user-info-table tr:last-child {
            border-bottom: none;
        }

        .user-info-table td {
            padding: 12px 0;
            font-size: 0.95rem;
        }

        .table-label {
            font-weight: 700;
            color: var(--text-secondary);
            width: 140px;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }

        .table-value {
            color: var(--text-primary);
            font-weight: 500;
        }

        /* Responsive Design */
        @media (max-width: 991.98px) {
            .section-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
        }

        @media (max-width: 767.98px) {
            .content-wrapper {
                padding: 20px 0;
            }

            .profile-card {
                padding: 20px;
            }

            .profile-img {
                width: 110px;
                height: 110px;
                border-width: 4px;
            }

            .employee-name {
                font-size: 1.5rem;
            }

            .action-buttons {
                flex-direction: column;
            }

            .section-title {
                font-size: 1.25rem;
            }

            .document-card {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .document-actions {
                width: 100%;
            }

            .btn-document {
                flex: 1;
            }

            .stats-value {
                font-size: 1.5rem;
            }
        }
    </style>

    <div class="content-wrapper">
        <div class="container-fluid">
            <div class="row gx-4">

                {{-- LEFT PROFILE CARD --}}
                <div class="col-xl-4 col-lg-5 col-md-6 mb-4">
                    <div class="profile-card">
                        <div class="employee-header">
                            <div class="profile-img-wrapper">
                                @if ($employee->profile_img)
                                    <img src="{{ $employee->profile_img ? asset($employee->profile_img) : asset('default-avatar.png') }}"
                                        alt="Profile" class="profile-img">
                                @else
                                    <img src="{{ asset('logo/profile.png') }}" alt="Profile" class="profile-img">
                                @endif

                                <div class="profile-status-badge"></div>
                            </div>

                            <h4 class="employee-name">{{ $employee->user->name }}</h4>
                            <span class="employee-type-badge">{{ $employee->employe_type }} Employee</span>
                        </div>

                        <div class="info-box mt-4">
                            <div class="contact-info-item">
                                <div class="contact-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="contact-details">
                                    <div class="contact-label">Email Address</div>
                                    <div class="contact-value">{{ $employee->user->email ?? 'N/A' }}</div>
                                </div>
                            </div>

                            <div class="contact-info-item">
                                <div class="contact-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <div class="contact-details">
                                    <div class="contact-label">Phone Number</div>
                                    <div class="contact-value">{{ $employee->user->phone ?? 'N/A' }}</div>
                                </div>
                            </div>

                            <div class="contact-info-item">
                                <div class="contact-icon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div class="contact-details">
                                    <div class="contact-label">Address</div>
                                    <div class="contact-value">{{ $employee->user->address ?? 'No Address' }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="action-buttons">
                            <a href="{{ route('employee.index') }}" class="btn btn-custom btn-back">
                                ← Back
                            </a>
                            <a href="{{ route('employee.edit', $employee->id) }}" class="btn btn-custom btn-edit">
                                Edit Profile
                            </a>
                        </div>
                    </div>
                </div>

                {{-- RIGHT DETAILS CARD --}}
                <div class="col-xl-8 col-lg-7 col-md-6">
                    <div class="profile-card">
                        <div class="section-header">
                            <h3 class="section-title">Employee Full Details</h3>
                            <div class="joined-date">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Joined: {{ optional($employee->created_at)->format('d M, Y') ?? 'N/A' }}
                            </div>
                        </div>

                        {{-- Main Stats --}}
                        <div class="row gx-3 mb-4">
                            <div class="col-md-4 col-sm-6 mb-3">
                                <div class="stats-card">
                                    <div class="stats-icon icon-success">
                                        <svg width="24" height="24" fill="white" viewBox="0 0 24 24">
                                            <path
                                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.31-8.86c-1.77-.45-2.34-.94-2.34-1.67 0-.84.79-1.43 2.1-1.43 1.38 0 1.9.66 1.94 1.64h1.71c-.05-1.34-.87-2.57-2.49-2.97V5H10.9v1.69c-1.51.32-2.72 1.3-2.72 2.81 0 1.79 1.49 2.69 3.66 3.21 1.95.46 2.34 1.15 2.34 1.87 0 .53-.39 1.39-2.1 1.39-1.6 0-2.23-.72-2.32-1.64H8.04c.1 1.7 1.36 2.66 2.86 2.97V19h2.34v-1.67c1.52-.29 2.72-1.16 2.73-2.77-.01-2.2-1.9-2.96-3.66-3.42z" />
                                        </svg>
                                    </div>
                                    <div class="stats-label">Monthly Salary</div>
                                    <h3 class="stats-value success">PKR {{ number_format($employee->salary, 0) }}</h3>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-6 mb-3">
                                <div class="stats-card">
                                    <div class="stats-icon icon-primary">
                                        <svg width="24" height="24" fill="white" viewBox="0 0 24 24">
                                            <path
                                                d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" />
                                        </svg>
                                    </div>
                                    <div class="stats-label">Monthly Target</div>
                                    <h3 class="stats-value primary">${{ number_format($employee->target, 0) }}</h3>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-6 mb-3">
                                <div class="stats-card">
                                    <div class="stats-icon icon-info">
                                        <svg width="24" height="24" fill="white" viewBox="0 0 24 24">
                                            <path
                                                d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                                        </svg>
                                    </div>
                                    <div class="stats-label">Employee Type</div>
                                    <h3 class="stats-value info" style="font-size: 1.25rem;">{{ $employee->employe_type }}
                                    </h3>
                                </div>
                            </div>
                        </div>

                        {{-- Attendance Stats --}}
                        <div class="row gx-3 mb-4">
                            <div class="col-md-6 mb-3">
                                <div class="stats-card">
                                    <div class="stats-icon icon-warning">
                                        <svg width="24" height="24" fill="white" viewBox="0 0 24 24">
                                            <path
                                                d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z" />
                                        </svg>
                                    </div>
                                    <div class="stats-label">Late Count</div>
                                    <h3 class="stats-value warning">{{ $employee->late ?? 0 }}</h3>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="stats-card">
                                    <div class="stats-icon icon-danger">
                                        <svg width="24" height="24" fill="white" viewBox="0 0 24 24">
                                            <path
                                                d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2zm-7 5h5v5h-5z" />
                                        </svg>
                                    </div>
                                    <div class="stats-label">Total Leaves</div>
                                    <h3 class="stats-value danger">{{ $employee->leave ?? 0 }}</h3>
                                </div>
                            </div>
                        </div>

                        {{-- Monthly Performance --}}
                        <div class="performance-section">
                            <h5 class="subsection-title">Monthly Performance</h5>

                            <div class="row gx-3">
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <div class="stats-card">
                                        <div class="stats-icon icon-success">
                                            <svg width="24" height="24" fill="white" viewBox="0 0 24 24">
                                                <path
                                                    d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z" />
                                            </svg>
                                        </div>
                                        <div class="stats-label">Monthly Sales</div>
                                        <h3 class="stats-value success">{{ $sales ?? 0 }}</h3>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 mb-3">
                                    <div class="stats-card">
                                        <div class="stats-icon icon-success">
                                            <i class="fa-solid fa-bullseye text-white"></i>
                                        </div>
                                        <div class="stats-label">Monthly Target Achieve</div>
                                        <h3 class="stats-value success">${{ number_format($SalePrice, 0) }}</h3>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 mb-3">
                                    <div class="stats-card">
                                        <div class="stats-icon icon-success">
                                            <i class="fa-solid fa-bullseye text-white"></i>
                                        </div>
                                        <div class="stats-label">Current Month Commission</div>
                                        <h3 class="stats-value success">PKR 0</h3>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 mb-3">
                                    <div class="stats-card">
                                        <div class="stats-icon icon-primary">
                                            <svg width="24" height="24" fill="white" viewBox="0 0 24 24">
                                                <path
                                                    d="M20 6h-2.18c.11-.31.18-.65.18-1 0-1.66-1.34-3-3-3-1.05 0-1.96.54-2.5 1.35l-.5.67-.5-.68C10.96 2.54 10.05 2 9 2 7.34 2 6 3.34 6 5c0 .35.07.69.18 1H4c-1.11 0-1.99.89-1.99 2L2 19c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2zm-5-2c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zM9 4c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm11 15H4v-2h16v2zm0-5H4V8h5.08L7 10.83 8.62 12 11 8.76l1-1.36 1 1.36L15.38 12 17 10.83 14.92 8H20v6z" />
                                            </svg>
                                        </div>
                                        <div class="stats-label">Monthly Leads</div>
                                        <h3 class="stats-value primary">{{ $leads ?? 0 }}</h3>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 mb-3">
                                    <div class="stats-card">
                                        <div class="stats-icon icon-info">
                                            <svg width="24" height="24" fill="white" viewBox="0 0 24 24">
                                                <path
                                                    d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z" />
                                            </svg>
                                        </div>
                                        <div class="stats-label">Monthly Trials</div>
                                        <h3 class="stats-value info">{{ $trials ?? 0 }}</h3>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 mb-3">
                                    <div class="stats-card">
                                        <div class="stats-icon icon-success">
                                            <svg width="24" height="24" fill="white" viewBox="0 0 24 24">
                                                <path
                                                    d="M9 11H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2zm2-7h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z" />
                                            </svg>
                                        </div>
                                        <div class="stats-label">Monthly Attendance</div>
                                        <h3 class="stats-value success">{{ $monthlyAttendance ?? 0 }}</h3>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 mb-3">
                                    <div class="stats-card">
                                        <div class="stats-icon icon-warning">
                                            <svg width="24" height="24" fill="white" viewBox="0 0 24 24">
                                                <path
                                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                                            </svg>
                                        </div>
                                        <div class="stats-label">Monthly Half Days</div>
                                        <h3 class="stats-value warning">{{ $monthlyHalfDays ?? 0 }}</h3>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 mb-3">
                                    <div class="stats-card">
                                        <div class="stats-icon icon-warning">
                                            <svg width="24" height="24" fill="white" viewBox="0 0 24 24">
                                                <path
                                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                                            </svg>
                                        </div>
                                        <div class="stats-label">Monthly Absent</div>
                                        <h3 class="stats-value warning">{{ $monthlyAbsent ?? 0 }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Documents Section --}}
                        <div class="performance-section">
                            <h5 class="subsection-title">Documents</h5>

                            {{-- Resume --}}
                            <div class="document-card">
                                <div class="document-info">
                                    <div class="document-icon">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div class="document-details">
                                        <div class="document-title">Resume</div>
                                        <div class="document-status {{ $employee->resume ? 'available' : '' }}">
                                            {{ $employee->resume ? 'Available' : 'No File' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="document-actions">
                                    @if ($employee->resume)
                                        <a href="{{ asset($employee->resume) }}" target="_blank"
                                            class="btn-document btn-view">View</a>
                                        <a href="{{ asset($employee->resume) }}" download
                                            class="btn-document btn-download">Download</a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Offer Letter --}}
                            <div class="document-card">
                                <div class="document-info">
                                    <div class="document-icon">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div class="document-details">
                                        <div class="document-title">Offer Letter</div>
                                        <div class="document-status {{ $employee->offer_letter ? 'available' : '' }}">
                                            {{ $employee->offer_letter ? 'Available' : 'No File' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="document-actions">
                                    @if ($employee->offer_letter)
                                        <a href="{{ asset($employee->offer_letter) }}" target="_blank"
                                            class="btn-document btn-view">View</a>
                                        <a href="{{ asset($employee->offer_letter) }}" download
                                            class="btn-document btn-download">Download</a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </div>
                            </div>

                            {{-- CNIC --}}
                            <div class="document-card">
                                <div class="document-info">
                                    <div class="document-icon">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                        </svg>
                                    </div>
                                    <div class="document-details">
                                        <div class="document-title">CNIC</div>
                                        <div class="document-status {{ $employee->cnic ? 'available' : '' }}">
                                            {{ $employee->cnic ? 'Uploaded' : 'No File' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="document-actions">
                                    @if ($employee->cnic)
                                        <a href="{{ asset($employee->cnic) }}" target="_blank"
                                            class="btn-document btn-view">View</a>
                                        <a href="{{ asset($employee->cnic) }}" download
                                            class="btn-document btn-download">Download</a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- User Info Section --}}
                        {{-- <div class="user-info-section">
                            <h6 class="subsection-title" style="font-size: 1.1rem;">User Information</h6>
                            <table class="user-info-table">
                                <tbody>
                                    <tr>
                                        <td class="table-label">User ID</td>
                                        <td class="table-value">{{ $employee->user->id }}</td>
                                        <td class="table-label">Role</td>
                                        <td class="table-value">{{ $employee->user->role }}</td>
                                    </tr>
                                    <tr>
                                        <td class="table-label">Email</td>
                                        <td class="table-value">{{ $employee->user->email }}</td>
                                        <td class="table-label">IP Address</td>
                                        <td class="table-value">{{ $employee->user->ip_address ?? '—' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div> --}}

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
