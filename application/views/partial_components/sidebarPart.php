<section id="sidebar-section">
    <div class="offcanvas-lg offcanvas-start custom-sidebar" data-bs-scroll="true" tabindex="-1" id="sidebarPanelOffCanvas" style="overflow-y: auto">
        <div class="d-flex flex-column flex-shrink-0 py-3 bg-white" style="width: auto; height: 100vh;">
            <a href="/" class=" px-3 d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
                <img src="<?= base_url('assets/img/logo.png') ?>" width="32">&emsp;
                <span class="fs-4">Monager</span>
            </a>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="<?= base_url('budget') ?>" class="nav-link rounded-0 rounded-0 link-dark sidebar-item" id="sidebar-budget">
                        <i class="fa-solid fa-money-bill-wave fa-fw"></i>&emsp;
                        Budget
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('debt') ?>" class="nav-link rounded-0 link-dark sidebar-item" id="sidebar-debt">
                        <i class="fa-solid fa-money-check-dollar fa-fw"></i>&emsp;
                        Debt
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('loan') ?>" class="nav-link rounded-0 link-dark sidebar-item" id="sidebar-loan">
                        <i class="fa-solid fa-hand-holding-dollar fa-fw"></i>&emsp;
                        Loan
                    </a>
                </li>
            </ul>
            <hr>
            <div class="dropup-center px-3">
                <a href="#" class="d-flex align-items-center link-dark text-decoration-none " data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-user"></i>&emsp;
                    <p class="mb-0"><?= $this->UserModel->getUserNameBySession() ?></p>
                </a>
                <ul class="dropdown-menu text-small shadow rounded-0">
                    <!-- <li><a class="dropdown-item" href="#">New project...</a></li>
                    <li><a class="dropdown-item" href="#">Settings</a></li>
                    <li><a class="dropdown-item" href="#">Profile</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li> -->
                    <li><a class="dropdown-item" href="<?= base_url('logout') ?>">Sign out</a></li>
                </ul>
            </div>
        </div>
    </div>
</section>