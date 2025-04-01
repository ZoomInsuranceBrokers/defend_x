@extends('superadmin.layouts.app')
@section('title', 'Defend X - Add User')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title"> Add User </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Add</a></li>
                        <li class="breadcrumb-item active" aria-current="page">User</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-md-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Join the Journey: Share Your User Details to Get Onboarded with Us!
                            </h4>
                            <p class="card-description">Let's Start!</p>
                            <form class="forms-sample" method="POST" action="{{ route('superadmin.add_user_post') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <!-- First Name -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="first_name">First Name</label>
                                            <input type="text" class="form-control" id="first_name" name="first_name"
                                                value="{{ old('first_name') }}" placeholder="Enter first name">
                                            @error('first_name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- Last Name -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="last_name">Last Name</label>
                                            <input type="text" class="form-control" id="last_name" name="last_name"
                                                value="{{ old('last_name') }}" placeholder="Enter last name">
                                            @error('last_name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">

                                    <!-- Gender -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="gender">Gender</label>
                                            <select class="form-control" id="gender" name="gender">
                                                <option value="" disabled selected>Select gender</option>
                                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male
                                                </option>
                                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>
                                                    Female</option>
                                            </select>
                                            @error('gender')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Email -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" id="email" name="email"
                                                value="{{ old('email') }}" placeholder="Enter email">
                                            @error('email')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">

                                    <!-- Mobile -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="mobile">Mobile</label>
                                            <input type="text" class="form-control" id="mobile" name="mobile"
                                                value="{{ old('mobile') }}" placeholder="Enter mobile number">
                                            @error('mobile')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- Select Company -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="company">Company</label>
                                            <select class="form-control" id="company" name="company">
                                                <option value="" disabled selected>Select Company</option>
                                                @foreach ($data['companies'] as $company)
                                                    <option value="{{ $company->comp_id }}">{{ $company->comp_name }}</option>
                                                @endforeach
                                            </select>
                                            @error('company')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="role">Role</label>
                                            <select class="form-control" id="role" name="role">
                                                <option value="" disabled selected>Select Role</option>
                                                <option value="2" {{ old('role') == '2' ? 'selected' : '' }}>
                                                    Company Admin
                                                </option>
                                                <option value="3"
                                                    {{ old('role') == '3' ? 'selected' : '' }}>
                                                    Company User</option>

                                            </select>
                                            @error('role')
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
