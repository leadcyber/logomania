@extends('layouts.auth-master')

@section('styles')
    <!-- SWIPER CSS -->
    <link rel="stylesheet" href="{{ asset('build/assets/libs/swiper/swiper-bundle.min.css') }}">
@endsection

@section('content')
@section('error-body')

    <body class="bg-white">
    @endsection

    <div class="row authentication mx-0">

        <div class="col-xxl-7 col-xl-7 col-lg-12">
            <div class="row justify-content-center align-items-center h-100">
                <div class="col-xxl-6 col-xl-7 col-lg-7 col-md-7 col-sm-8 col-12">
                    <div class="p-5">
                        <div class="mb-3">
                            <a href="{{ url('/') }}">
                                <img src="{{ asset('build/assets/images/brand-logos/desktop-logo.png') }}"
                                    alt="" class="authentication-brand desktop-logo">
                                <img src="{{ asset('build/assets/images/brand-logos/desktop-dark.png') }}"
                                    alt="" class="authentication-brand desktop-dark">
                            </a>
                        </div>
                        <p class="h5 fw-semibold mb-2">Sign In</p>
                        <p class="mb-3 text-muted op-7 fw-normal">Welcome back Jhon !</p>
                        <div class="btn-list">
                            <button class="btn btn-light"><svg class="google-svg" xmlns="http://www.w3.org/2000/svg"
                                    width="2443" height="2500" preserveAspectRatio="xMidYMid" viewBox="0 0 256 262">
                                    <path fill="#4285F4"
                                        d="M255.878 133.451c0-10.734-.871-18.567-2.756-26.69H130.55v48.448h71.947c-1.45 12.04-9.283 30.172-26.69 42.356l-.244 1.622 38.755 30.023 2.685.268c24.659-22.774 38.875-56.282 38.875-96.027" />
                                    <path fill="#34A853"
                                        d="M130.55 261.1c35.248 0 64.839-11.605 86.453-31.622l-41.196-31.913c-11.024 7.688-25.82 13.055-45.257 13.055-34.523 0-63.824-22.773-74.269-54.25l-1.531.13-40.298 31.187-.527 1.465C35.393 231.798 79.49 261.1 130.55 261.1" />
                                    <path fill="#FBBC05"
                                        d="M56.281 156.37c-2.756-8.123-4.351-16.827-4.351-25.82 0-8.994 1.595-17.697 4.206-25.82l-.073-1.73L15.26 71.312l-1.335.635C5.077 89.644 0 109.517 0 130.55s5.077 40.905 13.925 58.602l42.356-32.782" />
                                    <path fill="#EB4335"
                                        d="M130.55 50.479c24.514 0 41.05 10.589 50.479 19.438l36.844-35.974C195.245 12.91 165.798 0 130.55 0 79.49 0 35.393 29.301 13.925 71.947l42.211 32.783c10.59-31.477 39.891-54.251 74.414-54.251" />
                                </svg>Sign In with google</button>
                            <button class="btn btn-icon btn-light"><i class="ri-facebook-fill"></i></button>
                            <button class="btn btn-icon btn-light"><i class="ri-twitter-fill"></i></button>
                        </div>
                        <div class="text-center my-5 authentication-barrier">
                            <span>OR</span>
                        </div>
                        <div class="row gy-3">
                            <div class="col-xl-12 mt-0">
                                <label for="signin-username" class="form-label text-default">User Name</label>
                                <input type="text" class="form-control form-control-lg" id="signin-username"
                                    placeholder="user name">
                            </div>
                            <div class="col-xl-12 mb-3">
                                <label for="signin-password" class="form-label text-default d-block">Password<a
                                        href="{{ url('reset-password') }}" class="float-end text-danger">Forget
                                        password ?</a></label>
                                <div class="input-group">
                                    <input type="password" class="form-control form-control-lg" id="signin-password"
                                        placeholder="password">
                                    <button class="btn btn-light" type="button"
                                        onclick="createpassword('signin-password',this)" id="button-addon2"><i
                                            class="ri-eye-off-line align-middle"></i></button>
                                </div>
                                <div class="mt-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value=""
                                            id="defaultCheck1">
                                        <label class="form-check-label text-muted fw-normal" for="defaultCheck1">
                                            Remember password ?
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12 d-grid mt-2">
                                <a href="{{ url('/signin') }}" class="btn btn-lg btn-primary">Sign In</a>
                            </div>
                        </div>
                        <div class="text-center">
                            <p class="fs-12 text-muted mt-4">Dont have an account? <a href="{{ url('/signup') }}"
                                    class="text-primary">Sign Up</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-5 col-xl-5 col-lg-5 d-xl-block d-none px-0">
            <div class="authentication-cover">
                <div class="aunthentication-cover-content rounded">
                    <div class="swiper keyboard-control">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div
                                    class="text-fixed-white text-center p-5 d-flex align-items-center justify-content-center">
                                    <div>
                                        <div class="mb-5">
                                            <img src="{{ asset('build/assets/images/authentication/2.png') }}"
                                                class="authentication-image" alt="">
                                        </div>
                                        <h6 class="fw-semibold text-fixed-white">Sign In</h6>
                                        <p class="fw-normal fs-14 op-7"> Lorem ipsum dolor sit amet, consectetur
                                            adipisicing elit. Ipsa eligendi expedita aliquam quaerat nulla voluptas
                                            facilis. Porro rem voluptates possimus, ad, autem quae culpa architecto,
                                            quam labore blanditiis at ratione.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div
                                    class="text-fixed-white text-center p-5 d-flex align-items-center justify-content-center">
                                    <div>
                                        <div class="mb-5">
                                            <img src="{{ asset('build/assets/images/authentication/3.png') }}"
                                                class="authentication-image" alt="">
                                        </div>
                                        <h6 class="fw-semibold text-fixed-white">Sign In</h6>
                                        <p class="fw-normal fs-14 op-7"> Lorem ipsum dolor sit amet, consectetur
                                            adipisicing elit. Ipsa eligendi expedita aliquam quaerat nulla voluptas
                                            facilis. Porro rem voluptates possimus, ad, autem quae culpa architecto,
                                            quam labore blanditiis at ratione.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div
                                    class="text-fixed-white text-center p-5 d-flex align-items-center justify-content-center">
                                    <div>
                                        <div class="mb-5">
                                            <img src="{{ asset('build/assets/images/authentication/2.png') }}"
                                                class="authentication-image" alt="">
                                        </div>
                                        <h6 class="fw-semibold text-fixed-white">Sign In</h6>
                                        <p class="fw-normal fs-14 op-7"> Lorem ipsum dolor sit amet, consectetur
                                            adipisicing elit. Ipsa eligendi expedita aliquam quaerat nulla voluptas
                                            facilis. Porro rem voluptates possimus, ad, autem quae culpa architecto,
                                            quam labore blanditiis at ratione.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-pagination"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <!-- SWIPER JS -->
    <script src="{{ asset('build/assets/libs/swiper/swiper-bundle.min.js') }}"></script>

    <!-- INTERNAL AUTHENTICATION JS -->
    @vite('resources/assets/js/authentication.js')


    <!-- SHOW PASSWORD JS -->
    @vite('resources/assets/js/show-password.js')
@endsection
