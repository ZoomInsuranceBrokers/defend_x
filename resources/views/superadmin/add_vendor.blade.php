@extends('superadmin.layouts.app')
@section('title', 'Defend X - Add Vendor')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title"> Add Vendor </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Add</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Vendor</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-md-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Join the Journey: Share Your Vendor Details to Get Onboarded with Us!
                            </h4>
                            <p class="card-description">Let's Start!</p>
                            <form class="forms-sample" method="POST" action="{{ route('superadmin.add_vendor_post') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="vendor_name">Vendor Name</label>
                                            <input type="text" class="form-control" id="vendor_name" name="vendor_name"
                                                value="{{ old('vendor_name') }}" placeholder="Enter vendor name">
                                            @error('vendor_name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="support_email">Support Email</label>
                                            <input type="email" class="form-control" id="support_email"
                                                name="support_email" value="{{ old('support_email') }}"
                                                placeholder="Enter support email">
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
                                            <input type="number" class="form-control" id="support_mobile"
                                                name="support_mobile" value="{{ old('support_mobile') }}"
                                                placeholder="Enter support mobile">
                                            @error('support_mobile')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <textarea class="form-control" id="address" name="address" rows="3" placeholder="Enter address">{{ old('address') }}</textarea>
                                            @error('address')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="logo">Upload Logo</label>
                                            <input type="file" class="form-control-file" id="logo" name="logo">
                                            @error('logo')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary mt-4">Submit</button>
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
