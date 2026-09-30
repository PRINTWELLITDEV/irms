<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Item Inquiry - Summary Report</title>

    <style>
        @page {
            margin: 25px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9px;
            color: #2b2b2b;
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
            color: #4A5568;
            margin-top: 3px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }
        .header .company {
            font-size: 11px;
            font-weight: bold;
            color: #4A5568;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

 
        .header .subtitle {
            font-size: 10px;
            color: #6c757d;
        }

        /* ---------- Info / total row ---------- */
        .info-row {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        .info-row td {
            border: none;
            vertical-align: middle;
        }

        .info-box {

            padding: 8px 12px;
        }

        .info-box table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-box td {
            border: none;
            padding: 0 22px 0 0;
        }

        .info-label {
            color: #6c757d;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 7.5px;
            letter-spacing: 0.4px;
            display: block;
        }

        .info-value {
            font-weight: bold;
            color: #1c1c1c;
            font-size: 9.5px;
        }

        .total-cell {
            width: 150px;
            padding-left: 10px;
        }

        .total-box {
            background-color: #4A5568;
            border: 1px solid #4A5568;
            border-radius: 4px;
            padding: 7px 10px;
            text-align: right;
        }

        .total-label {
            font-size: 7.5px;
            color: #cdd6ea;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .total-value {
            font-size: 14px;
            font-weight: bold;
            color: #ffffff;
        }

        /* ---------- Table ---------- */
        table.report-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
        }

        .report-table th {
            background-color: #4A5568;
            color: #ffffff;
            border: 1px solid #4A5568;
            padding: 7px 6px;
            text-align: left;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .report-table td {
            border: 1px solid #c8cdd2;
            padding: 6px;
            vertical-align: middle;
        }

        .report-table tbody tr:nth-child(even) {
            background-color: #f7f8fb;
        }

        .qty {
            text-align: right;
            white-space: nowrap;
        }

        .center {
            text-align: center;
        }

        /* Status badges */
        .badge-status {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 10px;
            font-size: 7.5px;
            font-weight: bold;
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
            color: #6c757d;
            font-style: italic;
        }

        /* ---------- Footer ---------- */
        .footer {
            margin-top: 16px;
            padding-top: 8px;
            border-top: 1px solid #ddd;
            font-size: 8px;
            color: #6c757d;
            display: table;
            width: 100%;
        }

        .footer .footer-left {
            display: table-cell;
            text-align: left;
        }

        .footer .footer-right {
            display: table-cell;
            text-align: right;
        }
    </style>
</head>

<body>

@php
    $totalQty = $data->sum('qty');
@endphp

<div class="header">
    <div class="site-name">
        {{ $site_desc }}
    </div>

    <div class="title">
        Item Inquiry &ndash; Summary Report
    </div>
</div>


<!-- SUMMARY TABLE -->
<table class="report-table">

    <thead>
        <tr>
            <th>Warehouse</th>
            <th>CO</th>
            <th>Item</th>
            <th>Item Description</th>
            <th>U/M</th>
            <th class="qty">Qty</th>
            <th class="center">Status</th>
            <th>Bay No.</th>
            


        </tr>
    </thead>

    <tbody>

        @forelse($data as $row)
            @php
                $statusLower = strtolower($row->status ?? '');
                $badgeClass = match(true) {
                    str_contains($statusLower, 'active') && !str_contains($statusLower, 'inactive') => 'badge-active',
                    str_contains($statusLower, 'inactive') => 'badge-inactive',
                    str_contains($statusLower, 'pending') => 'badge-pending',
                    default => 'badge-default',
                };
            @endphp
            <tr>
                <td>{{ $row->warehouse }}</td>
                <td>{{ $row->co }}</td>
                <td>{{ $row->item }}</td>
                <td>{{ $row->item_description }}</td>
                <td>{{ $row->um }}</td>
                <td class="qty">{{ number_format((float) $row->qty, 2) }}</td>
                <td class="center">{{ $row->status }}</td>
                <td>{{ $row->bay_no }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="empty-state">No records found.</td>
            </tr>
        @endforelse

    </tbody>

</table>

<!-- <div class="footer">
    <div class="footer-left">
        <div>
            IRMS &mdash; Item Inquiry Summary Report
        </div>

        <div style="margin-top: 3px;">
            PRINT DATE: {{ now()->format('F d, Y g:i A') }}
            &nbsp;&nbsp;|&nbsp;&nbsp;
            PRINTED BY: {{ auth()->user()->name ?? auth()->user()->userid }}
        </div>
    </div>
</div> -->
</body>
</html>