<?php

$title = 'Detalhes do Cliente';
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
            <!-- Cabeçalho -->
            <div class="page-header">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 mb-0 text-gray-800">
                            <i class="fas fa-user me-2"></i>
                            <?= htmlspecialchars($customer['name']) ?>
                        </h1>
                        <p class="text-muted mb-0">Cliente #<?= $customer['id'] ?></p>
                    </div>
                    <div>
                        <a href="<?php echo(SITE_URL.'/portal')?>" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left me-1"></i>
                            Voltar
                        </a>
                        <a href="<?php echo(SITE_URL.'/edit_cliente') .'/'. $customer['id'] ?>" class="btn btn-warning me-2">
                            <i class="fas fa-edit me-1"></i>
                            Editar
                        </a>
                        
                    </div>
                </div>
            </div>
            <!-- Card Principal -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar-circle bg-accent text-white me-3">
                            <?= strtoupper(substr($customer['name'], 0, 2)) ?>
                        </div>
                        <div>
                            <h5 class="mb-0"><?= $customer['name'] ?></h5>
                            <small class="text-muted">Cliente desde <?= date('d/m/Y', strtotime($customer['created_at'])) ?></small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Telefone -->
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-1">
                            <i class="fas fa-phone text-primary me-2"></i>
                            <strong>Telefone</strong>
                        </div>
                        <div class="ms-4">
                            <a href="tel:<?= $customer['phone'] ?>" class="text-decoration-none">
                                <?= $customer['phone'] ?>
                            </a>
                            <button class="btn btn-sm btn-outline-success ms-2" onclick="copyToClipboard('<?= $customer['phone'] ?>')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Email -->
                    <?php if (!empty($customer['email'])): ?>
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-1">
                            <i class="fas fa-envelope text-primary me-2"></i>
                            <strong>E-mail</strong>
                        </div>
                        <div class="ms-4">
                            <a href="mailto:<?= $customer['email'] ?>" class="text-decoration-none">
                                <?= $customer['email'] ?>
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Data de Nascimento -->
                    <?php if (!empty($customer['date_birth']) && $customer['date_birth'] !== '0000-00-00'): ?>
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-1">
                            <i class="fas fa-birthday-cake text-primary me-2"></i>
                            <strong>Nascimento</strong>
                        </div>
                        <div class="ms-4">
                           <?php if (!empty($customer['date_birth']) && $customer['date_birth'] !== '0000-00-00'): ?>
                            <?= date('d/m/Y', strtotime($customer['date_birth'])) ?>
                            <br><small class="text-muted">
                                <?= floor((time() - strtotime($customer['date_birth'])) / (365*24*60*60)) ?> anos
                            </small>
                        <?php else: ?>
                            <span class="text-muted">-</span>
                        <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Endereço -->
                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-1">
                                <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                <strong>Endereço</strong>
                            </div>
                            <?php foreach ($addresses as $address): ?>
                                <div class="ms-4">
                                    - <?= $address['address'] ?>
                                </div>
                            <?php endforeach; ?>

                        </div>

                    <!-- Data de Cadastro -->
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-1">
                            <i class="fas fa-calendar text-primary me-2"></i>
                            <strong>Cadastrado em</strong>
                        </div>
                        <div class="ms-4">
                            <?= date('d/m/Y', strtotime($customer['created_at'])) ?>
                            <br><small class="text-muted">
                                há <?= max(0, floor((time() - strtotime($customer['created_at'])) / (24*60*60))) ?> dias
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Modal de Confirmação de Exclusão -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <h5>Tem certeza?</h5>
                    <p>Esta ação não pode ser desfeita. Todos os agendamentos deste cliente também serão removidos.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a href="<?= url('clientes/delete/' . $customer['id']) ?>" class="btn btn-danger">
                    <i class="fas fa-trash me-1"></i>
                    Excluir Cliente
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// Função para copiar texto
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Se tiver SweetAlert carregado
        if (typeof Swal !== 'undefined') {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
            });
            
            Toast.fire({
                icon: 'success',
                title: 'Telefone copiado!'
            });
        } else {
            // Fallback para alert simples
            alert('Telefone copiado: ' + formatPhone(text));
        }
    }).catch(function(err) {
        console.error('Erro ao copiar: ', err);
        alert('Erro ao copiar o telefone');
    });
}

// Confirmar exclusão
function confirmarExclusao() {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

// Confirmar agendamento via AJAX
function confirmarAgendamento(agendamentoId) {
    if (confirm('Confirmar este agendamento?')) {
        fetch(`<?= url('api/agendamentos/') ?>${agendamentoId}/confirmar`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erro ao confirmar agendamento: ' + (data.message || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro de conexão');
        });
    }
}

// Imprimir histórico
function imprimirHistorico() {
    window.print();
}

// Exportar para CSV
function exportarCSV() {
    window.location.href = `<?= url('clientes/' . $customer['id'] . '/export-csv') ?>`;
}

// Formatação de telefone no JavaScript (compatibilidade)
function formatPhone(phone) {
    if (!phone) return '';
    
    const cleaned = phone.replace(/\D/g, '');
    
    if (cleaned.length === 11) {
        return `(${cleaned.substr(0, 2)}) ${cleaned.substr(2, 5)}-${cleaned.substr(7)}`;
    } else if (cleaned.length === 10) {
        return `(${cleaned.substr(0, 2)}) ${cleaned.substr(2, 4)}-${cleaned.substr(6)}`;
    }
    
    return phone;
}

// Auto-hide alerts
document.addEventListener('DOMContentLoaded', function() {
    // Esconder alertas automaticamente após 5 segundos
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert:not(.alert-light)');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>

<?php
include(__DIR__ . '/../layout/footer.php');
?>