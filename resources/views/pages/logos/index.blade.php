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
        .svg-wrapper.favorite .btn-favorite {
            background-color: rgb(var(--primary-rgb));
            border-color: rgb(var(--primary-rgb));
            color: #fff;
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
                    <button class="btn btn-primary btn-load-more me-3">Generate More...</button>
                    <button class="btn btn-success btn-next">Next</button>
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

        var favorites = [];

        var skeleton = '<div class="mb-4 shadow rounded-3 overflow-hidden svg-wrapper skeleton"></div>';

        $('.btn-load-more').click(() => {
            page++;
            fetchLogos();
        });

        function fetchLogos() {
            if (count > 0 && count >= total) return;
            $('#logos-wrapper').append(skeleton.repeat(itemsPerPage));
            $.get("{{ route('logos.render') }}?page=" + page + "&itemsPerPage=" + itemsPerPage)
                .then(res => {
                    res = JSON.parse(res);
                    if (res.logos && res.logos.length > 0) {
                        var html = "";
                        var logos = res.logos;

                        favorites = res.favorites;
                        
                        logos.forEach((logo, i) => {
                            html += 
                                '<div id="logo-' + logo.id + '" class="mb-4 shadow rounded-3 overflow-hidden svg-wrapper ' + (logo.favorite ? 'favorite' : '') + '">' + 
                                    '<img src="' + logo.svg + '" />' +
                                    '<div class="svg-btn-group d-flex">' + 
                                        '<button class="btn btn-icon btn-primary-transparent rounded-pill btn-wave me-2 btn-favorite" onclick="toggleFavorite(this, ' + logo.id + ');"><i class="ri-heart-line"></i></button>' +
                                        '<a href="/logos/' + logo.id + '/edit" class="btn btn-icon btn-secondary-transparent rounded-pill btn-wave"><i class="ri-edit-line"></i></a>' +
                                    '</div>' + 
                                '</div>';
                        })

                        $('#logos-wrapper .skeleton').remove();
                        $('#logos-wrapper').append(html);

                        count += logos.length;
                        total = res.total;
                    }

                }).catch(err => {
                    console.log(err);
                });
        }

        function toggleFavorite(e, i) {
            var parent = $(e).closest('.svg-wrapper');
            if (parent.hasClass('favorite')) {
                parent.removeClass('favorite');
                favorites = favorites.filter(item => item !== i);
            }
            else {
                parent.addClass('favorite');
                favorites.push(i);
                favorites = [...new Set(favorites)];
            }

            $.post("{{ route('logos.favorites') }}", JSON.stringify({
                favorites: favorites,
                _token: $('meta[name="csrf-token"]').attr('content')
            }), function(response) {
                // Handle the response from the server
                console.log('Response:', response);
            }, 'json');
        }

        fetchLogos();
    </script>
@endsection
