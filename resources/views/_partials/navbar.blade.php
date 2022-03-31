  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Dark Mode Toggle -->
      <li class="nav-item">
        <button class="nav-link btn" id="toggle-dark-mode">
          <i class="fas fa-moon"></i>
        </button>
      </li>

      <li class="nav-item">
        <a href="{{ route('user.edit-password') }}">
          <button class="nav-link btn" id="toggle-dark-mode">
            <i class="fas fa-user-alt"></i>
          </button>
        </a>
      </li>

      <li class="nav-item">
        <a href="{{ route('logout') }}">
          <button class="nav-link btn" id="toggle-dark-mode">
            <i class="fas fa-sign-out-alt"></i>
          </button>
        </a>
      </li>

    </ul>
  </nav>
  <!-- /.navbar -->