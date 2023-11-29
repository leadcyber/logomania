@extends('layouts.home-master')

@section('styles')
    <!-- SWIPERJS CSS -->
    <link rel="stylesheet" href="{{ asset('build/assets/libs/swiper/swiper-bundle.min.css') }}">
    <style>
        #desc {
            height: 150px;
        }
    </style>
@endsection

@section('content')
    <section class="section logo">
        <div class="container d-flex justify-content-center">
            <div class="card custom-card shadow">
                <div class="card-header">
                    <div class="card-title">Generated LOGOs.</div>
                </div>
                <div class="card-body d-flex justify-content-around flex-wrap">
                    @foreach ($logos as $logo)
                        <div class="mb-4 shadow rounded-3 overflow-hidden">
                            {!! $logo !!}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <!-- SWIPER JS -->
    <script src="{{ asset('build/assets/libs/swiper/swiper-bundle.min.js') }}"></script>

    <!-- INTERNAL LANDING JS -->
    @vite('resources/assets/js/home.js')
@endsection
