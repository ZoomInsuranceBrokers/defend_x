@extends('superadmin.layouts.app')
@section('title', 'Defend X - Findings')

@section('content')
    <style>
        /* Custom Styling for Dark Mode */
        .nav-tabs .nav-link.active {
            background-color: #495057 !important;
            border-color: #6c757d !important;
        }

        .dataTables_wrapper .dataTables_filter input {
            background-color: #343a40;
            color: #fff;
            border: 1px solid #6c757d;
            border-radius: 5px;
            padding: 5px 10px;
        }

        .dataTables_wrapper .dataTables_filter label {
            color: #fff;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            color: #fff !important;
            background: #343a40 !important;
            border: 1px solid #6c757d !important;
            border-radius: 5px;
            padding: 5px 10px;
            margin: 2px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #495057 !important;
        }

        .dataTables_wrapper .dataTables_info {
            color: #fff;
        }

        .dataTables_wrapper .dataTables_length select {
            background-color: #343a40;
            color: #fff;
            border: 1px solid #6c757d;
            border-radius: 5px;
            padding: 5px;
        }
    </style>
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <h1 class="my-4 text-white">Findings</h1>

                <!-- Navbar -->
                <ul class="nav nav-tabs bg-dark p-2 rounded">
                    <li class="nav-item">
                        <a class="nav-link active text-white" href="#all-findings" data-bs-toggle="tab">
                            All Findings
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#data-breach" id="data-breach-click" data-bs-toggle="tab">
                            Data Breach
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#ransomware" data-bs-toggle="tab">
                            Ransomware
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#fraudulent-domain" data-bs-toggle="tab">
                            Fraudulent Domain
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#patch-management" data-bs-toggle="tab">
                            Patch Management
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#application-security" data-bs-toggle="tab">
                            Application Security
                        </a>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content mt-4">
                    <!-- All Findings Tab -->
                    <div class="tab-pane fade show active" id="all-findings">
                        <div class="table-responsive">
                            <table id="all-findings-table" class="table table-bordered table-hover table-dark">
                                <thead class="table-secondary text-dark">
                                    <tr>
                                        <th>Finding ID</th>
                                        <th>Status</th>
                                        <th>First Seen Date</th>
                                        <th>Update Date</th>
                                        <th>Module</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!empty($data['findings']))
                                        @foreach ($data['findings'] as $finding)
                                            <tr>
                                                <td>{{ $finding['FindingId'] }}</td>
                                                <td>{{ $finding['Status'] }}</td>
                                                <td>{{ $finding['FirstSeenDate'] }}</td>
                                                <td>{{ $finding['UpdateDate'] }}</td>
                                                <td>{{ $finding['Module'] }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center">No findings available</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Data Breach Tab -->
                    <div class="tab-pane fade" id="data-breach">
                        <p class="text-white">Content for Data Breach</p>
                        <div id="data-breach-content" class="table-responsive"></div>
                    </div>

                    <!-- Ransomware Tab -->
                    <div class="tab-pane fade" id="ransomware">
                        <p class="text-white">Content for Ransomware</p>
                        <div id="ransomware-content" class="table-responsive"></div>

                    </div>

                    <!-- Fraudulent Domain Tab -->
                    <div class="tab-pane fade" id="fraudulent-domain">
                        <p class="text-white">Content for Fraudulent Domain</p>
                    </div>

                    <!-- Patch Management Tab -->
                    <div class="tab-pane fade" id="patch-management">
                        <p class="text-white">Content for Patch Management</p>
                    </div>

                    <!-- Application Security Tab -->
                    <div class="tab-pane fade" id="application-security">
                        <p class="text-white">Content for Application Security</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (window.DataTable) {
                new DataTable('#all-findings-table', {
                    paging: true,
                    searching: true,
                    ordering: true,
                    info: true
                });
            } else {
                console.error("DataTables library is not loaded.");
            }
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const dataBreachTab = document.querySelector('a[href="#data-breach"]');
            const dataBreachContent = document.getElementById("data-breach-content");
            let isAjaxTriggered = false; // Flag to prevent multiple AJAX calls

            dataBreachTab.addEventListener("shown.bs.tab", function() {
                if (!isAjaxTriggered) {
                    isAjaxTriggered = true; // Set flag to true to prevent further AJAX calls
                    // Show loading message
                    dataBreachContent.innerHTML = '<p class="text-white">Loading data...</p>';

                    // Perform AJAX POST request
                    $.ajax({
                        url: "{{ route('superadmin.data-breach-finding') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}", // Include CSRF token
                            cmp_id: {{ $cmp_id }} // Include cmp_id
                        },
                        success: function(response) {
                            const findings = response
                                .data; // Adjust to match your API response structure
                            if (Array.isArray(findings) && findings.length > 0) {
                                // Parse each 'data' field from JSON string to JavaScript object
                                const parsedFindings = findings.map(finding => JSON.parse(
                                    finding.data));

                                let table = `
                                    <table class="table table-bordered table-dark">
                                        <thead class="table-secondary text-dark">
                                            <tr>
                                                <th>Finding ID</th>
                                                <th>Status</th>
                                                <th>Severity</th>
                                                <th>Domain</th>
                                                <th>Finding Title</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${parsedFindings.map(finding => `
                                                            <tr>
                                                                <td>${finding.FindingId || 'N/A'}</td>
                                                                <td>${finding.Status || 'N/A'}</td>
                                                                <td>${finding.Severity || 'N/A'}</td>
                                                                <td>${finding.Domain || 'N/A'}</td>
                                                                <td>${finding.FindingTitle || 'N/A'}</td>
                                                            </tr>
                                                        `).join('')}
                                        </tbody>
                                    </table>`;

                                // Display the table in the specified container
                                dataBreachContent.innerHTML = table;
                            } else {
                                dataBreachContent.innerHTML =
                                    '<p class="text-white">No findings available.</p>';
                            }
                        },

                        error: function(xhr, status, error) {
                            dataBreachContent.innerHTML =
                                `<p class="text-danger">Error loading data: ${error}</p>`;
                        }
                    });
                }
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ransomwareTab = document.querySelector('a[href="#ransomware"]');
            const ransomwareContent = document.getElementById("ransomware-content"); // Fix the content ID.

            ransomwareTab.addEventListener("shown.bs.tab", function() {
                if (ransomwareContent.innerHTML.trim() === "") {
                    // Show loading message
                    ransomwareContent.innerHTML = '<p class="text-white">Loading data...</p>';

                    // Perform AJAX POST request
                    $.ajax({
                        url: "{{ route('superadmin.ransomware-findings') }}",
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            cmp_id: {{ $cmp_id }} // Include cmp_id
                        }, // Include CSRF token as header
                        data: {
                            _token: "{{ csrf_token() }}", // Optional, already in headers
                        },
                        success: function(response) {
                            if (response.success && Array.isArray(response.data) && response
                                .data.length > 0) {
                                const findings = response.data;

                                let table = `
                            <table class="table table-bordered table-dark">
                                <thead class="table-secondary text-dark">
                                    <tr>
                                        <th>Finding ID</th>
                                        <th>Module</th>
                                        <th>URL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${findings.map(finding => `
                                            <tr>
                                                <td>${finding.FindingId || 'N/A'}</td>
                                                <td>${finding.Module || 'N/A'}</td>
                                                <td><a href="${finding.Url}" target="_blank">${finding.Url}</a></td>
                                            </tr>
                                        `).join('')}
                                </tbody>
                            </table>`;

                                ransomwareContent.innerHTML = table;
                            } else {
                                ransomwareContent.innerHTML =
                                    '<p class="text-white">No ransomware findings available.</p>';
                            }
                        },
                        error: function(xhr, status, error) {
                            ransomwareContent.innerHTML =
                                `<p class="text-danger">Error loading data: ${error}</p>`;
                        }
                    });
                }
            });
        });
    </script>
@endsection
