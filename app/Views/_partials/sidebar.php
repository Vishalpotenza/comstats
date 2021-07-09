 <div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="<?= base_url().ADMIN ?>/dashboard" > <img alt="image" src="<?=  base_url().IMGPATH ?>/logo.png" class="header-logo" /> 
            </a>
          </div>
          <ul class="sidebar-menu">
            <li class="menu-header">Main</li>
            <li class="dropdown active">
              <a href= "<?= base_url().ADMIN ?>/dashboard" class="nav-link"><i data-feather="monitor"></i><span>Dashboard</span></a>
            </li>
             <li class="dropdown">
              <a href="#" class="menu-toggle nav-link has-dropdown"><i
                  class="fas fa-futbol"></i><span>Clubs</span></a>
              <ul class="dropdown-menu">
                <li><a class="nav-link" href="<?= base_url().ADMIN ?>/clubs">Manage Clubs</a></li>
              </ul>
            </li>
          </ul>
        </aside>
      </div>