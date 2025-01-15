@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('content')
    <div class="section-body">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ __('Dashboard') }}</h4>
                        </div>

                        <div class="card-body">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <h5>{{ __('You are logged in!') }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
