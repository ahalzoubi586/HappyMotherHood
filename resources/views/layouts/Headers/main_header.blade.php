<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-end me-3 rotate-caret" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute start-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="https://demos.creative-tim.com/soft-ui-dashboard/pages/dashboard.html" target="_blank">
        <img src="{{ asset('assets/img/momm_logo.png') }}" class="navbar-brand-img h-100" alt="main_logo">
        <span class="me-1 font-weight-bold">أمومة سعيدة</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse px-0 w-auto  max-height-vh-100 h-100" id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link {{ str_starts_with(Route::getFacadeRoot()->current()->uri(), 'Dashboard') ? 'active' : '' }}" href="{{ route("dashboard.index") }}">
            <i class="fa fa-home"></i>
            <span class="nav-link-text me-1">الرئيسية</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ str_starts_with(Route::getFacadeRoot()->current()->uri(), 'Categories') ? 'active' : '' }}" href="{{ route("categories.index") }}">
            <i class="fas fa-grip"></i>
            <span class="nav-link-text me-1">التصنيفات</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ str_starts_with(Route::getFacadeRoot()->current()->uri(), 'Blogs') ? 'active' : '' }}" href="{{ route("blogs.index") }}">
            <i class="fas fa-list"></i>
            <span class="nav-link-text me-1">المقالات</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ str_starts_with(Route::getFacadeRoot()->current()->uri(), 'Users') ? 'active' : '' }}" href="{{ route("users.index") }}">
            <i class="fas fa-users"></i>
            <span class="nav-link-text me-1">المستخدمين</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route("logout") }}">
            <i class="fas fa-sign-out"></i>
            <span class="nav-link-text me-1">تسجيل الخروج</span>
          </a>
        </li>
      </ul>
    </div>
    <div class="sidenav-footer mx-3 ">
      <div class="card card-background shadow-none card-background-mask-secondary" id="sidenavCard">
        <div class="full-background" style="background-image: url('../assets/img/curved-images/white-curved.jpeg')"></div>
        <div class="card-body text-start p-3 w-100">
          <div class="icon icon-shape icon-sm bg-white shadow text-center mb-3 d-flex align-items-center justify-content-center border-radius-md">
            <i class="ni ni-diamond text-dark text-gradient text-lg top-0" aria-hidden="true" id="sidenavCardIcon"></i>
          </div>
          <div class="docs-info">
            <h6 class="text-white up mb-0 text-end">تحتاج مساعدة?</h6>
            <p class="text-xs font-weight-bold text-end">يرجى التحقق من مستنداتنا</p>
            <a href="https://www.creative-tim.com/learning-lab/bootstrap/license/soft-ui-dashboard" target="_blank" class="btn btn-white btn-sm w-100 mb-0">توثيق</a>
          </div>
        </div>
      </div>
      <a class="btn bg-gradient-primary mt-4 w-100" href="https://www.creative-tim.com/product/soft-ui-dashboard-pro?ref=sidebarfree" type="button">Upgrade to pro</a>
    </div>
  </aside>