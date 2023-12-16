@extends('layouts.home-master')

@section('styles')
    <!-- SWIPERJS CSS -->
    <link rel="stylesheet" href="{{ asset('build/assets/libs/swiper/swiper-bundle.min.css') }}">
    <style>
        .categories,
        .subcategories {
            padding: 90px 15px 15px;
            text-align: center;
        }

        .category {
            display: flex;
            align-items: center;
            font-size: 15px;
            font-family: "Montserrat", sans-serif;
            color: #000;
            cursor: pointer;
            margin-bottom: 15px;
            padding: 10px 15px;
            border: none;
            border-radius: 15px;
        }

        .category:hover {
            background-color: rgb(242, 243, 244);
        }

        .category .bx {
            color: var(--primary-color);
            font-size: 20px;
        }

        #logo-wrapper {
            height: 100vh;
            padding: 90px 15px 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            transform: scale(2);
        }

        .svg-wrapper {
            width: 380px;
            height: 240px;
            position: relative;
            cursor: pointer;
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

        .font-example {
            display: flex;
            width: 100%;
            min-height: 60px;
            padding: 10px;
            font-size: 24px;
            overflow: hidden;
            align-items: center;
            justify-content: center;
            line-height: 1;
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
    <div class="logo-editor overflow-hidden">
        <div class="row">
            <!-- Left Sidebar -->
            <div class="col-lg-4 col-md-12">
                <div class="row asidebar">
                    <div class="left-sidebar col-lg-4 col-md-12 border vh-100 pe-0 overflow-auto">
                        <!-- Sidebar content -->
                        <ul class="categories">
                            <li class="category" category="idea">
                                <i class="bx bx-brain list-menu__icon me-2"></i>
                                <span class="list-menu__label">More Ideas</span>
                            </li>
                            <li class="category" category="name">
                                <i class="bx bx-edit list-menu__icon me-2"></i>
                                <span class="list-menu__label">Name</span>
                            </li>
                            <li class="category" category="font">
                                <i class="bx bx-font list-menu__icon me-2"></i>
                                <span class="list-menu__label">Fonts</span>
                            </li>
                            <li class="category" category="icon">
                                <i class="bx bx-image list-menu__icon me-2"></i>
                                <span class="list-menu__label">Icons</span>
                            </li>
                            <li class="category" category="palette">
                                <i class="bx bx-palette list-menu__icon me-2"></i>
                                <span class="list-menu__label">Palettes</span>
                            </li>
                            <li class="category" category="layout">
                                <i class="bx bx-layer list-menu__icon me-2"></i>
                                <span class="list-menu__label">Layouts</span>
                            </li>
                            <li class="category" category="color">
                                <i class="bx bx-color list-menu__icon me-2"></i>
                                <span class="list-menu__label">Color</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Subcategory Bar -->
                    <div class="subcategories col-lg-8 col-md-12 border vh-100 overflow-auto">
                        <!-- Initially hidden, shown when a category is selected -->
                        <div class="subcategory" parent="idea">
                            <h5 class="mb-5">Ideas</h5>
                            <div class="items-wrapper d-flex flex-wrap justify-content-center"></div>
                            <button class="btn btn-primary btn-load-more">Load More..</button>
                        </div>
                        <div class="subcategory" parent="name">
                            <h5 class="mb-5">Name Options</h5>
                            <label for="logoname" class="form-label text-default">Logo Name</label>
                            <input type="text" class="form-control form-control-lg mb-3" id="logoname" name="logoname"
                                placeholder="Logo Name">
                            <button class="btn btn-primary">Change</button>
                        </div>
                        <div class="subcategory" parent="font">
                            <h5 class="mb-5">Fonts</h5>
                            <div class="items-wrapper d-flex flex-wrap justify-content-center"></div>
                            <button class="btn btn-primary btn-load-more">Load More..</button>
                        </div>
                        <div class="subcategory" parent="icon">
                            <h5 class="mb-5">Icons</h5>
                            <div class="items-wrapper d-flex flex-wrap justify-content-center"></div>
                            <button class="btn btn-primary btn-load-more">Load More..</button>
                        </div>
                        <div class="subcategory" parent="palette">
                            <h5 class="mb-5">Palettes</h5>
                            <div class="items-wrapper d-flex flex-wrap justify-content-center"></div>
                            <button class="btn btn-primary btn-load-more">Load More..</button>
                        </div>
                        <div class="subcategory" parent="layout">
                            <h5 class="mb-5">Layouts</h5>
                        </div>
                        <div class="subcategory" parent="color">
                            <h5 class="mb-5">Color</h5>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="col-lg-8 col-md-12 vh-100 overflow-auto">
                <div class="content" id="logo-wrapper">
                    {!! $svg !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- SWIPER JS -->
    <script src="{{ asset('build/assets/libs/swiper/swiper-bundle.min.js') }}"></script>

    <!-- INTERNAL LANDING JS -->
    @vite('resources/assets/js/home.js')

    <script>
        var categoryValues = {
            idea: {
                api: "{{ route('logos.render') }}",
                selected: 0,
                page: 1,
                itemsPerPage: 9,
                fetch: fetchLogos,
                items: [],
            },
            font: {
                api: "{{ route('fonts.rendered') }}",
                selected: 0,
                page: 1,
                itemsPerPage: 9,
                fetch: fetchFonts,
                items: [],
            },
            palette: {
                api: "{{ route('logo.svgs.palette') }}",
                selected: 0,
                page: 1,
                itemsPerPage: 9
            },
            icon: {
                api: "{{ route('logo.svgs.icon') }}",
                selected: 0,
                page: 1,
                itemsPerPage: 9,
                items: [],
            },
        }

        $('.category').click(function() {
            activeCategory = $(this).attr('category');
            toggleCategories();
        });

        $('.btn-load-more').click(function() {
            categoryValues[activeCategory].page++;
            if (categoryValues[activeCategory].fetch)
                categoryValues[activeCategory].fetch();
        });

        function toggleCategories() {
            $('.subcategory').hide();
            $('.subcategory[parent=' + activeCategory + ']').show();
        }

        function fetchLogos() {
            var ideaCategory = categoryValues.idea;
            if (ideaCategory.count > 0 && ideaCategory.count >= ideaCategory.total) return;

            var skeleton = '<div class="mb-4 shadow rounded-3 overflow-hidden svg-wrapper skeleton"></div>';
            var wrapperClass = '.subcategory[parent=idea] .items-wrapper';
            $(wrapperClass).append(skeleton.repeat(ideaCategory.itemsPerPage));
            $.get(ideaCategory.api + "?sort=favorite&page=" + ideaCategory.page + "&itemsPerPage=" + ideaCategory.itemsPerPage)
                .then(res => {
                    res = JSON.parse(res);
                    if (res.logos && res.logos.length > 0) {
                        var html = "";
                        var logos = res.logos;

                        logos.sort((a, b) => b.favorite - a.favorite);

                        logos.forEach((logo, i) => {
                            html +=
                                '<div id="svg-' + logo.id + '" class="idea mb-4 shadow rounded-3 overflow-hidden svg-wrapper ' + (logo.favorite ? 'favorite' : '') + '" data-id="' + logo.id + '">' + 
                                    '<img src="' + logo.svg + '" />' + 
                                    '<div class="svg-btn-group d-flex">' +
                                        '<button class="btn btn-icon btn-primary-transparent rounded-pill btn-wave me-2 btn-favorite"><i class="ri-heart-line"></i></button>' +
                                        // '<button class="btn btn-icon btn-secondary-transparent rounded-pill btn-wave"><i class="ri-edit-line"></i></button>' +
                                    '</div>' +
                                '</div>';
                        })

                        $(wrapperClass + ' .skeleton').remove();
                        $(wrapperClass).append(html);

                        $('.idea').click(function() {
                            ideaCategory.selected = $(this).data('id');
                            renderLogo();
                        });

                        ideaCategory.count += logos.length;
                        ideaCategory.total = res.total;
                    }

                }).catch(err => {
                    console.log(err);
                });
        }

        function fetchFonts() {
            var fontCategory = categoryValues.font;
            if (fontCategory.count > 0 && fontCategory.count >= fontCategory.total) return;

            var wrapperClass = '.subcategory[parent=font] .items-wrapper';
            $.get(fontCategory.api + "?page=" + fontCategory.page + "&itemsPerPage=" + fontCategory.itemsPerPage)
                .then(res => {
                    res = JSON.parse(res);
                    if (res.fonts && res.fonts.length > 0) {
                        var html = "";
                        var styles = "";
                        var fonts = res.fonts;
                        fontCategory.items = [...fontCategory.items, ...fonts];
                        console.log(fontCategory.items);
                        fonts.forEach((font, i) => {
                            var index = font.id;
                            styles += font.style;
                            html += '<span class="mb-4 shadow border border-secondary rounded-3 font-example" data-id="' + font.id + '" style="font-family: ' + font.fontname + ';">' + font.fontname + '</span>'
                        });
                        const styleElement = $('<style>');
                        styleElement.text(styles);
                        $('head').append(styleElement);
                        $(wrapperClass).append(html);

                        $('.font-example').click(function() {
                            fontCategory.selected = $(this).data('id');
                            renderLogo();
                        });

                        fontCategory.count += fonts.length;
                        fontCategory.total = res.total;
                    }

                }).catch(err => {
                    console.log(err);
                });
        }

        function renderLogo() {

        }

        var activeCategory = "idea";
        toggleCategories();
        ['idea', 'icon', 'font', 'palette'].forEach(category => {
            if (categoryValues[category].fetch)
                categoryValues[category].fetch();
        });
    </script>
@endsection
