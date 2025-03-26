<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="/" class="logo logo-dark">
            <span class="logo-sm">
                <img src="/assets/images/logo-sm.png" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="/assets/images/logo-dark.png" alt="" height="17">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="/" class="logo logo-light">
            <span class="logo-sm">
                <img src="/assets/images/logo-sm.png" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="/assets/images/logo-light.png" alt="" height="17">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
                id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="/admin">
                        <i class="ri-dashboard-line"></i> <span>Home</span>
                    </a>
                </li>

                <li class="menu-title"><span data-key="t-menu">Produto</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarEventos" data-bs-toggle="collapse" role="button"
                       aria-expanded="false" aria-controls="sidebarEventos">
                        <i class="ri-calendar-event-line"></i> <span>Eventos</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarEventos">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="/admin/eventos" class="nav-link">Painel de controle</a>
                            </li>
                            <li class="nav-item">
                                <a href="/admin/eventos/novo" class="nav-link">Criar novo</a>
                            </li>
                            <li class="nav-item">
                                <a href="/admin/eventos/lista" class="nav-link">Lista de eventos</a>
                            </li>
                            <li class="nav-item">
                                <a href="/admin/eventos/participantes" class="nav-link">Participantes</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="menu-title"><span data-key="t-menu">Financeiro</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarEcommerce" data-bs-toggle="collapse" role="button"
                       aria-expanded="false" aria-controls="sidebarEcommerce">
                        <i class="ri-shopping-bag-line"></i> <span>Ecommerce</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarEcommerce">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="/admin/cursos" class="nav-link">Painel de controle</a>
                            </li>
                            <li class="nav-item">
                                <a href="/admin/cursos/novo" class="nav-link">Criar novo</a>
                            </li>
                            <li class="nav-item">
                                <a href="/admin/cursos/lista" class="nav-link">Lista de cursos</a>
                            </li>
                            <li class="nav-item">
                                <a href="/admin/cursos/participantes" class="nav-link">Participantes</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarAnalises" data-bs-toggle="collapse" role="button"
                       aria-expanded="false" aria-controls="sidebarAnalises">
                        <i class="ri-bar-chart-line"></i> <span>Análises</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarAnalises">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="/admin/cursos" class="nav-link">Painel de controle</a>
                            </li>
                            <li class="nav-item">
                                <a href="/admin/cursos/novo" class="nav-link">Criar novo</a>
                            </li>
                            <li class="nav-item">
                                <a href="/admin/cursos/lista" class="nav-link">Lista de cursos</a>
                            </li>
                            <li class="nav-item">
                                <a href="/admin/cursos/participantes" class="nav-link">Participantes</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="menu-title"><span data-key="t-menu">Administração</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarIntegracoes" data-bs-toggle="collapse" role="button"
                       aria-expanded="false" aria-controls="sidebarIntegracoes">
                        <i class="ri-links-line"></i> <span>Integrações</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarIntegracoes">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="/admin/cursos" class="nav-link">Painel de controle</a>
                            </li>
                            <li class="nav-item">
                                <a href="/admin/cursos/novo" class="nav-link">Criar novo</a>
                            </li>
                            <li class="nav-item">
                                <a href="/admin/cursos/lista" class="nav-link">Lista de cursos</a>
                            </li>
                            <li class="nav-item">
                                <a href="/admin/cursos/participantes" class="nav-link">Participantes</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarConfiguracoes" data-bs-toggle="collapse" role="button"
                       aria-expanded="false" aria-controls="sidebarConfiguracoes">
                        <i class="ri-settings-3-line"></i> <span>Configurações</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarConfiguracoes">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="/admin/cursos" class="nav-link">Painel de controle</a>
                            </li>
                            <li class="nav-item">
                                <a href="/admin/cursos/novo" class="nav-link">Criar novo</a>
                            </li>
                            <li class="nav-item">
                                <a href="/admin/cursos/lista" class="nav-link">Lista de cursos</a>
                            </li>
                            <li class="nav-item">
                                <a href="/admin/cursos/participantes" class="nav-link">Participantes</a>
                            </li>
                        </ul>
                    </div>
                </li>

            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>