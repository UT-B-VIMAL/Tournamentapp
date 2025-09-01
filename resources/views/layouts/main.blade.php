<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="api-base-url" content="{{ config('app.api_base_url') }}">
  <title>@yield('title') - Admin Panel</title>
  <link rel="shortcut icon" type="image/png" href="{{ asset('./assets/images/logos/fav.svg') }}" />

 @yield('styles')
</head>

<body>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">



    <!-- Sidebar Start -->
    <aside class="left-sidebar">
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <a href="./index.html" class="text-nowrap logo-img">
            <img src="assets/images/logos/logo_unity.svg" alt="" />
          </a>
          <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
            <i class="ti ti-x fs-6"></i>
          </div>
        </div>

         @include('layouts.sidebar')

      </div>
    </aside>
    <!--  Sidebar End -->

    <!--  Main wrapper -->
    <div class="body-wrapper">
      <header class="app-header">
        @include('layouts.header')
      </header>
      <div class="body-wrapper-inner">
        <div class="container-fluid">
          @yield('back')
          @yield('content')
          @include('layouts.footer')
        </div>
      </div>
    </div>
  </div>
  <script src="{{ asset('js/common.js') }}"></script>
  <script src="{{ asset('./assets/libs/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ asset('./assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('./assets/js/sidebarmenu.js') }}"></script>
  <script src="{{ asset('./assets/js/app.min.js') }}"></script>
  <script src="{{ asset('./assets/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
  <script src="{{ asset('./assets/libs/simplebar/dist/simplebar.js') }}"></script>
  <script src="{{ asset('./assets/js/dashboard.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
{{-- script --}}
@yield('scripts')
{{-- script --}}
</body>

</html>