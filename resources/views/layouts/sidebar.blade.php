<nav class="sidebar-nav scroll-sidebar" data-simplebar="">
  <ul id="sidebarnav">
    <li class="sidebar-item">
      <a class="sidebar-link @if (request()->is('tournament', 'tournamentlist')) active @endif" href="{{ url('/tournamentlist') }}" aria-expanded="false">
        <i class="ti ti-category"></i>
        <span class="hide-menu">Tournament Types</span>
      </a>
    </li>

        <li class="sidebar-item">
      <a class="sidebar-link @if (request()->is('tournamentMode')) active @endif" href="{{ url('/tournamentMode') }}" aria-expanded="false">
        <i class="ti ti-adjustments-alt"></i>
        <span class="hide-menu">Add Tournament Mode</span>
      </a>
    </li>
    <li class="sidebar-item">
        <a class="sidebar-link @if (request()->is('createTournament')) active @endif" href="{{ url('/createTournament') }}" aria-expanded="false">
            <i class="ti ti-trophy"></i>
            <span class="hide-menu">Create Tournament</span>
        </a>
        </li>
  </ul>
</nav>
