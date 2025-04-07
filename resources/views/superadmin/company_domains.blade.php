@extends('superadmin.layouts.app')

@section('title', 'Defend X - Domain Details')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            @php $info = $data['information_records']; @endphp

            <div class="card mb-4 shadow-sm p-3">
                <div class="row align-items-center">
                    {{-- Left: Logo --}}
                    <div class="col-md-3 text-center">
                        @if (!empty($info['Logo']['Content']))
                            <img src="{{ $info['Logo']['Content'] }}" alt="Company Logo" class="img-fluid"
                                style="max-height: 120px; object-fit: contain;">
                        @else
                            <div class="text-muted">No logo available</div>
                        @endif
                    </div>

                    {{-- Right: Company Info --}}
                    <div class="col-md-9">
                        <h4 class="mb-2">{{ $info['Name'] ?? 'N/A' }}</h4>
                        <p class="mb-1"><strong>Website:</strong>
                            <a href="http://{{ $info['Url'] }}" target="_blank">{{ $info['Url'] }}</a>
                        </p>
                        <p class="mb-1"><strong>Industry:</strong>
                            {{ $info['Industry']['IndustryName'] ?? 'N/A' }}
                        </p>
                        <p class="mb-1"><strong>Number of Employees:</strong>
                            {{ $info['NumberOfEmployees'] ?? 'N/A' }}
                        </p>
                        <p class="mb-1"><strong>Risk Rating:</strong>
                            {{ $info['CountryRiskRating'] ?? 'N/A' }}
                        </p>
                        <p class="mb-1"><strong>Score:</strong>
                            {{ $info['Score'] ?? 'N/A' }}
                        </p>
                        @if (!empty($info['Specialities']))
                            <p class="mb-1"><strong>Specialities:</strong> {{ $info['Specialities'] }}</p>
                        @endif
                    </div>
                </div>
                <div class="col d-flex flex-wrap gap-2">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#socialMediaModal">Social
                        Media</button>
                    <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#dnsModal">DNS Records</button>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#emailsModal">Emails</button>
                    <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#cloudModal">Cloud</button>
                </div>
            </div>

            {{-- Bootstrap Modals --}}
            <!-- Social Media Modal -->
            <div class="modal fade" id="socialMediaModal" tabindex="-1" aria-labelledby="socialMediaLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Company Social Media Accounts</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @php
                                $icons = [
                                    'instagram' => 'fab fa-instagram',
                                    'facebook' => 'fab fa-facebook',
                                    'twitter' => 'fab fa-twitter',
                                    'linkedin' => 'fab fa-linkedin',
                                    'youtube' => 'fab fa-youtube',
                                ];
                            @endphp

                            @if (!empty($data['social_records']))
                                @foreach ($data['social_records'] as $item)
                                    <div class="d-flex align-items-center mb-3 border-bottom pb-2">
                                        <i
                                            class="{{ $icons[strtolower($item['Media'])] ?? 'fas fa-globe' }} fs-3 text-primary me-3"></i>
                                        <div>
                                            <h6 class="mb-1 text-capitalize">{{ $item['Media'] }}</h6>
                                            <a href="{{ $item['Url'] }}" target="_blank">{{ $item['Url'] }}</a>
                                            <p class="text-muted small mb-0">{{ $item['Domain'] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p>No social media records found.</p>
                            @endif
                        </div>

                    </div>
                </div>
            </div>

            <!-- DNS Modal -->
            <div class="modal fade" id="dnsModal" tabindex="-1" aria-labelledby="dnsModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">DNS Records</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @if (!empty($data['dns_records']) && is_array($data['dns_records']))
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Type</th>
                                                <th>Address</th>
                                                <th>Relations</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data['dns_records'] as $record)
                                                <tr>
                                                    <td>{{ $record['Name'] ?? '-' }}</td>
                                                    <td>{{ $record['Type'] ?? '-' }}</td>
                                                    <td>{{ $record['Address'] ?? '-' }}</td>
                                                    <td>
                                                        @if (!empty($record['Relations']) && is_array($record['Relations']))
                                                            <ul class="mb-0 ps-3">
                                                                @foreach ($record['Relations'] as $relation)
                                                                    <li>{{ $relation }}</li>
                                                                @endforeach
                                                            </ul>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p>No DNS records available.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>


            <!-- Emails Modal -->
            <div class="modal fade" id="emailsModal" tabindex="-1" aria-labelledby="emailsModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Emails</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="text" id="emailSearch" class="form-control mb-3" placeholder="Search emails...">

                            <div class="table-responsive">
                                <table class="table table-bordered table-sm align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Email</th>
                                            <th>Detection Date</th>
                                            <th>Source</th>
                                            <th>Type</th>
                                        </tr>
                                    </thead>
                                    <tbody id="emailTableBody">
                                        @foreach ($data['email_records'] as $index => $record)
                                            <tr class="email-row">
                                                <td>{{ $record['Email'] }}</td>
                                                <td>{{ $record['DetectionDate'] }}</td>
                                                <td>{{ $record['Source'] }}</td>
                                                <td>{{ $record['SourceType'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <nav>
                                <ul class="pagination justify-content-center" id="emailPagination"></ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Information Modal -->
            <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Information</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Display extra info about the company or domain.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cloud Modal -->
            <div class="modal fade" id="cloudModal" tabindex="-1" aria-labelledby="cloudModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Cloud</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Show if this company uses AWS, GCP, Azure, etc.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <h3>Active Domain Details</h3>
                @if (isset($data['domains']) && count($data['domains']) > 0)
                    @foreach ($data['domains'] as $domain)
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card text-white bg-dark shadow-sm border border-secondary rounded-sm">
                                <div class="card-body p-3">
                                    <h6 class="card-title text-warning">{{ $domain['Domain'] }}</h6>
                                    <p class="small text-light"><strong>Relation:</strong> {{ $domain['Relation'] }}</p>

                                    <ul class="list-group list-group-flush small">
                                        <li class="list-group-item bg-dark border-secondary text-light">
                                            <strong>Registrar:</strong> {{ $domain['Whois']['Registrar'] }}
                                        </li>
                                        <li class="list-group-item bg-dark border-secondary text-light">
                                            <strong>Created:</strong>
                                            {{ date('d M Y', strtotime($domain['Whois']['CreateDate'])) }}
                                        </li>
                                        <li class="list-group-item bg-dark border-secondary text-light">
                                            <strong>Expires:</strong>
                                            {{ date('d M Y', strtotime($domain['Whois']['ExpireDate'])) }}
                                        </li>
                                        <li class="list-group-item bg-dark border-secondary text-light">
                                            <strong>IP:</strong> {{ $domain['IPAddresses'] }}
                                        </li>
                                        <li class="list-group-item bg-dark border-secondary text-light">
                                            <strong>NS:</strong> {{ $domain['NSAddresses'] }}
                                        </li>
                                        <li class="list-group-item bg-dark border-secondary text-light">
                                            <strong>Mail Server:</strong> {{ $domain['MXAddresses'] }}
                                        </li>
                                        <li class="list-group-item bg-dark border-secondary text-light">
                                            <strong>Classification:</strong>
                                            <span class="badge bg-success">{{ $domain['Classification'] }}</span>
                                        </li>
                                        <li class="list-group-item bg-dark border-secondary text-light">
                                            <strong>Status:</strong>
                                            <span class="badge bg-info">{{ $domain['Status'] }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-center text-muted">No domains found for this company.</p>
                @endif
            </div>

            {{-- Subdomains Table with Search & Pagination --}}
            <div class="row mt-5">
                <h3>Active Subdomains</h3>

                <div class="col-md-12">
                    <input type="text" id="searchSubdomains" class="form-control mb-3"
                        placeholder="Search Subdomains...">

                    @if (isset($data['subdomains']) && count($data['subdomains']) > 0)
                        <table class="table table-dark table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Subdomain</th>
                                    <th>IP Addresses</th>
                                    <th>Status Code</th>
                                    <th>SSL/TLS</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="subdomainTableBody">
                                @foreach ($data['subdomains'] as $index => $subdomain)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $subdomain['Subdomain'] }}</td>
                                        <td>{{ is_array($subdomain['IPAddresses']) ? implode(', ', $subdomain['IPAddresses']) : $subdomain['IPAddresses'] }}
                                        </td>
                                        <td>{{ $subdomain['StatusCode'] }}</td>
                                        <td>
                                            @if ($subdomain['HasSslTlsSupport'])
                                                <span class="badge bg-success">Yes</span>
                                            @else
                                                <span class="badge bg-danger">No</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $subdomain['Status'] }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{-- Pagination --}}
                        <nav>
                            <ul class="pagination">
                                <li class="page-item disabled" id="prevPage">
                                    <a class="page-link" href="javascript:void(0)">Previous</a>
                                </li>
                                <li class="page-item active"><a class="page-link" href="javascript:void(0)"
                                        id="currentPage">1</a></li>
                                <li class="page-item" id="nextPage">
                                    <a class="page-link" href="javascript:void(0)">Next</a>
                                </li>
                            </ul>
                        </nav>
                    @else
                        <p class="text-center text-muted">No subdomains found for this company.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('mediaData', {
                mediaData: window.mediaData,
                getIcon
            });

            Alpine.data('modalHandler', () => ({
                open: false,
                mediaData: window.mediaData,
                getIcon
            }));
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('#emailTableBody .email-row');
            const pagination = document.getElementById('emailPagination');
            const searchInput = document.getElementById('emailSearch');

            let currentPage = 1;
            const rowsPerPage = 5;

            function showPage(page) {
                const start = (page - 1) * rowsPerPage;
                const end = start + rowsPerPage;

                rows.forEach((row, index) => {
                    row.style.display = index >= start && index < end ? '' : 'none';
                });

                renderPagination(page);
            }

            function renderPagination(activePage) {
                const totalPages = Math.ceil(rows.length / rowsPerPage);
                pagination.innerHTML = '';

                for (let i = 1; i <= totalPages; i++) {
                    const li = document.createElement('li');
                    li.classList.add('page-item');
                    if (i === activePage) li.classList.add('active');

                    const a = document.createElement('a');
                    a.classList.add('page-link');
                    a.href = '#';
                    a.textContent = i;
                    a.addEventListener('click', function(e) {
                        e.preventDefault();
                        currentPage = i;
                        showPage(currentPage);
                    });

                    li.appendChild(a);
                    pagination.appendChild(li);
                }
            }

            searchInput.addEventListener('input', function() {
                const term = this.value.toLowerCase();
                let visibleRows = [];

                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    const match = text.includes(term);
                    row.style.display = match ? '' : 'none';
                    if (match) visibleRows.push(row);
                });

                if (term) {
                    pagination.style.display = 'none';
                } else {
                    pagination.style.display = '';
                    showPage(currentPage);
                }
            });

            if (rows.length > 0) {
                showPage(currentPage);
            }
        });
    </script>

    {{-- JavaScript for Search & Pagination --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let tableRows = document.querySelectorAll("#subdomainTableBody tr");
            let searchInput = document.getElementById("searchSubdomains");
            let perPage = 5;
            let currentPage = 1;

            function showPage(page) {
                let start = (page - 1) * perPage;
                let end = start + perPage;

                tableRows.forEach((row, index) => {
                    row.style.display = (index >= start && index < end) ? "" : "none";
                });

                document.getElementById("currentPage").innerText = page;
                document.getElementById("prevPage").classList.toggle("disabled", page === 1);
                document.getElementById("nextPage").classList.toggle("disabled", end >= tableRows.length);
            }

            function filterTable() {
                let searchTerm = searchInput.value.toLowerCase();
                tableRows.forEach(row => {
                    let subdomain = row.cells[1].textContent.toLowerCase();
                    row.style.display = subdomain.includes(searchTerm) ? "" : "none";
                });
            }

            document.getElementById("prevPage").addEventListener("click", () => {
                if (currentPage > 1) {
                    currentPage--;
                    showPage(currentPage);
                }
            });

            document.getElementById("nextPage").addEventListener("click", () => {
                if ((currentPage * perPage) < tableRows.length) {
                    currentPage++;
                    showPage(currentPage);
                }
            });

            searchInput.addEventListener("keyup", filterTable);
            showPage(currentPage);
        });
    </script>
@endsection
