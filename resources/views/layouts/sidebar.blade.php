<nav class="sidebar-nav scroll-sidebar" data-simplebar="">
  <ul id="sidebarnav">
    <li class="nav-small-cap">
            <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
            <span class="hide-menu">Home</span>
        </li>
    <li class="sidebar-item">
      <a class="sidebar-link @if (request()->is('tournament', 'tournamenttypelist','edit-tournament')) active @endif" href="{{ url('/tournamenttypelist') }}" aria-expanded="false">
        <i class="ti ti-category"></i>
        <span class="hide-menu">Tournament Types</span>
      </a>
    </li>

        <li class="sidebar-item">
      <a class="sidebar-link @if (request()->is('tournamentmodeList','tournamentMode')) active @endif" href="{{ url('/tournamentmodeList') }}" aria-expanded="false">
        <i class="ti ti-adjustments-alt"></i>
        <span class="hide-menu">Add Tournament Mode</span>
      </a>
    </li>
    <li class="sidebar-item">
        <a class="sidebar-link @if (request()->is('tournamentlist','createTournament')) active @endif" href="{{ url('/tournamentlist') }}" aria-expanded="false">
            <i class="ti ti-trophy"></i>
            <span class="hide-menu">Create Tournament</span>
        </a>
        </li>
  </ul>
</nav>
