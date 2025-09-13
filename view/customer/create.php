<?php
$title = 'Novo Cliente';
$currentPage = 'customer';

include(__DIR__ . '/../layout/header.php');

?>

<div class="container-fluid">
    <div class="row">
          <!-- Sidebar -->
            <?php
                include(__DIR__ . '/../layout/nav.php');
            ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content pt-4">
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
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3">
                        <i class="fas fa-users me-2"></i>
                        Novo cliente
                    </h1>
                    <div>
                        <a href="<?= SITE_URL ?>/portal" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            Voltar
                        </a>
                    </div>
                </div>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-plus me-2"></i>
                        Dados do Cliente
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= SITE_URL ?>/cliente" id="clienteForm">
                        
                        <!-- Nome e Telefone -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">
                                    <i class="fas fa-user me-1"></i>
                                    Nome Completo *
                                </label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       placeholder="Digite o nome completo" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="phone" class="form-label">
                                    <i class="fas fa-phone me-1"></i>
                                    Telefone *
                                </label>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       placeholder="(11) 99999-9999" required>
                            </div>
                        </div>
                        
                        <!-- Email e Data Nascimento -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-1"></i>
                                    E-mail *
                                </label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       placeholder="cliente@email.com" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="date_birth" class="form-label">
                                    <i class="fas fa-birthday-cake me-1"></i>
                                    Data de Nascimento
                                </label>
                                <input type="date" class="form-control" id="date_birth" name="date_birth">
                            </div>
                        </div>
                        
                        <!-- Endereços -->
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                Endereços
                            </label>

                            <div id="enderecos-container">
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" name="addresses[]" placeholder="Rua, número, bairro, cidade" required>
                                    <button type="button" class="btn btn-danger remove-endereco">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>

                            <button type="button" id="add-endereco" class="btn btn-success btn-sm mt-2">
                                <i class="fas fa-plus"></i> Adicionar Endereço
                            </button>
                        </div>
                        
                        <!-- Botões -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="<?= SITE_URL ?>/portal" class="btn btn-secondary">
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
        </main>
        
       
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Focus no primeiro campo
    document.getElementById('nome').focus();
});

document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('enderecos-container');
    const addBtn = document.getElementById('add-endereco');

    // Adicionar novo campo
    addBtn.addEventListener('click', function () {
        const div = document.createElement('div');
        div.classList.add('input-group', 'mb-2');
        div.innerHTML = `
            <input type="text" class="form-control" name="addresses[]" placeholder="Rua, número, bairro, cidade" required>
            <button type="button" class="btn btn-danger remove-endereco">
                <i class="fas fa-trash"></i>
            </button>
        `;
        container.appendChild(div);
    });

    // Remover campo
    container.addEventListener('click', function (e) {
        if (e.target.closest('.remove-endereco')) {
            e.target.closest('.input-group').remove();
        }
    });
});

</script>

<?php
include(__DIR__ . '/../layout/footer.php');
?>

