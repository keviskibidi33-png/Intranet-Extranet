<?php
/**
 * Sidebar / Left Panel del Admin
 * Menú de navegación principal
 */

// Obtener página actual
$pagina_actual = isset($_GET['pagina']) ? $_GET['pagina'] : 'inicio';

// Función para verificar si una página está activa (incluye páginas relacionadas)
function esPaginaActiva($pagina_menu, $pagina_actual) {
    if ($pagina_menu === $pagina_actual) {
        return true;
    }
    // Páginas relacionadas con "clientes"
    if ($pagina_menu === 'clientes') {
        return in_array($pagina_actual, array('clientes', 'clientes_agregar', 'clientes_editar', 'pdf', 'pdf_agregar', 'pdf_editar'));
    }
    return false;
}
?>
<aside id="left-panel" class="left-panel">
  <nav class="navbar navbar-expand-sm navbar-default">
    <div class="navbar-header">
      <div class="geofal-logo-sidebar">
        <img src="<?php echo ruta ?>include/images/geofal.png" alt="Geofal Logo" class="geofal-logo-img">
        <div class="logo-text">
          <p class="logo-title">Ingeniería</p>
          <p class="logo-subtitle">y laboratorio</p>
          <p class="logo-subtitle">de materiales</p>
        </div>
      </div>
      <button class="navbar-toggler" type="button" id="toggle-menu" data-toggle="collapse" data-target="#main-menu" aria-controls="main-menu" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fa fa-chevron-left"></i>
      </button>
    </div>

    <div id="main-menu" class="main-menu collapse navbar-collapse">
      <ul class="nav navbar-nav geofal-menu">
        <li class="menu-item <?php echo esPaginaActiva('clientes', $pagina_actual) ? 'active' : ''; ?>">
          <a href="<?php echo ruta ?>clientes">
            <i class="menu-icon fa fa-building"></i>
            <span>Empresas Geofal</span>
          </a>
        </li>

        <li class="menu-item <?php echo ($pagina_actual === 'perfil') ? 'active' : ''; ?>">
          <a href="<?php echo ruta ?>perfil">
            <i class="menu-icon fa fa-user"></i>
            <span>Perfiles</span>
          </a>
        </li>

        <li class="menu-item <?php echo ($pagina_actual === 'pdf_vencer') ? 'active' : ''; ?>">
          <a href="<?php echo ruta ?>?pagina=pdf_vencer">
            <i class="menu-icon fa fa-calendar-times-o"></i>
            <span>PDFs por Vencer</span>
          </a>
        </li>
      </ul>
    </div>
  </nav>
</aside>

<script>
// Toggle sidebar en móvil
(function() {
    const toggleBtn = document.getElementById('toggle-menu');
    const leftPanel = document.getElementById('left-panel');
    const rightPanel = document.getElementById('right-panel');
    
    if (toggleBtn && leftPanel) {
        toggleBtn.addEventListener('click', function(e) {
            e.preventDefault();
            leftPanel.classList.toggle('open');
            if (rightPanel) {
                rightPanel.classList.toggle('sidebar-open');
            }
        });
    }
})();
</script>
