<?php
$title = 'Editar Cliente';
$currentPage = 'customer';

include(__DIR__ . '/../layout/header.php');
?>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <?php
                include(__DIR__ . '/../layout/nav.php');
            ?>
            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content pt-4">
                
                <!-- Cabeçalho -->
                 
                <div class="page-header">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h1 class="h3">
                                <i class="fas fa-user-edit me-3"></i>
                                Editar Cliente
                            </h1>
                        </div>
                        <div>
                            <a href="<?= SITE_URL ?>/portal" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            Voltar
                        </a>
                            <a href="/barbearia-new/clientes/show/<?= $customer['id'] ?>" class="btn btn-outline-light">
                                <i class="fas fa-eye me-2"></i>
                                Ver Detalhes
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Mensagens -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error']) && is_array($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <ul class="mb-0">
                            <?php foreach ($_SESSION['error'] as $error): ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-user-plus me-2"></i>
                            Dados do Cliente
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="<?= SITE_URL ?>/update_cliente/<?= $customer['id'] ?>" id="formEditarCliente">
                            <input type="hidden" name="id" value="<?= $customer['id'] ?>">
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="nome" class="form-label">
                                            <i class="fas fa-user me-1"></i>
                                            Nome Completo *
                                        </label>
                                        <input type="text" name="name" id="name" class="form-control" 
                                                value="<?= $customer['name'] ?? '' ?>" 
                                                placeholder="Digite o nome completo" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="telefone" class="form-label">
                                            <i class="fas fa-phone me-1"></i>
                                            Telefone *
                                        </label>
                                        <input type="tel" name="phone" id="phone" class="form-control" 
                                                value="<?= $customer['phone'] ?? '' ?>" 
                                                placeholder="(11) 99999-9999" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="data_nascimento" class="form-label">
                                            <i class="fas fa-birthday-cake me-1"></i>
                                            Data de Nascimento
                                        </label>
                                        <input type="date" name="date_birth" id="date_birth" class="form-control" 
                                                value="<?= $customer['date_birth'] ?? '' ?>" 
                                                max="<?= date('Y-m-d') ?>">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">
                                            <i class="fas fa-envelope me-1"></i>
                                            E-mail
                                        </label>
                                        <input type="email" name="email" id="email" class="form-control" 
                                                value="<?= $customer['email'] ?? '' ?>" 
                                                placeholder="cliente@email.com">
                                    </div>
                                </div>
                                
                            </div>
                            <label class="form-label">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                Endereços
                            </label>
                            <div id="enderecos-wrapper">
                                <?php foreach($addresses as $index => $address): ?>
                                <div class="input-group mb-2 endereco-row">
                                    <input type="hidden" name="addresses[<?= $index ?>][id]" value="<?= $address['id'] ?>">
                                    <input type="text" name="addresses[<?= $index ?>][address]" class="form-control"
                                        value="<?= $address['address'] ?>" placeholder="Rua, número, bairro, cidade">
                                    <button type="button" class="btn btn-danger remove-endereco">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <?php endforeach; ?>
                            </div>

                            <button type="button" id="add-endereco" class="btn btn-success btn-sm mt-2">
                                <i class="fas fa-plus"></i> Adicionar Endereço
                            </button>

                                                                       
                            <div class="d-flex justify-content-end gap-2">
                                <div>
                                    <a href="<?= SITE_URL ?>/portal" class="btn btn-secondary">
                                        <i class="fas fa-times me-1"></i>
                                        Cancelar
                                    </a>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-danger me-2" onclick="confirmarExclusao(<?php echo $id; ?>, '<?php echo SITE_URL; ?>')">
                                        <i class="fas fa-trash me-1"></i>
                                        Excluir Cliente
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>
                                        Salvar Alterações
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Máscara para telefone
    const telefoneInput = document.getElementById('telefone');
    telefoneInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length <= 11) {
            if (value.length <= 10) {
                value = value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
            } else {
                value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
            }
        }
        e.target.value = value;
    });
    
    // Auto-hide alerts depois de 5 segundos
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
    
    // Validação do formulário
    document.getElementById('formEditarCliente').addEventListener('submit', function(e) {
        const nome = document.getElementById('nome').value.trim();
        const telefone = document.getElementById('telefone').value.trim();
        
        if (!nome) {
            e.preventDefault();
            alert('Por favor, preencha o nome do cliente.');
            document.getElementById('nome').focus();
            return;
        }
        
        if (!telefone) {
            e.preventDefault();
            alert('Por favor, preencha o telefone do cliente.');
            document.getElementById('telefone').focus();
            return;
        }
    });
});

function confirmarExclusao(id,SITE_URL) {
    if (confirm('Tem certeza que deseja excluir este cliente?\n\nEsta ação não pode ser desfeita.')) {
        
        // Mostrar loading
        const button = event.target.closest('button');
        const originalHTML = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        button.disabled = true;
        
        // Tentar primeiro com fetch DELETE
        fetch(`${SITE_URL}/cliente/delete/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (response.ok) {
                // Sucesso - recarregar página
                window.location.reload();
            } else if (response.status === 404) {
                // Se DELETE não funcionar, tentar POST
                enviarExclusaoViaPOST(id,SITE_URL);
            } else {
                throw new Error('Erro na exclusão');
            }
        })
        .catch(error => {
            console.log('Fetch falhou, tentando POST...', error);
            // Fallback: usar POST
            enviarExclusaoViaPOST(id,SITE_URL);
        })
        .finally(() => {
            // Restaurar botão
            button.innerHTML = originalHTML;
            button.disabled = false;
        });
    }
}

function enviarExclusaoViaPOST(id,SITE_URL) {
    // Criar formulário oculto
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `${SITE_URL}/cliente/delete/${id}`;
    form.style.display = 'none';
    
    // Adicionar token CSRF se necessário
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken) {
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken.content;
        form.appendChild(csrfInput);
    }
    
    // Adicionar método DELETE
    const methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = 'DELETE';
    form.appendChild(methodInput);
    
    // Enviar formulário
    document.body.appendChild(form);
    form.submit();
}

document.addEventListener('DOMContentLoaded', function() {
    const wrapper = document.getElementById('enderecos-wrapper');
    const addButton = document.getElementById('add-endereco');

    // Remover endereço
    wrapper.addEventListener('click', function(e) {
        if (e.target.closest('.remove-endereco')) {
            const row = e.target.closest('.endereco-row');
            row.remove();
        }
    });

    // Adicionar novo endereço
    let index = <?= count($addresses) ?>; // começa do próximo índice

    addButton.addEventListener('click', function() {
        const row = document.createElement('div');
        row.classList.add('input-group', 'mb-2', 'endereco-row');

        row.innerHTML = `
            <input type="hidden" name="addresses[${index}][id]" value="">
            <input type="text" name="addresses[${index}][address]" class="form-control" placeholder="Rua, número, bairro, cidade">
            <button type="button" class="btn btn-danger remove-endereco">
                <i class="fas fa-trash"></i>
            </button>
        `;

        wrapper.appendChild(row);
        index++;
    });
});
</script>
<?php
include(__DIR__ . '/../layout/footer.php');
?>