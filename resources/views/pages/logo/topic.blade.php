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
            <div class="card custom-card shadow logo-topic">
                <div class="card-header">
                    <div class="card-title">Provide us all about your company.</div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('logo.topic') }}">
                        @csrf
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="company-name" name="company_name">
                            <label for="company-name">Company Name</label>
                        </div>
                        <div class="form-floating mb-3">
                            <textarea type="text" class="form-control" id="desc" name="desc" rows="8"></textarea>
                            <label for="desc">What is the primary function of your business?</label>
                        </div>
                        {{-- <div class="form-floating mb-3">
                            <select class="form-select" id="family1" name="family1_id"
                                aria-label="Floating label select" required>
                                <option value="" selected>Select your industry</option>
                                @foreach ($families as $family)
                                    <option value="{{ $family->id }}">{{ __($family->text_code) }}</option>
                                @endforeach
                            </select>
                            <label for="family1">Primary Industry</label>
                        </div>
                        <div class="form-floating mb-3">
                            <select class="form-select" id="family2" name="family2_id"
                                aria-label="Floating label select" required>
                                <option value="" selected>Select your industry</option>
                                @foreach ($families as $family)
                                    <option value="{{ $family->id }}">{{ __($family->text_code) }}</option>
                                @endforeach
                            </select>
                            <label for="family2">Secondary Industry</label>
                        </div> --}}
                        <button class="btn btn-primary" type="submit">Generate</button>
                    </form>
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
