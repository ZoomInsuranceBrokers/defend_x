@extends('superadmin.layouts.app')

@section('title', 'Defend X - Domain Details')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
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
                                        <td>{{ is_array($subdomain['IPAddresses']) ? implode(', ', $subdomain['IPAddresses']) : $subdomain['IPAddresses'] }}</td>
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
