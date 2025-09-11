<?php 
$title = "Criar conta";
include(__DIR__ . '/../layout/header.php');
?>
    
    <div class="container-fluid">
        <div class="row">
            <!-- Main content -->
            <main class="main-content align-items-center justify-content-center p-4">
                <!-- Mensagens -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center m-4">
                    <div>
                        <h1 class="h2">
                            <i class="fas fa-user-plus me-2"></i>
                            Novo Usuário
                        </h1>
                    </div>
                    <a href="<?php echo(SITE_URL.'/login');?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>
                        Voltar
                    </a>
                </div>

                <!-- Formulário -->
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-user-cog me-2"></i>
                                    Dados do Usuário
                                </h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="<?php echo(SITE_URL.'/registro');?>" id="formUsuario">
                                    <div class="row">
                                        <!-- Nome -->
                                        <div class="col-md-6 mb-3">
                                            <label for="nome" class="form-label">
                                                Nome Completo <span class="required">*</span>
                                            </label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="nome" 
                                                   name="nome" 
                                                   required
                                                   placeholder="Digite o nome completo">
                                        </div>
                                        
                                        <!-- Email -->
                                        <div class="col-md-6 mb-3">
                                            <label for="email" class="form-label">
                                                E-mail <span class="required">*</span>
                                            </label>
                                            <input type="email" 
                                                   class="form-control" 
                                                   id="email" 
                                                   name="email" 
                                                   required
                                                   placeholder="Digite o e-mail">
                                        </div>
                                    </div>
                                                                                       
                                    <hr>
                                    
                                    <h6 class="mb-3">
                                        <i class="fas fa-lock me-2"></i>
                                        Configurações de Acesso
                                    </h6>
                                    
                                    <div class="row">
                                        <!-- Senha -->
                                        <div class="col-md-6 mb-3">
                                            <label for="senha" class="form-label">
                                                Senha <span class="required">*</span>
                                            </label>
                                            <div class="password-wrapper">
                                                <input type="password" 
                                                       class="form-control" 
                                                       id="senha" 
                                                       name="senha" 
                                                       required
                                                       minlength="6"
                                                       placeholder="Digite a senha">
                                                <i class="fas fa-eye password-toggle" onclick="togglePassword('senha')"></i>
                                            </div>
                                            <div class="form-text">Mínimo de 6 caracteres</div>
                                        </div>
                                        
                                        <!-- Confirmar Senha -->
                                        <div class="col-md-6 mb-3">
                                            <label for="confirmar_senha" class="form-label">
                                                Confirmar Senha <span class="required">*</span>
                                            </label>
                                            <div class="password-wrapper">
                                                <input type="password" 
                                                       class="form-control" 
                                                       id="confirmar_senha" 
                                                       name="confirmar_senha" 
                                                       required
                                                       placeholder="Confirme a senha">
                                                <i class="fas fa-eye password-toggle" onclick="togglePassword('confirmar_senha')"></i>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Botões -->
                                    <div class="d-flex justify-content-between mt-4">
                                        <a href="<?php echo(SITE_URL.'/login');?>" class="btn btn-outline-secondary">
                                            <i class="fas fa-times me-1"></i>
                                            Cancelar
                                        </a>
                                        
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-1"></i>
                                            Cadastrar Usuário
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Função para mostrar/ocultar senha
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const toggle = field.nextElementSibling;
            
            if (field.type === 'password') {
                field.type = 'text';
                toggle.classList.remove('fa-eye');
                toggle.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                toggle.classList.remove('fa-eye-slash');
                toggle.classList.add('fa-eye');
            }
        }
        
        // Validação de senhas iguais
        document.getElementById('formUsuario').addEventListener('submit', function(e) {
            const senha = document.getElementById('senha').value;
            const confirmarSenha = document.getElementById('confirmar_senha').value;
            
            if (senha !== confirmarSenha) {
                e.preventDefault();
                alert('As senhas não conferem. Por favor, verifique e tente novamente.');
                document.getElementById('confirmar_senha').focus();
                return false;
            }
            
            // Mostrar loading no botão
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Cadastrando...';
            submitBtn.disabled = true;
            
            // Restaurar botão após 5 segundos (fallback)
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 5000);
        });
        
        // Auto-hide alerts
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    try {
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    } catch (e) {
                        console.log('Erro ao fechar alert:', e);
                    }
                });
            }, 5000);
        });
    </script>

<?php 
include(__DIR__ . '/../layout/footer.php');
?>