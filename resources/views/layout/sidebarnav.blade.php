 <!-- Main Sidebar Container -->
 <aside class="main-sidebar main-sidebar-custom sidebar-dark-primary elevation-4">
     <!-- Brand Logo -->
     <a href="#" class="brand-link">
         <img src="https://msis.co.id/wp-content/uploads/2021/08/Logo-MSI-Media-Selular-Indonesia-1024x570.png"
             alt="AdminLTE Logo" class="brand-image" style="opacity: .8">
         <span class="brand-text font-weight-light"><strong>Grosir APP</strong></span>
     </a>

     <!-- Sidebar -->
     <div class="sidebar">
         <!-- Sidebar user (optional) -->
         <div class="user-panel mt-3 pb-3 mb-3 d-flex">
             <div class="info">
                 <a href="/dashboard" class="d-block"><strong>HOME</strong></a>
             </div>
         </div>
         <!-- Sidebar Menu -->
         <nav class="mt-2">
             <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                 <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                 <li class="nav-item">
                     <a href="/user" class="nav-link {{ $active === 'user' ? 'active' : '' }}">
                         <i class="nav-icon fas fa-users"></i>
                         <p>User</p>
                     </a>
                 </li>
                 <li class="nav-item">
                     <a href="#" class="nav-link {{ $active === 'setting' ? 'active' : '' }}">
                         <i class="nav-icon fas fa-cogs"></i>
                         <p>
                             Settings
                             <i class="right fas fa-angle-left"></i>
                         </p>
                     </a>
                     <ul class="nav nav-treeview">
                         <li class="nav-item">
                             <a href="/setting/role" class="nav-link">
                                 <i class="fas fa-user-cog nav-icon"></i>
                                 <p>Role</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="/setting/divisi" class="nav-link">
                                 <i class="fas fa-briefcase nav-icon"></i>
                                 <p>Divisi</p>
                             </a>
                         </li>
                     </ul>
                 </li>
             </ul>
         </nav>
         <!-- /.sidebar-menu -->
     </div>
     <!-- /.sidebar -->

     <div class="sidebar-custom">
         <a href="/dashboard/logout" class="btn btn-link"><i class="fas fa-sign-out-alt"></i> Log Out</a>
     </div>
     <!-- /.sidebar-custom -->
 </aside>
