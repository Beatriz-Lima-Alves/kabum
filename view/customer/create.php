<?php
$title = 'Novo Cliente - Sistema de Barbearia';
$currentPage = 'clientes';

ob_start();
?>

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-user-plus me-2"></i>
            Novo Cliente
        </h1>
        <a href="<?= SITE_URL ?>/clientes" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>
            Voltar
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-plus me-2"></i>
                        Dados do Cliente
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= SITE_URL ?>/clientes" id="clienteForm">
                        
                        <!-- Nome e Telefone -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nome" class="form-label">
                                    <i class="fas fa-user me-1"></i>
                                    Nome Completo *
                                </label>
                                <input type="text" class="form-control" id="nome" name="nome" 
                                       placeholder="Digite o nome completo" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="telefone" class="form-label">
                                    <i class="fas fa-phone me-1"></i>
                                    Telefone *
                                </label>
                                <input type="tel" class="form-control" id="telefone" name="telefone" 
                                       placeholder="(11) 99999-9999" required>
                            </div>
                        </div>
                        
                        <!-- Email e Data Nascimento -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-1"></i>
                                    E-mail
                                </label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       placeholder="cliente@email.com">
                            </div>
                            
                            <div class="col-md-6">
                                <label for="data_nascimento" class="form-label">
                                    <i class="fas fa-birthday-cake me-1"></i>
                                    Data de Nascimento
                                </label>
                                <input type="date" class="form-control" id="data_nascimento" name="data_nascimento">
                            </div>
                        </div>
                        
                        <!-- Endereço -->
                        <div class="mb-3">
                            <label for="endereco" class="form-label">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                Endereço
                            </label>
                            <input type="text" class="form-control" id="endereco" name="endereco" 
                                   placeholder="Rua, número, bairro, cidade">
                        </div>
                        
                        <!-- Observações -->
                        <div class="mb-3">
                            <label for="observacoes" class="form-label">
                                <i class="fas fa-comment me-1"></i>
                                Observações
                            </label>
                            <textarea class="form-control" id="observacoes" name="observacoes" rows="3" 
                                      placeholder="Observações sobre o cliente..."></textarea>
                        </div>
                        
                        <!-- Botões -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="<?= SITE_URL ?>/clientes" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i>
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>
                                Salvar Cliente
                            </button>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Coluna lateral com dicas -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-lightbulb me-2"></i>
                        Dicas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <strong>Nome e Telefone</strong>
                        </div>
                        <p class="small text-muted mb-0">São obrigatórios para criar o cadastro.</p>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-envelope text-info me-2"></i>
                            <strong>E-mail</strong>
                        </div>
                        <p class="small text-muted mb-0">Para enviar confirmações automáticas.</p>
                    </div>
                    
                    <div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-birthday-cake text-warning me-2"></i>
                            <strong>Aniversário</strong>
                        </div>
                        <p class="small text-muted mb-0">Para promoções especiais.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Máscara telefone simples
    const telefone = document.getElementById('telefone');
    telefone.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length <= 11) {
            if (value.length <= 10) {
                value = value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
            } else {
                value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
            }
            e.target.value = value;
        }
    });
    
    // Focus no primeiro campo
    document.getElementById('nome').focus();
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>

