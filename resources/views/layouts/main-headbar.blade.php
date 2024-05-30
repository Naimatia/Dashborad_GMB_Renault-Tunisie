  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
          <li class="nav-item">
              <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
          </li>
          <li class="nav-item d-none d-sm-inline-block">
              @yield('link1')
          </li>
          <li class="nav-item d-none d-sm-inline-block">
              @yield('link2')
          </li>

      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
          <!-- Navbar Search -->
          <li class="nav-item">
              <input type="text" class="form-control" id="dateRangePicker"
                  style="max-width: 200px; margin: auto; padding: -10%">

          </li>

          <li class="nav-item">
              <a href="{{ route('logout') }}" class="nav-link logout-link">
                  <i class="fas fa-sign-out-alt mr-2"></i> <!-- L'icône de déconnexion -->
                  <span class="logout-text">déconnexion</span> <!-- Le texte qui apparaîtra lors du hover -->
              </a>
          </li>


          <li class="nav-item">
              <a class="nav-link" data-widget="fullscreen"  role="button">
                  <i class="fas fa-expand-arrows-alt"></i>
              </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" data-widget="control-sidebar" data-controlsidebar-slide="true" href="#"
                  role="button">
                  <i class="fas fa-th-large"></i>
              </a>
          </li>
           <!-- Yield pour inclure le compteur -->
      </ul>
  </nav>
  <!-- /.navbar -->
