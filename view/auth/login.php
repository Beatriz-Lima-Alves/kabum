<?php 
$title = "Login";
include(__DIR__ . '/../layout/header.php');
?>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-6">
                <div class="login-container row mx-0 justify-content-center">
                    <!-- Lado direito - Formulário -->
                    <div class="col-md-10 p-0 d-flex flex-column justify-content-center">
                        <h2 class="login-title text-center">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Entrar no Sistema
                        </h2>
                        
                        <!-- Alertas -->
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <?= $_SESSION['error'] ?>
                            </div>
                            <?php unset($_SESSION['error']); ?>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['success'])): ?>
                            <div class="alert alert-success" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                <?= $_SESSION['success'] ?>
                            </div>
                            <?php unset($_SESSION['success']); ?>
                        <?php endif; ?>
                        
                        <form method="POST" action="<?= SITE_URL ?>/login" novalidate>
                            <div class="mb-4">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-1"></i>
                                    Email
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input type="email" 
                                           class="form-control" 
                                           id="email" 
                                           name="email" 
                                           placeholder="Digite seu email"
                                           value="<?= $_POST['email'] ?? '' ?>"
                                           required>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="senha" class="form-label">
                                    <i class="fas fa-lock me-1"></i>
                                    Senha
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-key"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control" 
                                           id="password" 
                                           name="password" 
                                           placeholder="Digite sua senha"
                                           required>
                                    <button class="btn btn-outline-secondary" 
                                            type="button" 
                                            onclick="togglePassword()">
                                        <i class="fas fa-eye" id="toggleIcon"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-login">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                Entrar
                                <span class="loading spinner-border spinner-border-sm ms-2" style="display: none;"></span>
                            </button>
                        </form>
                        
                        <div class="text-center mt-4">
                            <small class="text-muted">
                                <a href="<?php echo(SITE_URL.'/registro');?>">Já possui uma conta?</a>
                            </small>
                            <small class="text-muted">
                               <a href=""> Esqueceu a senha? </a>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


     <script>
        // Toggle password visibility
        function togglePassword() {
            const senhaInput = document.getElementById('senha');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (senhaInput.type === 'password') {
                senhaInput.type = 'text';
                toggleIcon.className = 'fas fa-eye-slash';
            } else {
                senhaInput.type = 'password';
                toggleIcon.className = 'fas fa-eye';
            }
        }
        
        // Loading no formulário
        document.querySelector('form').addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            const loading = submitBtn.querySelector('.loading');
            
            loading.style.display = 'inline-block';
            submitBtn.disabled = true;
        });
        
        // Auto-hide alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
        
        // Focus no primeiro campo
        document.getElementById('email').focus();
    </script>

    <?php 
include(__DIR__ . '/../layout/footer.php');
?>