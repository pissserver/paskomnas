<div class="fixed-plugin">
  <a class="fixed-plugin-button text-dark position-fixed px-3 py-2">
    <i class="fa fa-cog py-2"> </i>
  </a>
  <div class="card shadow-lg">
    <div class="card-header pb-0 pt-3">
      <div class="{{ (Request::is('rtl') ? 'float-end' : 'float-start') }}">
        <h5 class="mt-3 mb-0">Dashboard Configurator</h5>
        <p>See our dashboard options.</p>
      </div>
      <div class="{{ (Request::is('rtl') ? 'float-start mt-4' : 'float-end mt-4') }}">
        <button class="btn btn-link text-dark p-0 fixed-plugin-close-button">
          <i class="fa fa-close"></i>
        </button>
      </div>
    </div>
    <hr class="horizontal dark my-1">
    <div class="card-body pt-sm-3 pt-0">
      <!-- Sidebar Backgrounds -->
      <div>
        <h6 class="mb-0">Sidebar Colors</h6>
      </div>
      <a href="javascript:void(0)" class="switch-trigger background-color">
        <div class="badge-colors my-2 {{ (Request::is('rtl') ? 'text-end' : 'text-start') }}">
          <span class="badge filter bg-gradient-primary" data-color="primary" onclick="sidebarColor(this)"></span>
          <span class="badge filter bg-gradient-dark" data-color="dark" onclick="sidebarColor(this)"></span>
          <span class="badge filter bg-gradient-info" data-color="info" onclick="sidebarColor(this)"></span>
          <span class="badge filter bg-gradient-success" data-color="success" onclick="sidebarColor(this)"></span>
          <span class="badge filter bg-gradient-warning" data-color="warning" onclick="sidebarColor(this)"></span>
          <span class="badge filter bg-gradient-danger" data-color="danger" onclick="sidebarColor(this)"></span>
        </div>
      </a>
      <!-- Sidenav Type -->
      <div class="mt-3">
        <h6 class="mb-0">Sidenav Type</h6>
        <p class="text-sm">Choose between 2 different sidenav types.</p>
      </div>
      <div class="d-flex">
        <button class="btn bg-gradient-primary w-100 px-3 mb-2 active" data-class="bg-transparent"
          onclick="sidebarType(this)">Transparent</button>
        <button class="btn bg-gradient-primary w-100 px-3 mb-2 {{ (Request::is('rtl') ? 'me-2' : 'ms-2') }}"
          data-class="bg-white" onclick="sidebarType(this)">White</button>
      </div>
      <p class="text-sm d-xl-none d-block mt-2">You can change the sidenav type just on desktop view.</p>
      <!-- Navbar Fixed -->
      <div class="mt-3">
        <h6 class="mb-0">Navbar Fixed</h6>
      </div>
      <div class="form-check form-switch ps-0">
        <input class="form-check-input mt-1 ms-auto {{ (Request::is('rtl') ? 'float-end' : '') }}" type="checkbox"
          id="navbarFixed" onclick="navbarFixed(this)">
      </div>
      <hr class="horizontal dark my-sm-4">
      <a class="btn bg-gradient-dark w-100" href="javascript:void(0)" id="saveConfigButton">Save Configuration</a>
    </div>
  </div>
</div>

<script>
  function sidebarColor(element) {
    let color = element.getAttribute('data-color');
    if (!color) {
      const classList = Array.from(element.classList);
      const gradientClass = classList.find(cls => cls.startsWith('bg-gradient-'));
      if (gradientClass) {
        color = gradientClass.replace('bg-gradient-', '');
      }
    }

    if (!color) return;

    // Simpan warna ke localStorage
    localStorage.setItem('sidebarColor', color);

    // Terapkan ke sidebar
    const sidebar = document.getElementById('sidenav-main');
    if (sidebar) {
      sidebar.setAttribute('data-color', color);
    }

    // Update warna icon menu yang aktif
    const activeIcon = document.querySelector('.nav-link.active .icon');
    if (activeIcon) {
      activeIcon.classList.forEach(cls => {
        if (cls.startsWith('bg-') || cls.startsWith('text-')) {
          activeIcon.classList.remove(cls);
        }
      });
      activeIcon.classList.add('bg-white', `text-${color}`);
    }

    // Update badge aktif
    document.querySelectorAll('.badge.filter').forEach(el => el.classList.remove('active'));
    element.classList.add('active');
  }

  function sidebarType(element) {
    const className = element.getAttribute('data-class');
    const sidebar = document.getElementById('sidenav-main');
    if (className && sidebar) {
      sidebar.classList.remove('bg-transparent', 'bg-white');
      sidebar.classList.add(className);
      localStorage.setItem('sidebarType', className);
    }

    // Toggle active state
    element.parentElement.querySelectorAll('button').forEach(btn => btn.classList.remove('active'));
    element.classList.add('active');
  }

  function navbarFixed(checkbox) {
    if (checkbox.checked) {
      localStorage.setItem('navbarFixed', 'true');
    } else {
      localStorage.setItem('navbarFixed', 'false');
    }
  }

  document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidenav-main');
    const navbar = document.querySelector('nav');
    const navbarCheckbox = document.getElementById('navbarFixed');

    // Load sidebarColor
    const savedColor = localStorage.getItem('sidebarColor') || 'primary';
    if (sidebar) {
      sidebar.setAttribute('data-color', savedColor);
    }

    // Terapkan ke icon aktif
    const activeIcon = document.querySelector('.nav-link.active .icon');
    if (activeIcon) {
      activeIcon.classList.forEach(cls => {
        if (cls.startsWith('bg-') || cls.startsWith('text-')) {
          activeIcon.classList.remove(cls);
        }
      });
      activeIcon.classList.add('bg-white', `text-${savedColor}`);
    }

    // Tandai badge yang aktif
    document.querySelectorAll('.badge.filter').forEach(el => el.classList.remove('active'));
    const badge = document.querySelector(`.badge.filter[data-color="${savedColor}"]`);
    if (badge) badge.classList.add('active');

    // Load sidebarType
    const savedType = localStorage.getItem('sidebarType');
    if (savedType && sidebar) {
      sidebar.classList.add(savedType);
      document.querySelectorAll('button[data-class]').forEach(btn => btn.classList.remove('active'));
      const button = document.querySelector(`button[data-class="${savedType}"]`);
      if (button) button.classList.add('active');
    }

    // Load navbar fixed
    const fixed = localStorage.getItem('navbarFixed');
    if (fixed === 'true') {
      navbar?.classList.add('position-sticky', 'blur', 'shadow-blur', 'mt-5', 'left-auto', 'top-1', 'z-index-sticky');
      if (navbarCheckbox) navbarCheckbox.checked = true;
      if (navbar) navbar.setAttribute('data-scroll', 'true');
    } else {
      navbar?.classList.remove('position-sticky', 'blur', 'shadow-blur', 'mt-5', 'left-auto', 'top-1', 'z-index-sticky');
      if (navbarCheckbox) navbarCheckbox.checked = false;
      if (navbar) navbar.setAttribute('data-scroll', 'false');
    }
  });

  // Tombol Save manual
  document.getElementById('saveConfigButton').addEventListener('click', function () {
    const sidebar = document.getElementById('sidenav-main');
    const color = sidebar.getAttribute('data-color');
    const typeClass = Array.from(sidebar.classList).find(cls =>
      ['bg-white', 'bg-transparent'].includes(cls)
    );
    const navbarCheckbox = document.getElementById('navbarFixed');

    localStorage.setItem('sidebarColor', color || 'primary');
    localStorage.setItem('sidebarType', typeClass || 'bg-transparent');
    localStorage.setItem('navbarFixed', navbarCheckbox?.checked ? 'true' : 'false');

    alert('Configuration saved to localStorage!');
  });
</script>