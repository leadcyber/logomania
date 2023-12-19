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

        .svg-wrapper,
        .layout-example {
            max-width: 380px;
            max-height: 240px;
            position: relative;
            cursor: pointer;
            border-radius: 15px;
            overflow: hidden;
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

        .palette-example,
        .like,
        .icon-example {
            overflow: hidden;
            cursor: pointer;
        }

        .icon-example img {
            width: 80px;
            height: 80px;
            object-fit: contain;
            padding: 5px;
        }

        .like img,
        .layout img {
            width: 100%
        }

        .palette-example img {
            max-width: 140px;
            overflow: hidden;
        }

        .layout-example img {
            width: 100%;
        }

        .subcategory[parent=color] label {
            font-size: 16px;
        }

        input[type=color] {
            width: 40px;
            height: 40px;
        }

        .subcategory {
            display: flex;
            flex-direction: column;
            height: 100%;
        }
    </style>
@endsection

@section('content')
    <div class="logo-editor overflow-hidden">
        <div class="row">
            <!-- Left Sidebar -->
            <div class="col-xl-4 col-lg-6 col-md-12 col-xs-12">
                <div class="row asidebar">
                    <div class="left-sidebar col-md-5 border vh-100 pe-0 overflow-auto">
                        <!-- Sidebar content -->
                        <ul class="categories">
                            <li class="category" category="layout">
                                <i class="bx bx-layer list-menu__icon me-2"></i>
                                <span class="list-menu__label">Layouts</span>
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
                            <li class="category" category="color">
                                <i class="bx bx-color list-menu__icon me-2"></i>
                                <span class="list-menu__label">Color</span>
                            </li>
                            <li class="category" category="like">
                                <i class="bx bx-heart list-menu__icon me-2"></i>
                                <span class="list-menu__label">Likes</span>
                            </li>
                            <button class="btn btn-danger btn-reset">Reset Logo</button>
                        </ul>
                    </div>

                    <!-- Subcategory Bar -->
                    <div class="subcategories col-md-7 border vh-100 overflow-auto">
                        <!-- Initially hidden, shown when a category is selected -->
                        <div class="subcategory" parent="layout">
                            <h5 class="pb-3">Layouts</h5>
                            <div class="w-100 overflow-auto">
                                <div class="items-wrapper d-flex flex-wrap justify-content-around"></div>
                            </div>
                        </div>
                        <div class="subcategory" parent="name">
                            <h5 class="pb-3">Logo Name</h5>
                            <input type="text" class="form-control form-control-lg mb-3" id="logoname" name="logoname"
                                placeholder="Logo Name">
                        </div>
                        <div class="subcategory" parent="font">
                            <h5 class="pb-3">Fonts</h5>
                            <div class="w-100 overflow-auto">
                                <div class="items-wrapper d-flex flex-wrap justify-content-center"></div>
                                <div class="text-center">
                                    <div class="spinner-border px-2" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="subcategory" parent="icon">
                            <h5 class="pb-3">Icons</h5>
                            <div class="w-100 overflow-auto">
                                <div class="items-wrapper d-flex flex-wrap justify-content-around"></div>
                                <div class="text-center">
                                    <div class="spinner-border px-2" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="subcategory" parent="palette">
                            <h5 class="pb-3">Palettes</h5>
                            <div class="w-100 overflow-auto">
                                <div class="items-wrapper d-flex flex-wrap justify-content-around"></div>
                                <div class="text-center">
                                    <div class="spinner-border px-2" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="subcategory" parent="color">
                            <h5 class="pb-3">Color</h5>
                            <div class="items-wrapper d-flex flex-wrap justify-content-around">
                                <div class="w-100 d-flex align-items-center justify-content-between mb-3">
                                    <label for="background">Background Color</label>
                                    <input type="color" class="form-control form-control-color border-0" id="background"
                                        value="#136ad0" title="Background color">
                                </div>
                                <div class="w-100 d-flex align-items-center justify-content-between mb-3">
                                    <label for="text1">Text1 Color</label>
                                    <input type="color" class="form-control form-control-color border-0" id="text1"
                                        value="#136ad0" title="Text1 color">
                                </div>
                                <div class="w-100 d-flex align-items-center justify-content-between mb-3">
                                    <label for="text2">Text2 Color</label>
                                    <input type="color" class="form-control form-control-color border-0" id="text2"
                                        value="#136ad0" title="Text2 color">
                                </div>
                                <div class="w-100 d-flex align-items-center justify-content-between mb-3 icon-color">
                                    <label for="icon">Icon Color</label>
                                    <input type="color" class="form-control form-control-color border-0" id="icon"
                                        value="#136ad0" title="Icon color">
                                </div>
                            </div>
                        </div>
                        <div class="subcategory" parent="like">
                            <h5 class="pb-3">Likes</h5>
                            <div class="w-100 overflow-auto">
                                <div class="items-wrapper d-flex flex-wrap justify-content-center"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="col-xl-8 col-lg-6 col-md-12 vh-100 overflow-auto d-flex align-items-center justify-content-center">
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
        var isLoading = false;
        var logoJSON = '{!! json_encode($logo) !!}';
        var logo = JSON.parse(logoJSON);

        var categoryValues = {
            like: {
                api: "{{ route('logos.render') }}",
                selected: 0,
                page: 1,
                count: 0,
                itemsPerPage: 10,
                fetch: fetchLogos,
                items: [],
            },
            font: {
                api: "{{ route('fonts.list') }}",
                selected: 0,
                page: 1,
                count: 0,
                itemsPerPage: 10,
                fetch: fetchFonts,
                items: [],
            },
            palette: {
                api: "{{ route('logos.render.palette') }}",
                selected: 0,
                page: 1,
                count: 0,
                itemsPerPage: 20,
                fetch: fetchPalettes,
                items: [],
            },
            icon: {
                api: "{{ route('icons.list') }}",
                selected: 0,
                page: 1,
                count: 0,
                itemsPerPage: 36,
                fetch: fetchIcons,
                items: [],
            },
            layout: {
                api: "{{ route('logos.render.layout') }}",
                fetch: fetchLayouts,
                items: [],
            },
        }

        function toggleCategories() {
            if (activeCategory == 'color' && logo.icon.type == 'color') {
                $('.icon-color').css("visibility", "hidden");
            } else {
                $('.icon-color').css("visibility", "visible");
            }
            $('.subcategory').hide();
            $('.subcategory[parent=' + activeCategory + ']').show();
        }

        function init() {
            $('.subcategory .overflow-auto').on('scroll', function(e) {
                if (this.scrollTop + this.clientHeight >= this.querySelector('.items-wrapper').clientHeight) {
                    if (categoryValues[activeCategory].fetch && !isLoading) {
                        categoryValues[activeCategory].page++;
                        categoryValues[activeCategory].fetch();
                    }
                }
            });
            // new SimpleBar(document.querySelector(".subcategory"), {
            //     autoHide: false
            // });
            if (logo) {
                $('#logoname').val(logo.text);
                $('#background').val('#' + logo.palette.background);
                $('#icon').val('#' + logo.palette.icon);
                $('#text1').val('#' + logo.palette.text1);
                $('#text2').val('#' + logo.palette.text2);

                $('input[type=color]').on('input', function() {
                    var color = $(this).val();
                    var id = $(this).attr('id');
                    logo.palette[id] = color.replace('#', '');
                    renderLogo();
                });

                $('#logoname').on('input', function(e) {
                    logo.text = e.target.value;
                    renderLogo();
                });

                $('.category').click(function() {
                    activeCategory = $(this).attr('category');
                    toggleCategories();
                });
            }

            $('.btn-reset').click(function() {
                logo = JSON.parse(logoJSON);
                $('#logoname').val(logo.text);
                $('#background').val('#' + logo.palette.background);
                $('#icon').val('#' + logo.palette.icon);
                $('#text1').val('#' + logo.palette.text1);
                $('#text2').val('#' + logo.palette.text2);
                renderLogo();
            });
        }

        function fetchLogos() {
            var likeCategory = categoryValues.like;
            if (likeCategory.count > 0 && likeCategory.count >= likeCategory.total) return;

            var skeleton = '<div class="mb-4 shadow rounded-3 overflow-hidden svg-wrapper skeleton"></div>';
            var wrapperClass = '.subcategory[parent=like] .items-wrapper';
            $(wrapperClass).append(skeleton.repeat(likeCategory.itemsPerPage));
            isLoading = true;
            $.get(likeCategory.api + "?sort=favorite&page=" + likeCategory.page + "&itemsPerPage=" + likeCategory
                    .itemsPerPage)
                .then(res => {
                    res = JSON.parse(res);
                    if (res.logos && res.logos.length > 0) {
                        var html = "";
                        var logos = res.logos;

                        logos.forEach((logo, i) => {
                            html +=
                                '<a href="/logos/' + logo.id + '/edit"><div id="svg-' + logo.id +
                                '" class="like mb-4 shadow rounded-3 overflow-hidden svg-wrapper ' + (logo
                                    .favorite ? 'favorite' : '') + '" data-id="' + logo.id + '">' +
                                '<img src="' + logo.svg + '" />' +
                                '<div class="svg-btn-group d-flex">' +
                                '<button class="btn btn-icon btn-primary-transparent rounded-pill btn-wave me-2 btn-favorite"><i class="ri-heart-line"></i></button>' +
                                // '<button class="btn btn-icon btn-secondary-transparent rounded-pill btn-wave"><i class="ri-edit-line"></i></button>' +
                                '</div>' +
                                '</div></a>';
                        })

                        $(wrapperClass + ' .skeleton').remove();
                        $(wrapperClass).append(html);

                        $('.like').click(function() {
                            likeCategory.selected = $(this).data('id');
                            renderLogo();
                        });

                        likeCategory.count += logos.length;
                        likeCategory.total = res.total;
                    }

                    isLoading = false;
                }).catch(err => {
                    isLoading = false;
                    console.log(err);
                });
        }

        function fetchFonts() {
            var fontCategory = categoryValues.font;
            if (fontCategory.count > 0 && fontCategory.count >= fontCategory.total) return;

            var wrapperClass = '.subcategory[parent=font] .items-wrapper';
            isLoading = true;
            $.get(fontCategory.api + "?page=" + fontCategory.page + "&itemsPerPage=" + fontCategory.itemsPerPage)
                .then(res => {
                    res = JSON.parse(res);
                    if (res.fonts && res.fonts.length > 0) {
                        var html = "";
                        var styles = "";
                        var fonts = res.fonts;
                        fontCategory.items = [...fontCategory.items, ...fonts];
                        fonts.forEach((font, i) => {
                            styles += font.style;
                            html +=
                                '<span class="mb-4 shadow border border-secondary rounded-3 font-example" data-id="' +
                                font.id + '" style="font-family: ' + font.fontname + ';">' + font.fontname +
                                '</span>'
                        });
                        const styleElement = $('<style>');
                        styleElement.text(styles);
                        $('head').append(styleElement);
                        $(wrapperClass).append(html);

                        $('.font-example').click(function() {
                            fontCategory.selected = $(this).data('id');
                            font = fontCategory.items.filter(item => item.id == fontCategory.selected)[0];
                            logo.font = font;
                            logo.font_id = font.id;
                            renderLogo();
                        });

                        fontCategory.count += fonts.length;
                        fontCategory.total = res.total;
                    }

                    isLoading = false;
                }).catch(err => {
                    isLoading = false;
                    console.log(err);
                });
        }

        function fetchIcons() {
            var iconCategory = categoryValues.icon;
            if (iconCategory.count > 0 && iconCategory.count >= iconCategory.total) return;

            var wrapperClass = '.subcategory[parent=icon] .items-wrapper';
            isLoading = true;
            $.get(iconCategory.api + "?page=" + iconCategory.page + "&itemsPerPage=" + iconCategory.itemsPerPage)
                .then(res => {
                    res = JSON.parse(res);
                    if (res.icons && res.icons.length > 0) {
                        var html = "";
                        var styles = "";
                        var icons = res.icons;
                        iconCategory.items = [...iconCategory.items, ...icons];

                        icons.forEach((icon, i) => {
                            html +=
                                '<div class="mb-4 shadow border border-secondary rounded-3 icon-example" data-id="' +
                                icon.id + '">' +
                                '<img src="data:image/svg+xml;base64,' + btoa(icon.svg) + '"/>' +
                                '</div>'
                        });
                        $(wrapperClass).append(html);

                        $('.icon-example').click(function() {
                            iconCategory.selected = $(this).data('id');
                            icon = iconCategory.items.filter(item => item.id == iconCategory.selected)[0];
                            logo.icon = icon;
                            logo.icon_id = icon.id;
                            logo.type = "text_icon_top";
                            renderLogo();
                        });

                        iconCategory.count += icons.length;
                        iconCategory.total = res.total;
                    }

                    isLoading = false;
                }).catch(err => {
                    isLoading = false;
                    console.log(err);
                });
        }

        function fetchPalettes() {
            var paletteCategory = categoryValues.palette;
            if (paletteCategory.count > 0 && paletteCategory.count >= paletteCategory.total) return;

            var wrapperClass = '.subcategory[parent=palette] .items-wrapper';
            isLoading = true;
            $.get(paletteCategory.api + "?page=" + paletteCategory.page + "&itemsPerPage=" + paletteCategory.itemsPerPage)
                .then(res => {
                    res = JSON.parse(res);
                    if (res.palettes && res.palettes.length > 0) {
                        var html = "";
                        var styles = "";
                        var palettes = res.palettes;
                        paletteCategory.items = [...paletteCategory.items, ...palettes];

                        palettes.forEach((palette, i) => {
                            html += '<div class="mb-4 shadow rounded-3 palette-example" data-id="' + palette
                                .id + '">' +
                                '<img src="' + palette.svg + '"/>' +
                                '</div>'
                        });
                        $(wrapperClass).append(html);

                        $('.palette-example').click(function() {
                            paletteCategory.selected = $(this).data('id');
                            palette = paletteCategory.items.filter(item => item.id == paletteCategory.selected)[
                                0];
                            logo.palette = palette;
                            logo.palette_id = palette.id;
                            $('#background').val('#' + palette.background);
                            $('#icon').val('#' + palette.icon);
                            $('#text1').val('#' + palette.text1);
                            $('#text2').val('#' + palette.text2);
                            renderLogo();
                        });

                        paletteCategory.count += palettes.length;
                        paletteCategory.total = res.total;
                    }

                    isLoading = false;
                }).catch(err => {
                    isLoading = false;
                    console.log(err);
                });
        }

        function fetchLayouts() {
            var layoutCategory = categoryValues.layout;

            var wrapperClass = '.subcategory[parent=layout] .items-wrapper';
            isLoading = true;
            $.get(layoutCategory.api)
                .then(res => {
                    res = JSON.parse(res);
                    if (res.layouts && res.layouts.length > 0) {
                        var html = "";
                        var layouts = res.layouts;
                        layoutCategory.items = layouts;

                        layouts.forEach((layout, i) => {
                            html += '<div class="mb-4 shadow rounded-3 layout-example" data-id="' + layout
                                .type + '">' +
                                '<img src="' + layout.svg + '"/>' +
                                '</div>'
                        });
                        $(wrapperClass).append(html);

                        $('.layout-example').click(function() {
                            layoutCategory.selected = $(this).data('id');
                            logo.type = layoutCategory.selected;
                            renderLogo();
                        });
                    }

                    isLoading = false;
                }).catch(err => {
                    isLoading = false;
                    console.log(err);
                });
        }

        function renderLogo() {
            console.log(logo);
            var texts = logo.text.trim().split(/\s+/);
            $('#logo-wrapper *[part=text1]').text(texts[0] ? texts[0] : '');
            $('#logo-wrapper *[part=text1]').css("color", '#' + logo.palette.text1);
            $('#logo-wrapper *[part=text1]').css("font-family", logo.font.fontname);
            $('#logo-wrapper *[part=text2]').text(texts[1] ? texts.slice(1).join("") : '');
            $('#logo-wrapper *[part=text2]').css("color", '#' + logo.palette.text2);
            $('#logo-wrapper *[part=text2]').css("font-family", logo.font.fontname);

            $('#logo-wrapper *[part=background]').css("background", '#' + logo.palette.background);
            var flexDirection = "row";
            if (logo.type == 'text_only') $('#logo-wrapper *[part=icon]').hide();
            else $('#logo-wrapper *[part=icon]').show();
            if (logo.type == "text_icon_top") flexDirection = "column";
            $('#logo-wrapper *[part=background]').css("flex-direction", flexDirection);

            var svg = logo.icon.svg;
            if (!svg)
                svg = atob(logo.icon.blob);
            if (logo.icon.type == 'fillable') {
                svg = svg.replace(/fill="#[0-9a-fA-F]{3,6}"/g, 'fill="#' + logo.palette.icon + '"');
                svg = svg.replace(/fill:#[0-9a-fA-F]{3,6}/g, 'fill:#' + logo.palette.icon);
                svg = svg.replace(/stroke="#[0-9a-fA-F]{3,6}"/g, 'stroke="#' + logo.palette.icon + '"');
                svg = svg.replace(/stroke:#[0-9a-fA-F]{3,6}/g, 'stroke:#' + logo.palette.icon);
                svg = svg.replace(/<svg(.*?)>/g, function(match, attributes) {
                    if (attributes.indexOf('fill=') === -1) {
                        return '<svg fill="#' + logo.palette.icon + '" ' + attributes + '>';
                    } else {
                        return match;
                    }
                });
            }
            $('#logo-wrapper *[part=icon]').attr('src', 'data:image/svg+xml;base64,' + btoa(svg));
        }

        var activeCategory = "layout";
        toggleCategories();
        ['like', 'icon', 'font', 'palette', 'layout'].forEach(category => {
            if (categoryValues[category].fetch)
                categoryValues[category].fetch();
        });

        init();
    </script>
@endsection
