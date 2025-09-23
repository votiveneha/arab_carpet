<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <li class="nav-item">
      <a class="nav-link" href="{{route('seller.dashboard')}}">
        <i class="icon-grid menu-icon"></i>
        <span class="menu-title">{{ __('messages.dashboard') }} </span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{route('seller.myProfile')}}">
        <i class="icon-head menu-icon"></i>
        <span class="menu-title">{{ __('messages.my_profile') }}</span>
      </a>
    </li>
    <!-- <li class="nav-item">
      <a class="nav-link" href="{{route('seller.productMaster')}}">
        <i class="icon-bar-graph menu-icon"></i>
        <span class="menu-title">Product Catalogue</span>
      </a>
    </li> -->
    <li class="nav-item">
      <a class="nav-link" href="{{route('seller.getAllProduct')}}">
        <i class="icon-bar-graph menu-icon"></i>
        <span class="menu-title">{{ __('messages.product_catalogue') }}</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{route('seller.productList')}}">
        <i class="icon-open menu-icon"></i>
        <span class="menu-title">{{ __('messages.product') }}</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{route('seller.myRequest')}}">
        <i class="icon-grid-2 menu-icon"></i>
        <span class="menu-title">{{ __('messages.request_list') }}</span>
      </a>
    </li>
    
    <!-- <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#charts" aria-expanded="false" aria-controls="charts">
        <i class="icon-bar-graph menu-icon"></i>
        <span class="menu-title">Policy</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="charts">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="{{route('admin.policyList')}}">Policy List</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{route('admin.addPolicy')}}">Add Policy</a></li>
        </ul>
      </div>
    </li> -->
    
    <!--li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#charts" aria-expanded="false" aria-controls="charts">
        <i class="icon-bar-graph menu-icon"></i>
        <span class="menu-title">Charts</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="charts">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="pages/charts/chartjs.html">ChartJs</a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#tables" aria-expanded="false" aria-controls="tables">
        <i class="icon-grid-2 menu-icon"></i>
        <span class="menu-title">Tables</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="tables">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="pages/tables/basic-table.html">Basic table</a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#icons" aria-expanded="false" aria-controls="icons">
        <i class="icon-contract menu-icon"></i>
        <span class="menu-title">Icons</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="icons">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="pages/icons/mdi.html">Mdi icons</a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
        <i class="icon-head menu-icon"></i>
        <span class="menu-title">User Pages</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="auth">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="pages/samples/login.html"> Login </a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/samples/register.html"> Register </a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#error" aria-expanded="false" aria-controls="error">
        <i class="icon-ban menu-icon"></i>
        <span class="menu-title">Error pages</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="error">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="pages/samples/error-404.html"> 404 </a></li>
          <li class="nav-item"> <a class="nav-link" href="pages/samples/error-500.html"> 500 </a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="../../../docs/documentation.html">
        <i class="icon-paper menu-icon"></i>
        <span class="menu-title">Documentation</span>
      </a>
    </li-->
  </ul>
</nav>