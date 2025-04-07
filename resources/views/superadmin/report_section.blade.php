@extends('superadmin.layouts.app')
@section('title', 'Defend X - Report Summary')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                @php
                    $cards = [
                        [
                            'title' => 'Company Trend',
                            'description' => 'Analyze company trends and patterns over time.',
                            'icon' => 'trending-up',
                            'route' => route('superadmin.company-trend', ['id' => $cmp_id]),
                            'color' => 'primary'
                        ],
                        [
                            'title' => 'Findings',
                            'description' => 'Review key findings from recent assessments.',
                            'icon' => 'file-text',
                            'route' => route('superadmin.findings', ['id' => $cmp_id]),
                            'color' => 'success'
                        ],
                        [
                            'title' => 'Digital Footprints',
                            'description' => 'Track the company’s online presence and exposure.',
                            'icon' => 'globe',
                            'route' => route('superadmin.digital-footprints', ['id' => $cmp_id]),
                            'color' => 'info'
                        ],
                        [
                            'title' => 'Compliance',
                            'description' => 'Check the company’s compliance status with regulations.',
                            'icon' => 'shield',
                            'route' => route('superadmin.compliance', ['id' => $cmp_id]),
                            'color' => 'warning'
                        ],
                        [
                            'title' => 'Financial Risk',
                            'description' => 'Assess potential financial risks impacting the company.',
                            'icon' => 'bar-chart',
                            'route' => route('superadmin.financial-risk', ['id' => $cmp_id]),
                            'color' => 'danger'
                        ],
                        [
                            'title' => 'Reports & Findings',
                            'description' => 'Download detailed reports and findings.',
                            'icon' => 'download',
                            'route' => route('superadmin.report-section', ['id' => $cmp_id]),
                            'color' => 'dark'
                        ]
                    ];
                @endphp

                @foreach($cards as $card)
                    <div class="col-md-4 col-sm-6 grid-margin stretch-card">
                        <a href="{{ $card['route'] }}" class="card-link text-decoration-none">
                            <div class="card border-left-{{ $card['color'] }} shadow-sm p-3">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <i class="mdi mdi-{{ $card['icon'] }} mdi-36px text-{{ $card['color'] }}"></i>
                                        <div class="ms-3">
                                            <h5 class="card-title text-{{ $card['color'] }}">{{ $card['title'] }}</h5>
                                            <p class="card-text text-muted mb-0">{{ $card['description'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
