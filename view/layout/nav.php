<nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                                                
                        <li class="nav-item">
                            <a class="nav-link <?= $currentPage == 'customer' ? 'active' : '' ?>" href="<?php echo(SITE_URL.'/portal');?>">
                                <i class="fas fa-users"></i>
                                Clientes
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?= $currentPage == 'users' ? 'active' : '' ?>" href="">
                                <i class="fas fa-user-tie"></i>
                                Usuários
                            </a>
                        </li>

                        <li class="nav-item logout-section">
                        <a class="nav-link logout-link" href="<?php echo(SITE_URL.'/logout');?>" onclick="return confirm('Tem certeza que deseja sair?');">
                            <i class="fas fa-sign-out-alt"></i>
                            Sair
                        </a>
                    </li>
                    </ul>
                </div>
            </nav>