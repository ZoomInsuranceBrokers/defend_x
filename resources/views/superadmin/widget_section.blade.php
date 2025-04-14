@extends('superadmin.layouts.app')
@section('title', 'Defend X - Visual Report')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <h2 class="mb-4">Compliance Dashboard</h2>

            {{-- Company Info Section --}}
            @if (!empty($data['information_response']))
                <div class="mb-4">
                    <h4>Company: {{ $data['information_response']['companyName'] ?? 'N/A' }}</h4>
                    <p>Industry: {{ $data['information_response']['industry'] ?? 'N/A' }}</p>
                    <p>Location: {{ $data['information_response']['country'] ?? 'N/A' }}</p>
                </div>
            @endif

            {{-- Widgets Grid --}}
            <div class="row">

                {{-- Compliance Rating Widget --}}
                @if (!empty($data['compliance_widget']))
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-header">
                                <h5 class="card-title">Compliance Rating</h5>
                            </div>
                            <div class="card-body text-center">
                                <img src="data:image/png;base64,{{ $data['compliance_widget'] }}" class="img-fluid"
                                    alt="Compliance Rating">
                            </div>
                        </div>
                    </div>
                @endif


                {{-- Repeat similar blocks for other widgets once you add them in backend --}}
                {{-- Example Widget --}}
                {{--
                @if (!empty($data['xyz_widget']['widget']))
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-header">
                                <h5 class="card-title">XYZ Widget</h5>
                            </div>
                            <div class="card-body text-center">
                                <img src="data:image/png;base64,{{ $data['xyz_widget']['widget'] }}" class="img-fluid" alt="XYZ Widget">
                            </div>
                        </div>
                    </div>
                @endif
                --}}

            </div>
        </div>
    </div>
@endsection
