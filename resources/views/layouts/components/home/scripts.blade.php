<!-- SCROLL-TO-TOP -->
<div class="scrollToTop">
    <span class="arrow"><i class="ri-arrow-up-s-fill fs-20"></i></span>
</div>
<div id="responsive-overlay"></div>

<!-- JQUERY -->
<script src="{{ asset('build/assets/libs/jquery/jquery.min.js') }}"></script>

<!-- POPPER JS -->
<script src="{{ asset('build/assets/libs/@popperjs/core/umd/popper.min.js') }}"></script>

<!-- BOOTSTRAP JS -->
<script src="{{ asset('build/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- COLOR PICKER JS -->
<script src="{{ asset('build/assets/libs/@simonwep/pickr/pickr.es5.min.js') }}"></script>

<!-- CHOICES JS -->
<script src="{{ asset('build/assets/libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>

<!-- NODE WAVES JS -->
<script src="{{ asset('build/assets/libs/node-waves/waves.min.js') }}"></script>

<!-- SIMPLEBAR JS -->
<script src="{{ asset('build/assets/libs/simplebar/simplebar.min.js') }}"></script>

<!-- DEFAULTMENU JS -->
@vite('resources/assets/js/defaultmenu.js')

<script>
    $.ajaxSetup({
        headers: {
            'Content-Type': 'application/json',
            // You can add other headers as needed
            // 'Authorization': 'Bearer YourAccessToken',
        }
    });
</script>
