  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="index3.html" class="brand-link">
          <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
              style="opacity: .8">

      </a>

      <!-- Sidebar -->
      <div class="sidebar">
      

          <!-- Sidebar Menu -->
          <nav class="mt-2">
              <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                  data-accordion="false">
                  <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->


                  <li class="nav-header">EXAMPLES</li>
                  <li class="nav-item menu-open">
                      <a href="#" class="nav-link active">
                          <i class="nav-icon fas fa-table"></i>
                          <p>
                              Property
                              <i class="fas fa-angle-left right"></i>
                          </p>
                      </a>
                      <ul class="nav nav-treeview">
                          <li class="nav-item">
                              <a href="{{ route('property.list') }}" class="nav-link active">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>All Property</p>
                              </a>
                          </li>
                          <li class="nav-item">
                              <a href="{{ route('property.create') }}" class="nav-link">
                                  <i class="far fa-circle nav-icon"></i>
                                  <p>Add Property</p>
                              </a>
                          </li>
                      </ul>
                  </li>


              </ul>
          </nav>
          <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
  </aside>
