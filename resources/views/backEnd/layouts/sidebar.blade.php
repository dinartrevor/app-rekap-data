<!-- ======= Header ======= -->
<header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="{{route('dashboard')}}" class="logo d-flex align-items-center text-center">
        {{-- <img src="{{asset('assets/img/login.png')}}" alt="Log In Megastore" class="img-logo"> --}}
        <span class="d-none d-lg-block">POLMER SIRAIT LAW </span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->
    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">
        <li class="nav-item dropdown pe-3">
          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="{{Auth::user()->image_url}}" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2">{{Auth::user()->name}}</span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6>{{Auth::user()->name}}</h6>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="{{route('profile.index')}}">
                <i class="bi bi-person"></i>
                <span>My Profile</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="#">
                <i class="bi bi-box-arrow-right"></i>
                <form action="{{ route('logout') }}" method="POST">
					@csrf
					<button type="submit" class="dropdown-item">Sign Out</button>
				</form>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->
      </ul>
    </nav><!-- End Icons Navigation -->

</header><!-- End Header -->

  <!-- Main Sidebar Container -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        @can('dashboard')
        <li class="nav-item">
          <a class="nav-link  {{ (request()->is('dashboard*')) ? 'collapsed' : '' }}" href="{{route('dashboard')}}">
            <i class="bi bi-grid"></i>
            <span>Dashboard</span>
          </a>
        </li><!-- End Dashboard Nav -->
        @endcan

        @can('user-management')
        <li class="nav-item">
            <a class="nav-link {{ (request()->is('user_management*')) ? 'collapsed' : '' }}" data-bs-target="#user-management-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-people-fill"></i><span>User Management</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="user-management-nav" class="nav-content collapse {{ (request()->is('user_management*')) ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                @can('user-list')
                <li>
                    <a href="{{route('user.index')}}"  class="{{ (request()->is('user_management/user*')) ? 'active' : '' }}">
                    <i class="bi bi-circle"></i><span>User</span>
                    </a>
                </li>
                @endcan
                @can('role-list')
                <li>
                    <a href="{{route('role.index')}}""  class="{{ (request()->is('user_management/role*')) ? 'active' : '' }}">
                    <i class="bi bi-circle"></i><span>Role</span>
                    </a>
                </li>
                @endcan
                @can('permission-list')
                <li>
                    <a href="{{route('permission.index')}}"" class="{{ (request()->is('user_management/permission*')) ? 'active' : '' }}">
                    <i class="bi bi-circle"></i><span>Permission</span>
                    </a>
                </li>
                @endcan
            </ul>
        </li><!-- End Components Nav -->
      @endcan
      @can('legal-case-list')
      <li class="nav-item">
        <a class="nav-link  {{ (request()->is('legal_case*')) ? 'collapsed' : '' }}" href="{{route('legal_case.index')}}">
          <i class="bi bi-briefcase-fill"></i>
          <span>Pencatatan Kasus</span>
        </a>
      </li><!-- End Dashboard Nav -->
      @endcan
    </ul>
  </aside><!-- End Sidebar-->
