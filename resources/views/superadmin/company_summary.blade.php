@extends('superadmin.layouts.app')
@section('title', 'Defend X - Company Summary')
@section('content')
    <style>
        .card.active {
            border: 2px solid #007bff;
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.5);
        }
    </style>
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-12 mb-3">
                    <form action="{{ route('superadmin.report-section', ['id' => $cmp_id]) }}" method="GET">
                        @csrf
                        <input type="hidden" name="cmp_id" value="{{ $cmp_id }}">
                        <button type="submit" class="btn btn-success float-start">
                            Get All Reports And Findings
                        </button>
                    </form>

                    {{-- <form action="{{ route('superadmin.widget-section', ['id' => $cmp_id]) }}" method="GET">
                        @csrf
                        <input type="hidden" name="cmp_id" value="{{ $cmp_id }}">
                        <button type="submit" class="btn btn-warning float-start">
                            Visual Reports
                        </button>
                    </form> --}}

                </div>

                <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                    <div class="card active position-relative" data-target="technical">
                        <div class="card-body">
                            <h3 class="mb-0">Technical Summary</h3>
                            <p class="text-success ms-2 mb-0 font-weight-medium">Grade Rating
                                {{ $summary['TechnicalSummary']['GradeLetter'] ?? 'NA' }}</p>
                            <h6 class="text-muted font-weight-normal">Cyber Rating
                                {{ $summary['TechnicalSummary']['CyberRating'] ?? 'NA' }}</h6>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                    <div class="card" data-target="compliance">
                        <div class="card-body">
                            <h3 class="mb-0">Compliance Summary</h3>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                    <div class="card" data-target="financial">
                        <div class="card-body">
                            <h3 class="mb-0">Financial Summary</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <div id="technical" class="content-section">
                    <div class="container">
                        <h2 class="mb-4">Company Summary - {{ $summary['DomainName'] }}</h2>

                        <!-- Cyber Rating Overview -->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5>Cyber Rating</h5>
                                        <p class="text-primary">{{ $summary['TechnicalSummary']['CyberRating'] }}
                                            ({{ $summary['TechnicalSummary']['CyberRatingAsString'] }})</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5>Overall Grade</h5>
                                        <p class="text-success">{{ $summary['TechnicalSummary']['GradeLetter'] }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Chart for Different Ratings -->
                        <canvas id="cyberChart"></canvas>

                        <!-- SafeGuard Table -->
                        <!-- SafeGuard Section -->
                        <h3 class="mt-4">SafeGuard</h3>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Cyber Rating</th>
                                    <th>Grade</th>
                                    <th>Last Updated</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($summary['TechnicalSummary']['SafeGuard'] as $key => $value)
                                    @if (is_array($value))
                                        <tr>
                                            <td>{{ $key }}</td>
                                            <td>{{ $value['CyberRating'] }}</td>
                                            <td>{{ $value['GradeLetter'] }}</td>
                                            <td>{{ $value['LastUpdatedAt'] }}</td>
                                            <td>
                                                @if (!empty($value['Description']))
                                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                        data-bs-target="#descriptionModal"
                                                        data-desc="{{ $value['Description'] }}">
                                                        View Description
                                                    </button>
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Reputation Section -->
                        <h3 class="mt-4">Reputation</h3>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Cyber Rating</th>
                                    <th>Grade</th>
                                    <th>Last Updated</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($summary['TechnicalSummary']['Reputation'] as $key => $value)
                                    @if (is_array($value))
                                        <tr>
                                            <td>{{ $key }}</td>
                                            <td>{{ $value['CyberRating'] }}</td>
                                            <td>{{ $value['GradeLetter'] }}</td>
                                            <td>{{ $value['LastUpdatedAt'] }}</td>
                                            <td>
                                                @if (!empty($value['Description']))
                                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                        data-bs-target="#descriptionModal"
                                                        data-desc="{{ $value['Description'] }}">
                                                        View Description
                                                    </button>
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Resiliency Section -->
                        <h3 class="mt-4">Resiliency</h3>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Cyber Rating</th>
                                    <th>Grade</th>
                                    <th>Last Updated</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($summary['TechnicalSummary']['Resiliency'] as $key => $value)
                                    @if (is_array($value))
                                        <tr>
                                            <td>{{ $key }}</td>
                                            <td>{{ $value['CyberRating'] }}</td>
                                            <td>{{ $value['GradeLetter'] }}</td>
                                            <td>{{ $value['LastUpdatedAt'] }}</td>
                                            <td>
                                                @if (!empty($value['Description']))
                                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                        data-bs-target="#descriptionModal"
                                                        data-desc="{{ $value['Description'] }}">
                                                        View Description
                                                    </button>
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Privacy Section -->
                        <h3 class="mt-4">Privacy</h3>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Cyber Rating</th>
                                    <th>Grade</th>
                                    <th>Last Updated</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($summary['TechnicalSummary']['Privacy'] as $key => $value)
                                    @if (is_array($value))
                                        <tr>
                                            <td>{{ $key }}</td>
                                            <td>{{ $value['CyberRating'] }}</td>
                                            <td>{{ $value['GradeLetter'] }}</td>
                                            <td>{{ $value['LastUpdatedAt'] }}</td>
                                            <td>
                                                @if (!empty($value['Description']))
                                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                        data-bs-target="#descriptionModal"
                                                        data-desc="{{ $value['Description'] }}">
                                                        View Description
                                                    </button>
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Bootstrap Modal -->
                        <div class="modal fade" id="descriptionModal" tabindex="-1" aria-labelledby="descriptionModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="descriptionModalLabel">Description</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p id="modalDescription">Loading...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="compliance" class="content-section d-none">
                    <h4>Compliance Summary Details</h4>
                    <p>Details about Compliance Summary...</p>
                    <h2>Compliance Summary</h2>

                    <table class="table">
                        <thead>
                            <tr>
                                <th>Standard</th>
                                <th>Rating</th>
                                <th>Completeness</th>
                                <th>Confidence</th>
                                <th>Last Updated</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($summary['ComplianceSummary'] as $compliance)
                                <tr>
                                    <td>{{ $compliance['Standard'] }}</td>
                                    <td>{{ $compliance['Rating'] }}</td>
                                    <td>{{ $compliance['Completeness'] }}</td>
                                    <td>{{ $compliance['Confidence'] }}</td>
                                    <td>{{ \Carbon\Carbon::parse($compliance['LastUpdatedAt'])->format('Y-m-d H:i:s') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- Chart -->
                    <canvas id="complianceChart" width="400" height="200"></canvas>

                </div>
                <div id="financial" class="content-section d-none">
                    <h4>Financial Summary Details</h4>
                    <p>Details about Financial Summary...</p>
                    <div class="card">
                        <div class="card-body">
                            <canvas id="financialSummaryChart"></canvas>
                        </div>
                    </div>

                    <h2 class="mt-4">Financial Summaries</h2>

                    <!-- Table for Financial Summaries -->
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Risk Type</th>
                                <th>Average Loss</th>
                                <th>Minimum Loss</th>
                                <th>Maximum Loss</th>
                                <th>Loss Magnitude</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($summary['FinancialSummaries'] as $financial)
                                <tr>
                                    <td>{{ $financial['RiskType'] }}</td>
                                    <td>${{ number_format($financial['Avg'], 2) }}</td>
                                    <td>${{ number_format($financial['Min'], 2) }}</td>
                                    <td>${{ number_format($financial['Max'], 2) }}</td>
                                    <td>${{ number_format($financial['LossMagnitude']['Value'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>

        </div>
    </div>

    <script>
        var complianceData = {!! json_encode($summary['ComplianceSummary']) !!};
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const chartCtx = document.getElementById("complianceChart").getContext("2d");

            let labels = [];
            let ratings = [];
            let completeness = [];
            let confidence = [];

            complianceData.forEach(item => {
                // Prepare Data for Chart
                labels.push(item.Standard);
                ratings.push(item.Rating);
                completeness.push(item.Completeness);
                confidence.push(item.Confidence);
            });

            // Render Bar Chart
            new Chart(chartCtx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                            label: 'Rating',
                            data: ratings,
                            backgroundColor: 'rgba(54, 162, 235, 0.6)'
                        },
                        {
                            label: 'Completeness',
                            data: completeness,
                            backgroundColor: 'rgba(255, 206, 86, 0.6)'
                        },
                        {
                            label: 'Confidence',
                            data: confidence,
                            backgroundColor: 'rgba(75, 192, 192, 0.6)'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
    <script>
        var financialData = @json($summary['FinancialSummary']);

        document.addEventListener("DOMContentLoaded", function () {
            var ctx = document.getElementById('financialSummaryChart').getContext('2d');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Average', 'Minimum', 'Maximum'],
                    datasets: [{
                        label: 'Financial Summary (USD)',
                        data: [financialData.Avg, financialData.Min, financialData.Max],
                        backgroundColor: ['#007bff', '#28a745', '#dc3545'],
                        borderColor: ['#0056b3', '#1e7e34', '#c82333'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        });
    </script>
    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".card").click(function() {
                $(".card").removeClass("active");
                $(this).addClass("active");

                // Hide all sections
                $(".content-section").addClass("d-none");

                // Show the clicked target section
                let target = $(this).data("target");
                $("#" + target).removeClass("d-none");
            });

            // Prevent closing when clicking inside content-section
            $(".content-section").click(function(event) {
                event.stopPropagation();
            });
        });
    </script>
    <!-- Chart.js for Cyber Ratings -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById("cyberChart").getContext("2d");
            var cyberChart = new Chart(ctx, {
                type: "bar",
                data: {
                    labels: ["Cyber Rating", "Application Security", "Network Security"],
                    datasets: [{
                        label: "Cyber Rating Score",
                        data: [
                            {{ $summary['TechnicalSummary']['CyberRating'] }},
                            {{ $summary['TechnicalSummary']['SafeGuard']['ApplicationSecurity']['CyberRating'] }},
                            {{ $summary['TechnicalSummary']['Resiliency']['NetworkSecurity']['CyberRating'] }}
                        ],
                        backgroundColor: ["#4CAF50", "#FF9800", "#2196F3"]
                    }]
                }
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var descriptionModal = document.getElementById('descriptionModal');

            descriptionModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget; // Button that triggered the modal
                var description = button.getAttribute('data-desc'); // Extract info from data-* attribute
                document.getElementById("modalDescription").innerText = description; // Insert into modal
            });
        });
    </script>

@endsection
