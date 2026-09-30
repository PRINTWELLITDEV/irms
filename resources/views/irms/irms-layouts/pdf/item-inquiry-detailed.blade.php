<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Item Inquiry - Detailed Report</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

    <style>
        @page {
            margin: 28px;
        }

        :root {
            --brand: #4A5568;
            --brand-light: #eef1f8;
            --text-muted: #6c757d;
            --border-color: #dee2e6;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9px;
            color: #2b2b2b;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* ---------- Header ---------- */
        .header {
            text-align: center;
            border-bottom: 2px solid #4A5568;
            padding-bottom: 10px;
            margin-bottom: 14px;
        }
        .header .site-name {
            font-family: 'Times New Roman', Times, serif;
            font-size: 19px;
            font-weight: bold;
            font-style: Italic;
            color: #1c1c1c;
            margin: 0;
        }

        .header .title {
            font-size: 11px;
            /* font-weight: bold; */
            color: var(--brand);
            margin-top: 3px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .report-header .company {
            font-size: 11px;
            font-weight: 700;
            color: var(--brand);
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }


        .report-header .subtitle {
            font-size: 10px;
            color: var(--text-muted);
        }

        /* ---------- Filter summary bar ---------- */
        .filter-bar {
            padding: 8px 12px;
            margin-bottom: 14px;
            font-size: 9px;
        }

        .filter-bar .filter-item {
            display: inline-block;
            margin-right: 22px;
        }

        .filter-bar .filter-label {
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 7.5px;
            letter-spacing: 0.4px;
            display: block;
        }

        .filter-bar .filter-value {
            font-weight: 600;
            color: #1c1c1c;
            font-size: 9.5px;
        }

        /* ---------- Table ---------- */
        table.report-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
        }

        table.report-table thead th {
            background-color: var(--brand);
            color: #fff;
            border: 1px solid var(--brand);
            padding: 7px 6px;
            text-align: left;
            font-size: 8px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        table.report-table tbody td {
            border: 1px solid var(--border-color);
            padding: 6px;
            vertical-align: middle;
        }

        table.report-table tbody tr:nth-child(even) {
            background-color: #f7f8fb;
        }

        .text-end,
        .qty {
            text-align: right !important;
        }

        .text-center {
            text-align: center !important;
        }

        /* Status badges */
        .badge-status {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 10px;
            font-size: 7.5px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .badge-active {
            background-color: #d4edda;
            color: #155724;
        }

        .badge-inactive {
            background-color: #f8d7da;
            color: #721c24;
        }

        .badge-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .badge-default {
            background-color: #e2e3e5;
            color: #383d41;
        }

        .empty-state {
            text-align: center;
            padding: 24px 0;
            color: var(--text-muted);
            font-style: italic;
        }

        /* ---------- Footer ---------- */
        .report-footer {
            margin-top: 16px;
            padding-top: 8px;
            border-top: 1px solid var(--border-color);
            font-size: 8px;
            color: var(--text-muted);
            display: table;
            width: 100%;
        }

        .report-footer .footer-left {
            display: table-cell;
            text-align: left;
        }

        .report-footer .footer-right {
            display: table-cell;
            text-align: right;
        }
    </style>
</head>

<body>
<div class="header">
    <div class="site-name">
        {{ $site_desc }}
    </div>

    <div class="title">
        Item Inquiry &ndash; Detailed Report
    </div>
</div>

    <table class="report-table">
        <thead>
            <tr>
                <th>Warehouse</th>
                <th>CO</th>
                <th>Item</th>
                <th>Item Description</th>
                <th>U/M</th>
                <th class="text-end">Qty</th>
                <th>Date Received</th>
                <th>Location</th>

            </tr>
        </thead>

        <tbody>
            @forelse($data as $row)
                <tr>
                    <td>{{ $row->warehouse }}</td>
                    <td>{{ $row->co }}</td>
                    <td>{{ $row->item }}</td>
                    <td>{{ $row->item_description }}</td>
                    <td>{{ $row->um }}</td>
                    <td class="text-end">{{ number_format((float) $row->qty, 2) }}</td>
                    <td>
                        @if($row->date_received)
                            {{ \Carbon\Carbon::parse($row->date_received)->format('M d, Y') }}
                        @endif
                    </td>
                    <td>{{ $row->location }}</td>

                </tr>
            @empty
                <tr>
                    <td colspan="9" class="empty-state">No records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>


</body>
</html>