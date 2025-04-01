@extends('superadmin.layouts.app')
@section('title', 'Defend X - Add Company')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title"> Add Company </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Add</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Company</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-md-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Join the Journey: Share Your Company Details to Get Onboarded with Us!</h4>
                            <p class="card-description">Let's Start!</p>
                            <form class="forms-sample" method="POST" action="{{ route('superadmin.add_company_post') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="comp_name">Company Name</label>
                                            <input type="text" class="form-control" id="comp_name" name="comp_name" value="{{ old('comp_name') }}" placeholder="Enter company name">
                                            @error('comp_name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="business_type">Industry Type</label>
                                            <select class="form-control" id="business_type" name="business_type">
                                                <option value="" disabled selected>Select industry type</option>
                                                <option value="Agriculture" {{ old('business_type') == 'Agriculture' ? 'selected' : '' }}>Agriculture</option>
                                                <option value="Automotive" {{ old('business_type') == 'Automotive' ? 'selected' : '' }}>Automotive</option>
                                                <option value="Construction" {{ old('business_type') == 'Construction' ? 'selected' : '' }}>Construction</option>
                                                <option value="Education" {{ old('business_type') == 'Education' ? 'selected' : '' }}>Education</option>
                                                <option value="Energy" {{ old('business_type') == 'Energy' ? 'selected' : '' }}>Energy</option>
                                                <option value="Finance" {{ old('business_type') == 'Finance' ? 'selected' : '' }}>Finance</option>
                                                <option value="Healthcare" {{ old('business_type') == 'Healthcare' ? 'selected' : '' }}>Healthcare</option>
                                                <option value="Hospitality" {{ old('business_type') == 'Hospitality' ? 'selected' : '' }}>Hospitality</option>
                                                <option value="IT and Software" {{ old('business_type') == 'IT and Software' ? 'selected' : '' }}>IT and Software</option>
                                                <option value="Manufacturing" {{ old('business_type') == 'Manufacturing' ? 'selected' : '' }}>Manufacturing</option>
                                                <option value="Media and Entertainment" {{ old('business_type') == 'Media and Entertainment' ? 'selected' : '' }}>Media and Entertainment</option>
                                                <option value="Real Estate" {{ old('business_type') == 'Real Estate' ? 'selected' : '' }}>Real Estate</option>
                                                <option value="Retail" {{ old('business_type') == 'Retail' ? 'selected' : '' }}>Retail</option>
                                                <option value="Telecommunications" {{ old('business_type') == 'Telecommunications' ? 'selected' : '' }}>Telecommunications</option>
                                                <option value="Transportation and Logistics" {{ old('business_type') == 'Transportation and Logistics' ? 'selected' : '' }}>Transportation and Logistics</option>
                                                <option value="Other" {{ old('business_type') == 'Other' ? 'selected' : '' }}>Other</option>
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
                                            <input type="text" class="form-control" id="comp_addr" name="comp_addr" value="{{ old('comp_addr') }}" placeholder="Enter company address">
                                            @error('comp_addr')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="comp_pincode">Pincode</label>
                                            <input type="number" class="form-control" id="comp_pincode" name="comp_pincode" value="{{ old('comp_pincode') }}" placeholder="Enter pincode">
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
                                            <input type="text" class="form-control" id="comp_city" name="comp_city" value="{{ old('comp_city') }}" placeholder="Enter city">
                                            @error('comp_city')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="comp_state">State</label>
                                            <input type="text" class="form-control" id="comp_state" name="comp_state" value="{{ old('comp_state') }}" placeholder="Enter state">
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
