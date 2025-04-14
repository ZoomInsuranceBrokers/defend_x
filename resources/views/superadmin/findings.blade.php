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
                        <div id="fraudulent-domains-content" class="table-responsive"></div>

                    </div>

                    <!-- Patch Management Tab -->
                    <div class="tab-pane fade" id="patch-management">
                        <p class="text-white">Content for Patch Management</p>
                        <div id="patch-management-content" class="table-responsive"></div>

                    </div>

                    <!-- Application Security Tab -->
                    <div class="tab-pane fade" id="application-security">
                        <p class="text-white">Content for Application Security</p>
                        <div id="application-security-content" class="table-responsive"></div>
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
                            const findings = response.data;
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
            const ransomwareContent = document.getElementById("ransomware-content");
            let isAjaxTriggeredRansomware = false; // Flag to prevent multiple AJAX calls

            ransomwareTab.addEventListener("shown.bs.tab", function() {
                if (!isAjaxTriggeredRansomware) {
                    isAjaxTriggeredRansomware = true; // Set flag to true to prevent further AJAX calls
                    // Show loading message
                    ransomwareContent.innerHTML = '<p class="text-white">Loading data...</p>';

                    // Perform AJAX POST request
                    $.ajax({
                        url: "{{ route('superadmin.ransomware-findings') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}", // Include CSRF token
                            cmp_id: {{ $cmp_id }} // Include cmp_id
                        },
                        success: function(response) {
                            const findings = response.data;
                            if (Array.isArray(findings) && findings.length > 0) {
                                // Parse each 'data' field from JSON string to JavaScript object
                                const parsedFindings = findings.map(finding => JSON.parse(
                                    finding.data));

                                let table = `
                                <table class="table table-bordered table-dark">
                                    <thead class="table-secondary text-dark">
                                        <tr>
                                            <th>Finding ID</th>
                                            <th>Severity</th>
                                            <th>Title</th>
                                            <th>Detail</th>
                                            <th>Domain</th>
                                            <th>Last Check Date</th>
                                            <th>URL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${parsedFindings.map(finding => `
                                                                <tr>
                                                                    <td>${finding.FindingId || 'N/A'}</td>
                                                                    <td>${finding.Severity || 'N/A'}</td>
                                                                    <td>${finding.Title || finding.FindingTitle || 'N/A'}</td>
                                                                    <td>${finding.Detail || finding.Snippet || 'N/A'}</td>
                                                                    <td>${finding.Domain || 'N/A'}</td>
                                                                    <td>${finding.LastCheckDate || 'N/A'}</td>
                                                                    <td>${finding.ShareUrl ? `<a href="${finding.ShareUrl}" target="_blank">${finding.ShareUrl}</a>` : 'N/A'}</td>
                                                                </tr>
                                                            `).join('')}
                                    </tbody>
                                </table>`;

                                // Display the table in the specified container
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
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const fraudulentDomainsTab = document.querySelector('a[href="#fraudulent-domain"]');
            const fraudulentDomainsContent = document.getElementById("fraudulent-domains-content");

            fraudulentDomainsTab.addEventListener("shown.bs.tab", function() {
                if (fraudulentDomainsContent.innerHTML.trim() === "") {
                    fraudulentDomainsContent.innerHTML = '<p class="text-white">Loading data...</p>';

                    $.ajax({
                        url: "{{ route('superadmin.fraudulent-findings') }}",
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        data: {
                            _token: "{{ csrf_token() }}",
                            cmp_id: {{ $cmp_id }},
                        },
                        success: function(response) {
                            const findings = response.data;
                            if (Array.isArray(findings) && findings.length > 0) {
                                let table = `
                            <table class="table table-bordered table-dark">
                                <thead class="table-secondary text-dark">
                                    <tr>
                                        <th>Finding ID</th>
                                        <th>Fraudulent Domain</th>
                                        <th>Severity</th>
                                        <th>Status</th>
                                        <th>Finding Date</th>
                                        <th>Possibility</th>
                                        <th>Registrar</th>
                                        <th>Whois Email</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${findings.map(finding => `
                                                        <tr>
                                                            <td>${finding.FindingId || 'N/A'}</td>
                                                            <td>${finding.FraudulentDomain || 'N/A'}</td>
                                                            <td>${finding.Severity || 'N/A'}</td>
                                                            <td>${finding.Status || 'N/A'}</td>
                                                            <td>${new Date(finding.FindingDate).toLocaleString() || 'N/A'}</td>
                                                            <td>${finding.Possibility || 'N/A'}%</td>
                                                            <td>${finding.Whois?.Registrar || 'N/A'}</td>
                                                            <td>${finding.Whois?.Email || 'N/A'}</td>
                                                            <td>
                                                                ${finding.Ticket?.TicketId
                                                                    ? `<a href="/tickets/${finding.Ticket.TicketId}" target="_blank">View Ticket</a>`
                                                                    : 'No Ticket'}
                                                            </td>
                                                        </tr>`).join('')}
                                </tbody>
                            </table>
                        `;

                                fraudulentDomainsContent.innerHTML = table;
                            } else {
                                fraudulentDomainsContent.innerHTML =
                                    '<p class="text-white">No fraudulent domain findings available.</p>';
                            }
                        },
                        error: function(xhr, status, error) {
                            fraudulentDomainsContent.innerHTML =
                                `<p class="text-danger">Error loading data: ${error}</p>`;
                        }
                    });
                }
            });
        });
    </script>

    <!-- Modal Structure (with fixed height) -->
    <div class="modal fade" id="findingDetailsModal" tabindex="-1" role="dialog"
        aria-labelledby="findingDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="max-height: 500px; overflow-y: auto;">
                <div class="modal-header">
                    <h5 class="modal-title" id="findingDetailsModalLabel">Finding Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modalBodyContent">
                    <!-- Dynamic content will be inserted here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Patch Management Details Div (Initial Empty) -->
    <div id="patch-management-details" class="mt-4">
        <!-- Details will be shown here when the Patch ID is clicked -->
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const patchManagementTab = document.querySelector('a[href="#patch-management"]');
            const patchManagementContent = document.getElementById("patch-management-content");

            patchManagementTab.addEventListener("shown.bs.tab", function() {
                if (patchManagementContent.innerHTML.trim() === "") {
                    patchManagementContent.innerHTML = '<p class="text-white">Loading data...</p>';

                    $.ajax({
                        url: "{{ route('superadmin.patchmanagement-findings') }}",
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        data: {
                            cmp_id: {{ $cmp_id }},
                        },
                        success: function(response) {
                            const findings = response.data;
                            if (Array.isArray(findings) && findings.length > 0) {
                                let table = `
                            <table class="table table-bordered table-dark">
                                <thead class="table-secondary text-dark">
                                    <tr>
                                        <th>Finding ID</th>
                                        <th>Domain</th>
                                        <th>IP Address</th>
                                        <th>Severity</th>
                                        <th>Status</th>
                                        <th>Finding Date</th>
                                        <th>CVSS Score</th>
                                        <th>Product</th>
                                        <th>Ticket</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${findings.map(finding => `
                                                <tr>
                                                    <td><a href="javascript:void(0);" class="patch-id-link" data-finding-id="${finding.FindingId}" data-finding="${JSON.stringify(finding)}">${finding.FindingId || 'N/A'}</a></td>
                                                    <td>${finding.Domain || 'N/A'}</td>
                                                    <td>${finding.IPAddress || 'N/A'}</td>
                                                    <td>${finding.Severity || 'N/A'}</td>
                                                    <td>${finding.Status || 'N/A'}</td>
                                                    <td>${finding.FindingDate ? new Date(finding.FindingDate).toLocaleString() : 'N/A'}</td>
                                                    <td>${finding.CvssScore || 'N/A'}</td>
                                                    <td>${finding.ProductName || 'N/A'}</td>
                                                    <td>
                                                        ${finding.Ticket?.TicketId
                                                            ? `<a href="/tickets/${finding.Ticket.TicketId}" target="_blank">View Ticket</a>`
                                                            : 'No Ticket'}
                                                    </td>
                                                </tr>`).join('')}
                                </tbody>
                            </table>
                        `;

                                patchManagementContent.innerHTML = table;

                                // Add event listener for clicking on Patch ID
                                const patchIdLinks = document.querySelectorAll(
                                    '.patch-id-link');
                                patchIdLinks.forEach(link => {
                                    link.addEventListener('click', function() {
                                        const finding = JSON.parse(this
                                            .getAttribute('data-finding'));

                                        let detailsDiv = `
                                        <div class="patch-details-card" id="details-${finding.FindingId}">
                                            <h4>Finding ID: ${finding.FindingId || 'N/A'}</h4>
                                            <p><strong>Domain:</strong> ${finding.Domain || 'N/A'}</p>
                                            <p><strong>IP Address:</strong> ${finding.IPAddress || 'N/A'}</p>
                                            <p><strong>Severity:</strong> ${finding.Severity || 'N/A'}</p>
                                            <p><strong>Status:</strong> ${finding.Status || 'N/A'}</p>
                                            <p><strong>Finding Date:</strong> ${finding.FindingDate ? new Date(finding.FindingDate).toLocaleString() : 'N/A'}</p>
                                            <p><strong>CVSS Score:</strong> ${finding.CvssScore || 'N/A'}</p>
                                            <p><strong>Product:</strong> ${finding.ProductName || 'N/A'}</p>
                                            <p><strong>Ticket:</strong> ${finding.Ticket?.TicketId
                                                ? `<a href="/tickets/${finding.Ticket.TicketId}" target="_blank">View Ticket</a>`
                                                : 'No Ticket'}</p>
                                        </div>
                                    `;

                                        // Insert the details into the patch-management-details div
                                        document.getElementById(
                                                "patch-management-details")
                                            .innerHTML = detailsDiv;

                                        // Optionally, open the modal with fixed height
                                        $('#findingDetailsModal').modal('show');
                                    });
                                });

                            } else {
                                patchManagementContent.innerHTML =
                                    '<p class="text-white">No patch management findings available.</p>';
                            }
                        },
                        error: function(xhr, status, error) {
                            patchManagementContent.innerHTML =
                                `<p class="text-danger">Error loading data: ${error}</p>`;
                        }
                    });
                }
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const applicationSecurityTab = document.querySelector('a[href="#application-security"]');
            const applicationSecurityContent = document.getElementById("application-security-content");

            applicationSecurityTab.addEventListener("shown.bs.tab", function() {
                if (applicationSecurityContent.innerHTML.trim() === "") {
                    applicationSecurityContent.innerHTML = '<p class="text-white">Loading data...</p>';

                    $.ajax({
                        url: "{{ route('superadmin.applicationsecurity-findings') }}",
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        data: {
                            _token: "{{ csrf_token() }}",
                            cmp_id: {{ $cmp_id }},
                        },
                        success: function(response) {
                            const findings = response.data;
                            if (Array.isArray(findings) && findings.length > 0) {
                                let table = `
                            <table class="table table-bordered table-dark">
                                <thead class="table-secondary text-dark">
                                    <tr>
                                        <th>Finding ID</th>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Severity</th>
                                        <th>Control ID</th>
                                        <th>Domain</th>
                                        <th>Finding Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${findings.map(finding => `
                                            <tr>
                                                <td>${finding.FindingId || 'N/A'}</td>
                                                <td>${finding.Title || 'N/A'}</td>
                                                <td>${finding.Status || 'N/A'}</td>
                                                <td>${finding.Severity || 'N/A'}</td>
                                                <td>${finding.ControlId || 'N/A'}</td>
                                                <td>${finding.Domain || 'N/A'}</td>
                                                <td>${new Date(finding.FindingDate).toLocaleString() || 'N/A'}</td>
                                            </tr>`).join('')}
                                </tbody>
                            </table>
                            `;

                                applicationSecurityContent.innerHTML = table;
                            } else {
                                applicationSecurityContent.innerHTML =
                                    '<p class="text-white">No application security findings available.</p>';
                            }
                        },
                        error: function(xhr, status, error) {
                            applicationSecurityContent.innerHTML =
                                `<p class="text-danger">Error loading data: ${error}</p>`;
                        }
                    });
                }
            });
        });
    </script>


@endsection
