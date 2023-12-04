@extends('layouts.home-master')

@section('styles')
    <!-- SWIPERJS CSS -->
    <link rel="stylesheet" href="{{ asset('build/assets/libs/swiper/swiper-bundle.min.css') }}">
    <style>
        .svg-wrapper {
            width: 380px;
            height: 240px;
            position: relative;
        }
        .svg-btn-group {
            position: absolute;
            bottom: 10px;
            right: 10px;
            font-size: 20px;
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
                <div id="logos-wrapper" class="card-body d-flex justify-content-around flex-wrap">

                </div>
                <div class="card-footer d-flex justify-content-center">
                    <button class="btn btn-secondary me-3 btn-back" onclick="window.history.go(-1);">Back</button>
                    <button class="btn btn-primary btn-load-more">Generate More...</button>
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

    <script>
        var page = 1;
        var itemsPerPage = 9;
        var count = 0;
        var total = 0;

        var skeleton = '<div class="mb-4 shadow rounded-3 overflow-hidden svg-wrapper skeleton"></div>';

        $('.btn-load-more').click(() => {
            page++;
            fetchSVGs();
        });

        function fetchSVGs() {
            if (count > 0 && count >= total) return;
            $('#logos-wrapper').append(skeleton.repeat(itemsPerPage));
            $.get("{{ route('logo.svgs') }}?page=" + page + "&itemsPerPage=" + itemsPerPage)
                .then(res => {
                    res = JSON.parse(res);
                    if (res.svgs && res.svgs.length > 0) {
                        var html = "";
                        var svgs = res.svgs;
                        
                        svgs.forEach((svg, index) => {
                            html += 
                                '<div id="svg-' + (count + index) + '" class="mb-4 shadow rounded-3 overflow-hidden svg-wrapper">' + svg + 
                                    '<div class="svg-btn-group d-flex">' + 
                                        '<button class="btn btn-icon btn-primary-transparent rounded-pill btn-wave me-2"><i class="ri-heart-line"></i></button>' +
                                        '<button class="btn btn-icon btn-secondary-transparent rounded-pill btn-wave"><i class="ri-edit-line"></i></button>' +
                                    '</div>' + 
                                '</div>';
                        })

                        $('#logos-wrapper .skeleton').remove();
                        $('#logos-wrapper').append(html);

                        count += svgs.length;
                        total = res.total;
                    }

                }).catch(err => {
                    console.log(err);
                });
        }

        fetchSVGs();
    </script>
@endsection
