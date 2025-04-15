@extends('superadmin.layouts.app')
@section('title', 'Defend X - Compliance')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Select Compliance Standard</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="complianceStandard">Compliance Standards</label>
                                <select name="compliance_standard" id="complianceStandard" class="form-control">
                                    <option value="" disabled selected>Select a standard</option>
                                    @foreach ($data['getcompliance_standard_response'] as $standard)
                                        <option value="{{ $standard['Standard'] }}">
                                            {{ $standard['Standard'] }} -
                                            {{ \Illuminate\Support\Str::limit($standard['Description'], 100) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="button" id="searchButton" class="btn btn-primary">Search</button>

                            <hr>
                            <ul class="nav nav-tabs justify-content-center mt-4" id="complianceTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="findings-tab" href="#" data-target="#findings"
                                        role="tab">Compliance Findings</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="area-tab" href="#" data-target="#area"
                                        role="tab">Compliance Area</a>
                                </li>
                            </ul>
                            <div class="tab-content mt-3" id="complianceTabsContent">
                                <!-- Compliance Findings Tab -->
                                <div class="tab-pane active" id="findings" role="tabpanel" class="table-container">
                                    <h5>Compliance Findings</h5>
                                    <p>Details or content related to compliance findings go here.</p>
                                    <!-- Table will be inserted here dynamically -->
                                </div>
                                <!-- Compliance Area Tab -->
                                <div class="tab-pane" id="area" role="tabpanel" class="table-container">
                                    <h5>Compliance Area</h5>
                                    <p>Details or content related to compliance areas go here.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<style>
    /* Dark theme table styling */
    table.table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        font-family: 'Arial', sans-serif;
        background-color: #2c2f36;
        /* Dark background for the table */
        color: #f4f4f4;
        overflow-x: auto;
        /* Enable horizontal scrolling */
        width: 100%;
        /* Ensure the container takes full width */
        /* Light text color for contrast */
    }

    /* Table headers */
    table.table th {
        background-color: #444;
        /* Darker gray for headers */
        color: #e1e1e1;
        /* Lighter gray for header text */
        font-size: 14px;
        text-align: left;
        padding: 12px 15px;
        font-weight: bold;
    }

    /* Table rows */
    table.table td {
        padding: 12px 15px;
        font-size: 13px;
        color: #f4f4f4;
        /* Light text */
        border-bottom: 1px solid #555;
        /* Subtle divider */
        word-wrap: break-word;
        /* Allow wrapping of long words */
        max-width: 200px;
        /* Set max width for description */
    }

    /* Table row hover effect */
    table.table tr:hover {
        background-color: #363c42;
        /* Slightly lighter dark background on hover */
    }

    /* Striped rows */
    table.table tbody tr:nth-child(odd) {
        background-color: #333;
        /* Dark gray for odd rows */
    }

    table.table tbody tr:nth-child(even) {
        background-color: #292f35;
        /* Darker gray for even rows */
    }

    /* Add scroll to long descriptions */
    .description {
        max-width: 300px;
        /* Set maximum width */
        white-space: normal;
        /* Allow description to wrap */
        overflow: hidden;
        word-wrap: break-word;
        /* Break words if necessary */
    }

    /* If the description is too long, it will be shown below the table row */
    table.table td.description {
        max-width: 100%;
        /* No limit on width for description */
        display: block;
        /* Make the description text block-level so it flows below */
    }

    /* Pagination styling */
    .dataTables_paginate {
        margin-top: 20px;
        text-align: center;
    }

    .dataTables_paginate .paginate_button {
        padding: 5px 10px;
        margin: 0 5px;
        background-color: #444;
        /* Dark background for pagination buttons */
        color: #f4f4f4;
        /* Light text color */
        border-radius: 5px;
        border: none;
        cursor: pointer;
    }

    .dataTables_paginate .paginate_button:hover {
        background-color: #555;
        /* Slightly lighter gray when hovering over the pagination button */
    }

    /* Search input styling */
    .dataTables_filter input {
        border: 1px solid #555;
        /* Dark border */
        border-radius: 5px;
        padding: 5px 10px;
        margin-left: 10px;
        background-color: #333;
        /* Dark background for the search input */
        color: #f4f4f4;
        /* Light text inside search box */
    }
</style>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {

        // Handle tab switching
        $('.nav-tabs .nav-link').on('click', function(e) {
            e.preventDefault();

            // Remove active class from all tabs and tab contents
            $('.nav-tabs .nav-link').removeClass('active');
            $('.tab-pane').removeClass('active');

            // Add active class to the clicked tab and its content
            $(this).addClass('active');
            const target = $(this).data('target');
            $(target).addClass('active');
        });
    });
</script>
<script>
    $(document).ready(function() {
        function initializeDataTable(tableId) {
            $(`#${tableId}`).DataTable({
                "paging": true, // Enable pagination
                "searching": true, // Enable searching
                "lengthChange": false, // Disable the ability to change the number of records per page
                "pageLength": 15, // Set the number of records per page
                "ordering": false, // Disable ordering
                "stripeClasses": ['odd', 'even'], // Add striped rows
                "responsive": true // Make the table responsive on small screens
            });
        }

        $('#searchButton').on('click', function() {
            // Get the selected value
            const complianceStandard = $('#complianceStandard').val();

            // Ensure a value is selected
            if (!complianceStandard) {
                alert('Please select a standard.');
                return;
            }

            // Send AJAX request
            $.ajax({
                url: '{{ route('superadmin.compliance.search') }}', // Replace with your route name
                type: 'POST',
                data: {
                    compliance_standard: complianceStandard,
                    cmp_id: {{ $cmp_id }},
                    _token: '{{ csrf_token() }}' // Include CSRF token for security
                },
                success: function(response) {
                    try {
                        const findingsDiv = $('#findings');
                        findingsDiv.empty(); // Clear existing content
                        findingsDiv.append('<h5>Compliance Findings</h5>');

                        const areasDiv = $('#area');
                        areasDiv.empty(); // Clear existing content
                        areasDiv.append('<h5>Compliance Areas</h5>');

                        let data = response.data.data;
                        let areas = response.areas.data;

                        // Parse data and areas if they are strings
                        if (typeof data === 'string') {
                            data = JSON.parse(data);
                        }
                        if (typeof areas === 'string') {
                            areas = JSON.parse(areas);
                        }

                        // Process Compliance Findings
                        if (Array.isArray(data)) {
                            let findingsTable = $('<table>')
                                .addClass('table table-bordered table-striped')
                                .attr('id', 'findingsTable');

                            let findingsThead = `
                                <thead>
                                    <tr>
                                        <th>Control ID</th>
                                        <th>Area</th>
                                        <th>Item ID</th>
                                        <th>Description</th>
                                        <th>Comment</th>
                                        <th>Confidence</th>
                                        <th>Result</th>
                                        <th>Percentage</th>
                                        <th>Recommendation</th>
                                        <th>Business Risk</th>
                                    </tr>
                                </thead>
                            `;
                            findingsTable.append(findingsThead);

                            let findingsTbody = $('<tbody>');
                            data.forEach(item => {
                                let row = `
                                    <tr>
                                        <td>${item.ControlId}</td>
                                        <td>${item.Area}</td>
                                        <td>${item.ItemId}</td>
                                        <td>${item.Description}</td>
                                        <td>${item.Comment}</td>
                                        <td>${item.Confidence}</td>
                                        <td>${item.Result}</td>
                                        <td>${item.Percentage}%</td>
                                        <td>${item.Recommendation}</td>
                                        <td>${item.BusinessRisk}</td>
                                    </tr>
                                `;
                                findingsTbody.append(row);
                            });

                            findingsTable.append(findingsTbody);
                            findingsDiv.append(findingsTable);
                            initializeDataTable(
                            'findingsTable'); // Initialize DataTable for findings
                        }

                        // Process Compliance Areas
                        if (Array.isArray(areas)) {
                            let areasTable = $('<table>')
                                .addClass('table table-bordered table-striped')
                                .attr('id', 'areasTable');

                            let areasThead = `
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Compliance</th>
                                        <th>Completeness</th>
                                    </tr>
                                </thead>
                            `;
                            areasTable.append(areasThead);

                            let areasTbody = $('<tbody>');
                            areas.forEach(area => {
                                let row = `
                                    <tr>
                                        <td>${area.Name}</td>
                                        <td>${area.Compliance}</td>
                                        <td>${area.Completeness ?? 'N/A'}</td>
                                    </tr>
                                `;
                                areasTbody.append(row);
                            });

                            areasTable.append(areasTbody);
                            areasDiv.append(areasTable);
                            initializeDataTable(
                            'areasTable'); // Initialize DataTable for areas
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        $('#findings').html(
                            '<p>Error processing compliance data. Please try again later.</p>'
                            );
                        $('#area').html(
                            '<p>Error processing compliance areas. Please try again later.</p>'
                            );
                    }
                },
                error: function(xhr) {
                    console.error('Error:', xhr.responseText);
                    $('#findings').html(
                        '<p>Error retrieving compliance data. Please try again later.</p>'
                        );
                }
            });
        });
    });
</script>
