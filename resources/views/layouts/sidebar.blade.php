<nav class="sidebar-nav scroll-sidebar" data-simplebar="">
  <ul id="sidebarnav">
    <li class="nav-small-cap">
      <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
      <span class="hide-menu">Home</span>
    </li>
    <li class="sidebar-item">
      <a class="sidebar-link" href="{{ url('/tournament') }}" aria-expanded="false">
        <i class="ti ti-category"></i>
        <span class="hide-menu">Add Tournament Type</span>
      </a>
    </li>

        <li class="sidebar-item">
      <a class="sidebar-link" href="{{ url('/tournamentMode') }}" aria-expanded="false">
        <i class="ti ti-adjustments-alt"></i>
        <span class="hide-menu">Add Tournament Mode</span>
      </a>
    </li>
    <li class="sidebar-item">
        <a class="sidebar-link" href="{{ url('/createTournament') }}" aria-expanded="false">
            <i class="ti ti-trophy"></i>
            <span class="hide-menu">Create Tournament</span>
        </a>
        </li>

    {{-- <li class="sidebar-item">
      <a class="sidebar-link justify-content-between" href="{{ url('/analytics') }}" aria-expanded="false">
        <div class="d-flex align-items-center gap-3">
          <span class="d-flex"><i class="ti ti-aperture"></i></span>
          <span class="hide-menu">Analytical</span>
        </div>
      </a>
    </li>

    <li class="sidebar-item">
      <a class="sidebar-link justify-content-between" href="{{ url('/ecommerce') }}" aria-expanded="false">
        <div class="d-flex align-items-center gap-3">
          <span class="d-flex"><i class="ti ti-shopping-cart"></i></span>
          <span class="hide-menu">eCommerce</span>
        </div>
      </a>
    </li>

    <li class="sidebar-item">
      <a class="sidebar-link justify-content-between has-arrow" href="javascript:void(0)" aria-expanded="false">
        <div class="d-flex align-items-center gap-3">
          <span class="d-flex"><i class="ti ti-layout-grid"></i></span>
          <span class="hide-menu">Front Pages</span>
        </div>
      </a>
      <ul aria-expanded="false" class="collapse first-level">
        <li class="sidebar-item">
          <a class="sidebar-link justify-content-between" href="{{ url('/homepage') }}">
            <div class="d-flex align-items-center gap-3">
              <div class="round-16 d-flex align-items-center justify-content-center"><i class="ti ti-circle"></i></div>
              <span class="hide-menu">Homepage</span>
            </div>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link justify-content-between" href="{{ url('/about') }}">
            <div class="d-flex align-items-center gap-3">
              <div class="round-16 d-flex align-items-center justify-content-center"><i class="ti ti-circle"></i></div>
              <span class="hide-menu">About Us</span>
            </div>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link justify-content-between" href="{{ url('/blog') }}">
            <div class="d-flex align-items-center gap-3">
              <div class="round-16 d-flex align-items-center justify-content-center"><i class="ti ti-circle"></i></div>
              <span class="hide-menu">Blog</span>
            </div>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link justify-content-between" href="{{ url('/blog-details') }}">
            <div class="d-flex align-items-center gap-3">
              <div class="round-16 d-flex align-items-center justify-content-center"><i class="ti ti-circle"></i></div>
              <span class="hide-menu">Blog Details</span>
            </div>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link justify-content-between" href="{{ url('/contact') }}">
            <div class="d-flex align-items-center gap-3">
              <div class="round-16 d-flex align-items-center justify-content-center"><i class="ti ti-circle"></i></div>
              <span class="hide-menu">Contact Us</span>
            </div>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link justify-content-between" href="{{ url('/portfolio') }}">
            <div class="d-flex align-items-center gap-3">
              <div class="round-16 d-flex align-items-center justify-content-center"><i class="ti ti-circle"></i></div>
              <span class="hide-menu">Portfolio</span>
            </div>
          </a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link justify-content-between" href="{{ url('/pricing') }}">
            <div class="d-flex align-items-center gap-3">
              <div class="round-16 d-flex align-items-center justify-content-center"><i class="ti ti-circle"></i></div>
              <span class="hide-menu">Pricing</span>
            </div>
          </a>
        </li>
      </ul>
    </li>

    <li class="sidebar-item">
      <a class="sidebar-link justify-content-between" href="{{ url('/profile') }}" aria-expanded="false">
        <div class="d-flex align-items-center gap-3">
          <span class="d-flex"><i class="ti ti-user-circle"></i></span>
          <span class="hide-menu">User Profile</span>
        </div>
      </a>
    </li> --}}
  </ul>
</nav>
