@extends('superadmin.layouts.app')
@section('title', 'Defend X - Vendor Details')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title"> Vendor Details </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Tables</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Vendor tables</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="page-header">
                                <h4 class="card-title">Vendor Table</h4>
                                <a href="{{ route('superadmin.add_vendor') }}">
                                    <button type="button" class="btn btn-success btn-rounded btn-fw">Add New
                                        Vendor</button>
                                </a>
                            </div>
                            <p class="card-description"> Details of All <code>Vendors</code>
                            </p>
                            <div class="table-responsive">
                                <table class="table" id="example">
                                    <thead>
                                        <tr>
                                            <th>Vendor Name</th>
                                            <th>Supported Email</th>
                                            <th>Address</th>
                                            <th>Mobile</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($data['vendors'] as $vendor): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($vendor->vendor_name) ?></td>
                                            <td><?= htmlspecialchars($vendor->support_email) ?></td>
                                            <td><?= htmlspecialchars($vendor->address ) ?></td>
                                            <td><?= htmlspecialchars($vendor->support_mobile ) ?></td>
                                            <td>
                                                <label class="badge badge-<?= $vendor->is_active == 1 ? 'success' : 'danger'; ?>">
                                                    <?= $vendor->is_active == 1 ? 'Active' : 'InActive'; ?>
                                                </label>
                                            </td>
                                            <td>
                                                <a href="{{ route('superadmin.edit.vendor', base64_encode(string: $vendor->id)) }}">
                                                    <button class="badge badge-warning">Edit</button>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">

    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (window.DataTable) {
                new DataTable('#example', {
                    paging: true, // Enable pagination
                    searching: true, // Enable search box
                    ordering: true, // Enable column ordering
                    info: true // Show table information
                });
            } else {
                console.error("DataTables library is not loaded.");
            }
        });
    </script>
@endsection
