<aside id="left-panel" class="left-panel">
  <nav class="navbar navbar-expand-sm navbar-default">
    <div class="navbar-header">
      <div class="geofal-logo-sidebar">
        <img src="<?= ruta ?>include/images/geofal.png" alt="Geofal Logo" class="geofal-logo-img">
        <div class="logo-text">
          <p class="logo-title">Ingeniería</p>
          <p class="logo-subtitle">y laboratorio</p>
          <p class="logo-subtitle">de materiales</p>
        </div>
      </div>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-menu" aria-controls="main-menu" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fa fa-chevron-left"></i>
      </button>
    </div>

    <div id="main-menu" class="main-menu collapse navbar-collapse">
      <ul class="nav navbar-nav geofal-menu">
        <li class="menu-item <?php echo (isset($_GET['pagina']) && ($_GET['pagina'] == 'clientes' || $_GET['pagina'] == 'pdf' || $_GET['pagina'] == 'pdf_agregar' || $_GET['pagina'] == 'pdf_editar' || $_GET['pagina'] == 'clientes_agregar' || $_GET['pagina'] == 'clientes_editar')) ? 'active' : ''; ?>">
          <a href="<?= ruta ?>clientes">
            <i class="menu-icon fa fa-building"></i>
            <span>Empresas Geofal</span>
          </a>
        </li>

        <li class="menu-item <?php echo (isset($_GET['pagina']) && $_GET['pagina'] == 'perfil') ? 'active' : ''; ?>">
          <a href="<?= ruta ?>perfil">
            <i class="menu-icon fa fa-user"></i>
            <span>Perfiles</span>
          </a>
        </li>
      </ul>
    </div>
  </nav>
</aside>
