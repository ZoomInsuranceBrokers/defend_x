@extends('superadmin.layouts.app')
@section('title', 'Defend X - Edit Vendor')
@section('content')
@php
    $vendor = $data['vendor'];
@endphp
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title"> Edit Vendor </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Edit</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Vendor</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-md-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Update Vendor Details</h4>
                            <form class="forms-sample" method="POST" action="{{ route('superadmin.update_vendor', $vendor->id) }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="vendor_name">Vendor Name</label>
                                            <input type="text" class="form-control" id="vendor_name" name="vendor_name" value="{{ old('vendor_name', $vendor->vendor_name) }}" placeholder="Enter vendor name">
                                            @error('vendor_name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="support_email">Support Email</label>
                                            <input type="email" class="form-control" id="support_email" name="support_email" value="{{ old('support_email', $vendor->support_email) }}" placeholder="Enter support email">
                                            @error('support_email')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="support_mobile">Support Mobile</label>
                                            <input type="text" class="form-control" id="support_mobile" name="support_mobile" value="{{ old('support_mobile', $vendor->support_mobile) }}" placeholder="Enter support mobile">
                                            @error('support_mobile')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="is_active">Status</label>
                                            <select class="form-control" id="is_active" name="is_active">
                                                <option value="1" {{ old('is_active', $vendor->is_active) == '1' ? 'selected' : '' }}>Active</option>
                                                <option value="0" {{ old('is_active', $vendor->is_active) == '0' ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                            @error('is_active')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <textarea class="form-control" id="address" name="address" rows="3" placeholder="Enter address">{{ old('address', $vendor->address) }}</textarea>
                                            @error('address')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="logo">Upload Logo</label>
                                            <input type="file" class="form-control-file" id="logo" name="logo">
                                            @if($vendor->logo)
                                                <img src="{{ asset( $vendor->logo) }}" alt="Vendor Logo" width="100" class="mt-2">
                                            @endif
                                            @error('logo')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary mt-4">Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
