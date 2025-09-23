<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <li class="nav-item">
      <a class="nav-link" href="{{route('admin.dashboard')}}">
        <i class="icon-grid menu-icon"></i>
        <span class="menu-title">Dashboard</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#masters" aria-expanded="false" aria-controls="masters">
        <i class="icon-columns menu-icon"></i>
        <span class="menu-title">Masters</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="masters">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="{{route('admin.adminProductList')}}">Product List</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{route('admin.subCategoryList')}}">Part List</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{route('admin.CategoryList')}}">Part Type List</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{route('admin.BrandList')}}">Make List</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{route('admin.modelList')}}">Model List</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{route('admin.makeYearList')}}">Generation List</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{route('admin.InterchangeProduct')}}">Interchange Product</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{route('admin.addProductCatalogue')}}">Add Brand Catalogue</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{route('admin.addPartCatalogue')}}">Add Part Catalogue</a></li>
          <!-- <li class="nav-item"> <a class="nav-link" href="{{route('admin.subGenerationList')}}">Sub Generation List</a></li> -->
        </ul>
      </div>
    </li>

    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
        <i class="icon-head menu-icon"></i>
        <span class="menu-title">User Management</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="ui-basic">
        <ul class="nav flex-column sub-menu">
          <!-- <li class="nav-item"> <a class="nav-link" href="{{route('admin.userList')}}">Customer List</a></li> -->
          <li class="nav-item"> <a class="nav-link" href="{{route('admin.sellerList')}}">Seller List</a></li>
          <li class="nav-item"> <a class="nav-link" href="{{route('admin.allRequest')}}">Request List</a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#form-elements" aria-expanded="false" aria-controls="form-elements">
        <i class="icon-open menu-icon"></i>
        <span class="menu-title">Parent Brand</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="form-elements">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="{{route('admin.parentList')}}">Parent List</a></li>
        </ul>
      </div>
    </li>
    {{--<li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#form-elements" aria-expanded="false" aria-controls="form-elements">
        <i class="icon-open menu-icon"></i>
        <span class="menu-title">Group</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="form-elements">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="{{route('admin.groupList')}}">Groups</a></li>
          <li class="nav-item"><a class="nav-link" href="{{route('admin.uniqueProductList')}}">Unique Product</a></li>
        </ul>
      </div>
    </li>--}}
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#location" aria-expanded="false" aria-controls="form-elements">
        <i class="icon-contract menu-icon"></i>
        <span class="menu-title">Location</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="location">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="{{route('admin.countryList')}}">Country List</a></li>
          <li class="nav-item"><a class="nav-link" href="{{route('admin.cityList')}}">City List</a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item">
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
    </li>
    
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