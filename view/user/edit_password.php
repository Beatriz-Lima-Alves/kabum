<?php 
$title = "Redefinir Senha";
include(__DIR__ . '/../layout/header.php');
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-6">
            <div class="login-container row mx-0 justify-content-center">
                <div class="col-md-10 p-0 d-flex flex-column justify-content-center">
                    <h2 class="login-title text-center">
                        <i class="fas fa-unlock-alt me-2"></i>
                        Redefinir Senha
                    </h2>

                    <!-- Alertas -->
                   <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <?php 
                            if (is_array($_SESSION['error'])) {
                                echo '<ul>';
                                foreach ($_SESSION['error'] as $err) {
                                    echo "<li>$err</li>";
                                }
                                echo '</ul>';
                            } else {
                                echo $_SESSION['error'];
                            }
                            ?>
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

                    <form method="POST" action="<?= SITE_URL ?>/edit_password" novalidate>
                    <input type="hidden" name="id" value="<?= $id ?>">    
                    <div class="mb-4">
                            <label for="senha" class="form-label">
                                <i class="fas fa-lock me-1"></i>
                                Nova Senha
                            </label>
                            <input type="password" 
                                   class="form-control" 
                                   id="senha" 
                                   name="senha" 
                                   placeholder="Digite sua nova senha"
                                   required>
                        </div>

                        <div class="mb-4">
                            <label for="confirma_senha" class="form-label">
                                <i class="fas fa-lock me-1"></i>
                                Confirme a Nova Senha
                            </label>
                            <input type="password" 
                                   class="form-control" 
                                   id="confirma_senha" 
                                   name="confirma_senha" 
                                   placeholder="Confirme sua nova senha"
                                   required>
                        </div>

                        <button type="submit" class="btn btn-login w-100">
                            <i class="fas fa-save me-2"></i>
                            Redefinir Senha
                            <span class="loading spinner-border spinner-border-sm ms-2" style="display: none;"></span>
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <small class="text-muted">
                            <a href="<?= SITE_URL ?>/login">Voltar ao login</a>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
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
    document.getElementById('senha').focus();
</script>

<?php 
include(__DIR__ . '/../layout/footer.php');
?>
