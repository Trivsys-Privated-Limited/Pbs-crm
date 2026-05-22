@extends('layout.app')
@extends('admin.nav')
@extends('admin.saidebar')

@section('content')
    <div class="content-wrapper p-4">
        <div class="container-fluid">
            <div class="payslip-container">
                {{-- Company Header --}}
                <div class="company-header">
                    <div class="company-branding">
                        <h1 class="company-name">TRIVSYS</h1>
                        <p class="company-subtitle">(PVT) LTD</p>
                    </div>
                    <div class="document-title">
                        <h2>PAYROLL STATEMENT</h2>
                        <p class="confidential-label">CONFIDENTIAL DOCUMENT</p>
                    </div>
                </div>

                <div class="divider-line"></div>

                {{-- Employee and Period Information --}}
                <div class="info-grid">
                    <div class="info-section">
                        <h3 class="section-heading">EMPLOYEE INFORMATION</h3>
                        <div class="info-row">
                            <span class="info-label">Employee Name:</span>
                            <span class="info-value">{{ $payroll->user->name }}</span>
                        </div>
                        {{-- Uncomment if needed --}}
                        {{-- <div class="info-row">
                            <span class="info-label">Employee ID:</span>
                            <span class="info-value">{{ $payroll->employee->employe_id }}</span>
                        </div> --}}
                    </div>
                    
                    <div class="info-section text-end-custom">
                        <h3 class="section-heading">PAYMENT PERIOD</h3>
                        <div class="info-row">
                            <span class="info-label">Pay Period:</span>
                            <span class="info-value">{{ \Carbon\Carbon::parse($payroll->month)->format('F Y') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Issue Date:</span>
                            <span class="info-value">{{ \Carbon\Carbon::now()->format('F d, Y') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Earnings Section --}}
                <div class="compensation-section">
                    <h3 class="section-title">EARNINGS & DEDUCTIONS</h3>
                    
                    <table class="compensation-table">
                        <thead>
                            <tr>
                                <th class="description-column">Description</th>
                                <th class="amount-column">Amount (PKR)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="earnings-group-header">
                                <td colspan="2"><strong>EARNINGS</strong></td>
                            </tr>
                            <tr>
                                <td class="description-cell">Base Salary</td>
                                <td class="amount-cell positive">{{ number_format($payroll->basic_salary, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="description-cell">Commission & Incentives</td>
                                <td class="amount-cell positive">{{ number_format($payroll->commission, 2) }}</td>
                            </tr>
                            <tr class="subtotal-row">
                                <td class="description-cell"><strong>Gross Earnings</strong></td>
                                <td class="amount-cell"><strong>{{ number_format($payroll->basic_salary + $payroll->commission, 2) }}</strong></td>
                            </tr>
                            
                            <tr class="deductions-group-header">
                                <td colspan="2"><strong>DEDUCTIONS</strong></td>
                            </tr>
                            <tr>
                                <td class="description-cell">Absence Deduction <span class="detail-badge">{{ $payroll->absent_days }} days</span></td>
                                <td class="amount-cell negative">{{ number_format($payroll->absent_deduction, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="description-cell">Late Arrival Deduction <span class="detail-badge">{{ $payroll->late_count }} occurrences</span></td>
                                <td class="amount-cell negative">{{ number_format($payroll->late_deduction, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="description-cell">Advance Payment Recovery</td>
                                <td class="amount-cell negative">{{ number_format($payroll->advance_deduction, 2) }}</td>
                            </tr>
                            <tr class="subtotal-row">
                                <td class="description-cell"><strong>Total Deductions</strong></td>
                                <td class="amount-cell"><strong>{{ number_format($payroll->absent_deduction + $payroll->late_deduction + $payroll->advance_deduction, 2) }}</strong></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="net-salary-row">
                                <td class="description-cell"><strong>NET SALARY PAYABLE</strong></td>
                                <td class="amount-cell"><strong>PKR {{ number_format($payroll->net_salary, 2) }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- Authorization Section --}}
                <div class="authorization-section">
                    <div class="signature-box">
                        <div class="signature-line"></div>
                        <p class="signature-label">Prepared By</p>
                        <p class="signature-note">HR Department</p>
                    </div>
                    <div class="signature-box">
                        <div class="signature-line"></div>
                        <p class="signature-label">Authorized By</p>
                        <p class="signature-note">Finance Director</p>
                    </div>
                </div>

                {{-- Footer Note --}}
                <div class="footer-note">
                    <p>This is a computer-generated document and does not require a physical signature. Please contact HR for any discrepancies.</p>
                </div>

                {{-- Action Buttons --}}
                <div class="action-buttons">
                    <button onclick="window.print()" class="btn-print">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/>
                            <rect x="6" y="14" width="12" height="8"/>
                        </svg>
                        Print Payslip
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        .payslip-container {
            max-width: 900px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07), 0 10px 20px rgba(0, 0, 0, 0.05);
            padding: 48px;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            position: relative;
        }

        .company-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .company-branding {
            margin-bottom: 16px;
        }

        .company-name {
            font-size: 42px;
            font-weight: 700;
            letter-spacing: 4px;
            color: #1a1a1a;
            margin: 0;
            font-family: 'Inter', sans-serif;
        }

        .company-subtitle {
            font-size: 14px;
            font-weight: 500;
            letter-spacing: 3px;
            color: #666;
            margin: 4px 0 0 0;
        }

        .document-title h2 {
            font-size: 20px;
            font-weight: 600;
            color: #2c3e50;
            margin: 16px 0 4px 0;
            letter-spacing: 2px;
        }

        .confidential-label {
            font-size: 11px;
            font-weight: 600;
            color: #e74c3c;
            letter-spacing: 1.5px;
            margin: 0;
        }

        .divider-line {
            height: 3px;
            background: linear-gradient(90deg, #3498db 0%, #2c3e50 100%);
            margin: 32px 0;
            border-radius: 2px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 32px;
            margin-bottom: 40px;
        }

        .info-section {
            background: #f8f9fa;
            padding: 24px;
            border-radius: 8px;
            border-left: 4px solid #3498db;
        }

        .text-end-custom {
            text-align: right;
            border-left: none;
            border-right: 4px solid #2c3e50;
        }

        .section-heading {
            font-size: 12px;
            font-weight: 700;
            color: #2c3e50;
            letter-spacing: 1.5px;
            margin: 0 0 16px 0;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 14px;
        }

        .text-end-custom .info-row {
            flex-direction: row-reverse;
        }

        .info-label {
            color: #7f8c8d;
            font-weight: 500;
        }

        .info-value {
            color: #2c3e50;
            font-weight: 600;
        }

        .compensation-section {
            margin-bottom: 40px;
        }

        .section-title {
            font-size: 16px;
            font-weight: 700;
            color: #2c3e50;
            letter-spacing: 1px;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #ecf0f1;
        }

        .compensation-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
        }

        .compensation-table thead {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
        }

        .compensation-table thead th {
            color: #ffffff;
            font-weight: 600;
            font-size: 13px;
            padding: 16px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .description-column {
            text-align: left;
        }

        .amount-column {
            text-align: right;
            width: 200px;
        }

        .earnings-group-header td,
        .deductions-group-header td {
            background: #f8f9fa;
            padding: 12px 16px;
            font-size: 12px;
            font-weight: 700;
            color: #2c3e50;
            letter-spacing: 1px;
        }

        .deductions-group-header {
            border-top: 2px solid #e0e0e0;
        }

        .compensation-table tbody tr {
            border-bottom: 1px solid #f0f0f0;
        }

        .description-cell {
            padding: 14px 16px;
            color: #2c3e50;
            font-size: 14px;
        }

        .amount-cell {
            padding: 14px 16px;
            text-align: right;
            font-weight: 600;
            font-size: 14px;
            color: #2c3e50;
            font-family: 'Courier New', monospace;
        }

        .amount-cell.positive {
            color: #27ae60;
        }

        .amount-cell.negative {
            color: #e74c3c;
        }

        .detail-badge {
            display: inline-block;
            background: #ecf0f1;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            color: #7f8c8d;
            margin-left: 8px;
        }

        .subtotal-row {
            background: #f8f9fa;
        }

        .subtotal-row td {
            padding: 14px 16px;
            font-size: 14px;
        }

        .net-salary-row {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
        }

        .net-salary-row td {
            color: #ffffff;
            padding: 20px 16px;
            font-size: 16px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .authorization-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 48px;
            margin: 48px 0 32px 0;
        }

        .signature-box {
            text-align: center;
        }

        .signature-line {
            border-bottom: 2px solid #2c3e50;
            margin-bottom: 12px;
            height: 60px;
        }

        .signature-label {
            font-weight: 600;
            color: #2c3e50;
            margin: 0 0 4px 0;
            font-size: 14px;
        }

        .signature-note {
            font-size: 12px;
            color: #7f8c8d;
            margin: 0;
        }

        .footer-note {
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 6px;
            margin-bottom: 24px;
        }

        .footer-note p {
            margin: 0;
            font-size: 12px;
            color: #7f8c8d;
            line-height: 1.6;
        }

        .action-buttons {
            text-align: center;
        }

        .btn-print {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: #ffffff;
            border: none;
            padding: 14px 32px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
        }

        .btn-print:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(52, 152, 219, 0.4);
        }

        .btn-print:active {
            transform: translateY(0);
        }

        @media print {
            body * {
                visibility: hidden;
            }

            .payslip-container,
            .payslip-container * {
                visibility: visible;
            }

            .payslip-container {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                box-shadow: none;
                padding: 24px;
            }

            .action-buttons,
            .btn-print {
                display: none !important;
            }

            .compensation-table {
                page-break-inside: avoid;
            }

            .net-salary-row {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .company-header,
            .divider-line,
            .info-section {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }

        @media (max-width: 768px) {
            .payslip-container {
                padding: 24px;
            }

            .info-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .text-end-custom {
                text-align: left;
                border-right: none;
                border-left: 4px solid #2c3e50;
            }

            .text-end-custom .info-row {
                flex-direction: row;
            }

            .authorization-section {
                grid-template-columns: 1fr;
                gap: 32px;
            }

            .company-name {
                font-size: 32px;
            }

            .amount-column {
                width: 140px;
            }
        }
    </style>
@endsection