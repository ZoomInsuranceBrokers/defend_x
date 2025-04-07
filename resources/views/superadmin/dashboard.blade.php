@extends('superadmin.layouts.app')
@section('title', 'Defend X - Dashboard')
@section('content')
    <style>
        :root {
            --primary-color: white;
            --secondary-color: rgb(61, 68, 73);
            --highlight-color: #3282b8;

            --dt-status-available-color: greenyellow;
            --dt-status-away-color: lightsalmon;
            --dt-status-offline-color: lightgray;

            --dt-padding: 12px;
            --dt-padding-s: 6px;
            --dt-padding-xs: 2px;

            --dt-border-radius: 3px;

            --dt-background-color-container: #2a3338;
            --dt-border-color: var(--secondary-color);
            --dt-bg-color: var(--highlight-color);
            --dt-text-color: var(--primary-color);
            --dt-bg-active-button: var(--highlight-color);
            --dt-text-color-button: var(--primary-color);
            --dt-text-color-active-button: var(--primary-color);
            --dt-hover-cell-color: var(--highlight-color);
            --dt-even-row-color: var(--secondary-color);
            --dt-focus-color: var(--highlight-color);
            --dt-input-background-color: var(--secondary-color);
            --dt-input-color: var(--primary-color);
        }

        .material-icons {
            font-size: 16px;
        }

        .datatable-container {
            font-family: sans-serif;
            background-color: var(--dt-background-color-container);
            border-radius: var(--dt-border-radius);
            color: var(--dt-text-color);
            max-width: 1140px;
            min-width: 950px;
            margin: 0 auto;
            font-size: 12px;
        }

        .datatable-container .header-tools {
            border-bottom: solid 1px var(--dt-border-color);
            padding: var(--dt-padding);
            padding-left: 0;
            display: flex;
            align-items: baseline;
        }

        .datatable-container .header-tools .search {
            width: 30%;
        }

        .datatable-container .header-tools .search .search-input {
            width: 100%;
            height: calc(1.5em + 0.75rem + 2px);
            padding: 0.375rem 0.75rem;
            background-color: var(--dt-input-background-color);
            display: block;
            box-sizing: border-box;
            border-radius: var(--dt-border-radius);
            border: solid 1px var(--dt-border-color);
            color: var(--dt-input-color);
        }

        .datatable-container .header-tools .tools {
            width: 70%;
        }

        .datatable-container .header-tools .tools ul {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: start;
            align-items: baseline;
        }

        .datatable-container .header-tools .tools ul li {
            display: inline-block;
            margin: 0 var(--dt-padding-xs);
            align-items: baseline;
        }

        .datatable-container .footer-tools {
            padding: var(--dt-padding);
            display: flex;
            align-items: baseline;
        }

        .datatable-container .footer-tools .list-items {
            width: 50%;
        }

        .datatable-container .footer-tools .pages {
            margin-left: auto;
            margin-right: 0;
            width: 50%;
        }

        .datatable-container .footer-tools .pages ul {
            margin: 0;
            padding: 0;
            display: flex;
            align-items: baseline;
            justify-content: flex-end;
        }

        .datatable-container .footer-tools .pages ul li {
            display: inline-block;
            margin: 0 var(--dt-padding-xs);
        }

        .datatable-container .footer-tools .pages ul li button,
        .datatable-container .header-tools .tools ul li button {
            color: var(--dt-text-color-button);
            width: 100%;
            box-sizing: border-box;
            border: 0;
            border-radius: var(--dt-border-radius);
            background: transparent;
            cursor: pointer;
        }

        .datatable-container .footer-tools .pages ul li button:hover,
        .datatable-container .header-tools .tools ul li button:hover {
            background: var(--dt-bg-active-button);
            color: var(--dt-text-color-active-button);
        }

        .datatable-container .footer-tools .pages ul li span.active {
            background-color: var(--dt-bg-color);
            border-radius: var(--dt-border-radius);
        }

        .datatable-container .footer-tools .pages ul li button,
        .datatable-container .footer-tools .pages ul li span,
        .datatable-container .header-tools .tools ul li button {
            padding: var(--dt-padding-s) var(--dt-padding);
        }

        .datatable-container .datatable {
            border-collapse: collapse;
            width: 100%;
        }

        .datatable-container .datatable,
        .datatable-container .datatable th,
        .datatable-container .datatable td {
            padding: var(--dt-padding) var(--dt-padding);
        }

        .datatable-container .datatable th {
            font-weight: bolder;
            text-align: left;
            border-bottom: solid 1px var(--dt-border-color);
        }

        .datatable-container .datatable td {
            border-bottom: solid 1px var(--dt-border-color);
        }

        .datatable-container .datatable tbody tr:nth-child(even) {
            background-color: var(--dt-even-row-color);
        }

        .datatable-container .datatable tbody tr:hover {
            background-color: var(--dt-hover-cell-color);
        }

        .datatable-container .datatable tbody tr .available::after,
        .datatable-container .datatable tbody tr .away::after,
        .datatable-container .datatable tbody tr .offline::after {
            display: inline-block;
            vertical-align: middle;
        }

        .datatable-container .datatable tbody tr .available::after {
            content: "Online";
            color: var(--dt-status-available-color);
        }

        .datatable-container .datatable tbody tr .away::after {
            content: "Away";
            color: var(--dt-status-away-color);
        }

        .datatable-container .datatable tbody tr .offline::after {
            content: "Offline";
            color: var(--dt-status-offline-color);
        }

        .datatable-container .datatable tbody tr .available::before,
        .datatable-container .datatable tbody tr .away::before,
        .datatable-container .datatable tbody tr .offline::before {
            content: "";
            display: inline-block;
            width: 10px;
            height: 10px;
            margin-right: 10px;
            border-radius: 50%;
            vertical-align: middle;
        }

        .datatable-container .datatable tbody tr .available::before {
            background-color: var(--dt-status-available-color);
        }

        .datatable-container .datatable tbody tr .away::before {
            background-color: var(--dt-status-away-color);
        }

        .datatable-container .datatable tbody tr .offline::before {
            background-color: var(--dt-status-offline-color);
        }
    </style>
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="datatable-container">
                    <!-- ======= Header tools ======= -->
                    <div class="header-tools">
                        <div class="tools">
                            <h3>Scan Lists</h3>
                        </div>
                        <div class="search">
                            <input type="search" id="search-input" class="search-input" placeholder="Search Company..." />
                        </div>
                    </div>

                    <!-- ======= Table ======= -->
                    <table class="datatable" id="company-table">
                        <thead>
                            <tr>
                                <th>Company</th>
                                <th>Ecosystem(s)</th>
                                <th>Industry</th>
                                <th>Country</th>
                                <th>Last Update</th>
                                <th>Grade</th>
                                <th>Cyber Rating</th>
                                <th>Annualized Impact (DB)</th>
                                <th>Compliance</th>
                                <th>Breach Index</th>
                                <th>Ransomware Index</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($companies as $company)
                                <tr>
                                    <td><a href="{{ route('superadmin.company-summary', $company['CompanyId']) }}">{{ $company['CompanyName'] }}</a></td>
                                    <td>
                                        @foreach ($company['Ecosystems'] as $ecosystem)
                                            {{ $ecosystem['EcosystemName'] }}@if (!$loop->last)
                                                ,
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>{{ $company['Industry']['IndustryName'] }}</td>
                                    <td>{{ $company['Country'] }}</td>
                                    <td>{{ \Carbon\Carbon::parse($company['CyberRating']['CyberRatingLastUpdatedAt'])->format('Y-m-d') }}
                                    </td>
                                    <td>{{ $company['CyberRating']['GradeLetter'] }}</td>
                                    <td>{{ $company['CyberRating']['CyberRating'] }}</td>
                                    <td>
                                        @foreach ($company['FinancialImpacts'] as $impact)
                                            @if ($impact['RiskType'] == 'Data Breach')
                                                ${{ number_format($impact['Rating'], 2) }}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>{{ $company['Compliance']['Rating'] }}</td>
                                    <td>{{ $company['CyberRating']['BreachIndex'] }}</td>
                                    <td>{{ $company['CyberRating']['RansomwareIndex'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- ======= Footer tools ======= -->
                    <div class="footer-tools">
                        <div class="list-items">
                            Show
                            <select name="n-entries" id="n-entries" class="n-entries">
                                <option value="5">5</option>
                                <option value="10" selected>10</option>
                                <option value="15">15</option>
                            </select>
                            entries
                        </div>
                        <div class="pagination" id="pagination-container"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery for Search and Pagination -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let rowsPerPage = parseInt($('#n-entries').val());
            let rows = $('#company-table tbody tr');
            let totalRows = rows.length;
            let totalPages = Math.ceil(totalRows / rowsPerPage);

            function showPage(page) {
                let start = (page - 1) * rowsPerPage;
                let end = start + rowsPerPage;
                rows.hide().slice(start, end).show();
            }

            function updatePagination() {
                $('#pagination-container').empty();
                for (let i = 1; i <= totalPages; i++) {
                    $('#pagination-container').append(`<button class="page-btn" data-page="${i}">${i}</button>`);
                }
            }

            showPage(1);
            updatePagination();

            $(document).on('click', '.page-btn', function() {
                let page = $(this).data('page');
                showPage(page);
            });

            $('#n-entries').change(function() {
                rowsPerPage = parseInt($(this).val());
                totalPages = Math.ceil(totalRows / rowsPerPage);
                showPage(1);
                updatePagination();
            });

            $('#search-input').on('keyup', function() {
                let value = $(this).val().toLowerCase();
                rows.hide().filter(function() {
                    return $(this).find('td:first').text().toLowerCase().includes(value);
                }).show();
            });
        });
    </script>

@endsection
