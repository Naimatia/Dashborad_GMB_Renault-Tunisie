   <!-- Main Sidebar Container -->
   <aside class="main-sidebar sidebar-dark-primary elevation-4">
       <!-- Brand Logo -->
       <a href="#" class="brand-link">
           <div class="d-flex align-items-center">
               <img src="dist/img/GMBapi-Logo-White.png" alt="GMBapi Logo" style="width: 50px; height: 50px;">
               <span class="brand-text ml-2">
                   <span class="font-weight-bold text-primary">GMB</span>
                   <span class="text-orange">api.com</span>
               </span>
           </div>
       </a>

       <!-- Sidebar -->
       <div class="sidebar">

           <div class="brand-link  pb-3 mb-3 d-flex">
               <div class="d-flex align-items-center">
                   <img src="dist/img/renault_logo.png" alt="GMBapi Logo" style="width: 30px; height: 40px; ">
                   <span class="brand-text ml-2">
                    <span class="text-white" style="font-size: 18px;">Renault Tunisie</span>
                </span>
               </div>
           </div>



           <!-- Sidebar Menu -->
           <nav class="mt-2">
               <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                   data-accordion="false">
                   <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
                   <!-- Titre blanc -->

                   <li class="nav-item">
                       <a href="{{ route('home') }}" class="nav-link">
                           <i class="nav-icon fas fa-home"></i>
                           <p>Accueil</p>
                       </a>
                   </li>
                   <li class="nav-item">
                       <a href="{{ route('Établissements') }}" class="nav-link">
                           <i class="nav-icon fas fa-building"></i>
                           <p>Établissements</p>
                       </a>
                   </li>


               </ul>
           </nav>
           <!-- /.sidebar-menu -->
       </div>
       <!-- /.sidebar -->
   </aside>
