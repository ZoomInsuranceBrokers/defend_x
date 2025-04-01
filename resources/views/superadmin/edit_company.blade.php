@extends('superadmin.layouts.app')
@section('title', 'Defend X - Edit Company')
@section('content')
@php
    $company = $data['company'];
@endphp
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title"> Edit Company </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Edit</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Company</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-md-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Update Company Details</h4>
                            <form class="forms-sample" method="POST" action="{{ route('superadmin.update_company', $company->comp_id) }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="comp_name">Company Name</label>
                                            <input type="text" class="form-control" id="comp_name" name="comp_name" value="{{ old('comp_name', $company->comp_name) }}" placeholder="Enter company name">
                                            @error('comp_name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="business_type">Industry Type</label>
                                            <select class="form-control" id="business_type" name="business_type">
                                                <option value="" disabled>Select industry type</option>
                                                @foreach ([
                                                    'Agriculture', 'Automotive', 'Construction', 'Education',
                                                    'Energy', 'Finance', 'Healthcare', 'Hospitality',
                                                    'IT and Software', 'Manufacturing', 'Media and Entertainment',
                                                    'Real Estate', 'Retail', 'Telecommunications',
                                                    'Transportation and Logistics', 'Other'
                                                ] as $type)
                                                    <option value="{{ $type }}" {{ old('business_type', $company->business_type) === $type ? 'selected' : '' }}>
                                                        {{ $type }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('business_type')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="comp_addr">Company Address</label>
                                            <input type="text" class="form-control" id="comp_addr" name="comp_addr" value="{{ old('comp_addr', $company->comp_addr) }}" placeholder="Enter company address">
                                            @error('comp_addr')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="comp_pincode">Pincode</label>
                                            <input type="number" class="form-control" id="comp_pincode" name="comp_pincode" value="{{ old('comp_pincode', $company->comp_pincode) }}" placeholder="Enter pincode">
                                            @error('comp_pincode')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="comp_city">City</label>
                                            <input type="text" class="form-control" id="comp_city" name="comp_city" value="{{ old('comp_city', $company->comp_city) }}" placeholder="Enter city">
                                            @error('comp_city')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="comp_state">State</label>
                                            <input type="text" class="form-control" id="comp_state" name="comp_state" value="{{ old('comp_state', $company->comp_state) }}" placeholder="Enter state">
                                            @error('comp_state')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="comp_logo">Upload Company Logo</label>
                                            <input type="file" class="form-control" id="comp_logo" name="comp_logo" accept="image/*">
                                            @error('comp_logo')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select class="form-control" id="status" name="status">
                                                <option value="1" {{ old('status', $company->status) == 1 ? 'selected' : '' }}>Active</option>
                                                <option value="0" {{ old('status', $company->status) == 0 ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                            @error('status')
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
