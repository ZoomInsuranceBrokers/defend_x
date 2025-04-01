@extends('superadmin.layouts.app')
@section('title', 'Defend X - Edit User')
@section('content')
@php
    $user = $data['users'];
@endphp
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title"> Edit User </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Edit</a></li>
                        <li class="breadcrumb-item active" aria-current="page">User</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-md-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Update User Details</h4>
                            <form class="forms-sample" method="POST" action="{{ route('superadmin.update_user', $user->id) }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="first_name">First Name</label>
                                            <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" placeholder="Enter first name">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="last_name">Last Name</label>
                                            <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" placeholder="Enter last name">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="mobile">Mobile</label>
                                            <input type="text" class="form-control" id="mobile" name="mobile" value="{{ old('mobile', $user->mobile) }}" placeholder="Enter mobile number">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="company">Company</label>
                                            <select class="form-control" id="company" name="company">
                                                <option value="" disabled>Select Company</option>
                                                @foreach ($data['companies'] as $company)
                                                    <option value="{{ $company->comp_id }}" {{ $user->company_id == $company->comp_id ? 'selected' : '' }}>{{ $company->comp_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="role">Role</label>
                                            <select class="form-control" id="role" name="role">
                                                <option value="2" {{ $user->role_id == 2 ? 'selected' : '' }}>Company Admin</option>
                                                <option value="3" {{ $user->role_id == 3 ? 'selected' : '' }}>Company User</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select class="form-control" id="status" name="is_active">
                                                <option value="1" {{ $user->is_active == 1 ? 'selected' : '' }}>Active</option>
                                                <option value="0" {{ $user->is_active == 0 ? 'selected' : '' }}>Inactive</option>
                                            </select>
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
