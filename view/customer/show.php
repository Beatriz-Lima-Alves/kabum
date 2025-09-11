<?php
/**
 * Arquivo: app/views/clientes/show.php
 * Sistema MVC - View para visualizar detalhes do cliente
 * 
 * IMPORTANTE: Este arquivo deve ser salvo como:
 * C:\xampp\htdocs\barbearia-new\app\views\clientes\show.php
 */

// Incluir configurações - AJUSTAR CAMINHO PARA SEU SISTEMA
require_once __DIR__ . '/../../../config/config.php';

$title = 'Detalhes do Cliente - Sistema de Barbearia';
$currentPage = 'clientes';

ob_start();
?>

<div class="container-fluid px-4">
    <!-- Cabeçalho -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user me-2"></i>
                <?= htmlspecialchars($cliente['nome']) ?>
            </h1>
            <p class="text-muted mb-0">Cliente #<?= $cliente['id'] ?></p>
        </div>
        <div>
            <a href="<?= url('clientes') ?>" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left me-1"></i>
                Voltar
            </a>
            <a href="<?= url('clientes/edit/' . $cliente['id']) ?>" class="btn btn-warning me-2">
                <i class="fas fa-edit me-1"></i>
                Editar
            </a>
            <a href="<?= url('agendamentos/create?cliente_id=' . $cliente['id']) ?>" class="btn btn-success">
                <i class="fas fa-calendar-plus me-1"></i>
                Novo Agendamento
            </a>
        </div>
    </div>

    <!-- Mensagens -->
    <?php if ($success = getFlash('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?= $success ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if ($error = getFlash('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <?= $error ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Informações do Cliente -->
        <div class="col-lg-4">
            <!-- Card Principal -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar-circle text-white me-3" 
                             style="width: 60px; height: 60px; font-size: 24px; background-color: <?= generateAvatarColor($cliente['nome']) ?>;">
                            <?= strtoupper(substr($cliente['nome'], 0, 2)) ?>
                        </div>
                        <div>
                            <h5 class="mb-0"><?= e($cliente['nome']) ?></h5>
                            <small class="text-muted">Cliente desde <?= formatDate($cliente['data_criacao']) ?></small>
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
                            <a href="tel:<?= $cliente['telefone'] ?>" class="text-decoration-none">
                                <?= formatPhone($cliente['telefone']) ?>
                            </a>
                            <button class="btn btn-sm btn-outline-success ms-2" onclick="copyToClipboard('<?= $cliente['telefone'] ?>')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Email -->
                    <?php if (!empty($cliente['email'])): ?>
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-1">
                            <i class="fas fa-envelope text-primary me-2"></i>
                            <strong>E-mail</strong>
                        </div>
                        <div class="ms-4">
                            <a href="mailto:<?= $cliente['email'] ?>" class="text-decoration-none">
                                <?= e($cliente['email']) ?>
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Data de Nascimento -->
                    <?php if (!empty($cliente['data_nascimento']) && $cliente['data_nascimento'] !== '0000-00-00'): ?>
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-1">
                            <i class="fas fa-birthday-cake text-primary me-2"></i>
                            <strong>Nascimento</strong>
                        </div>
                        <div class="ms-4">
                            <?= formatDate($cliente['data_nascimento']) ?>
                            <?php if ($idade = calculateAge($cliente['data_nascimento'])): ?>
                                <span class="badge bg-info ms-2">
                                    <?= $idade ?> anos
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Endereço -->
                    <?php if (!empty($cliente['endereco'])): ?>
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-1">
                            <i class="fas fa-map-marker-alt text-primary me-2"></i>
                            <strong>Endereço</strong>
                        </div>
                        <div class="ms-4">
                            <?= e($cliente['endereco']) ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Data de Cadastro -->
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-1">
                            <i class="fas fa-calendar text-primary me-2"></i>
                            <strong>Cadastrado em</strong>
                        </div>
                        <div class="ms-4">
                            <?= formatDateTime($cliente['data_criacao']) ?>
                            <br><small class="text-muted">
                                há <?= max(0, floor((time() - strtotime($cliente['data_criacao'])) / (24*60*60))) ?> dias
                            </small>
                        </div>
                    </div>

                    <!-- Observações -->
                    <?php if (!empty($cliente['observacoes'])): ?>
                    <div class="mb-0">
                        <div class="d-flex align-items-center mb-1">
                            <i class="fas fa-comment text-primary me-2"></i>
                            <strong>Observações</strong>
                        </div>
                        <div class="ms-4">
                            <div class="alert alert-light">
                                <?= nl2br(e($cliente['observacoes'])) ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Estatísticas Rápidas -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar me-2"></i>
                        Estatísticas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <div class="h4 text-primary mb-0">
                                    <?= isset($agendamentos) ? count($agendamentos) : 0 ?>
                                </div>
                                <small class="text-muted">Agendamentos</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="h4 text-success mb-0">
                                <?php
                                $realizados = 0;
                                if (isset($agendamentos)) {
                                    foreach ($agendamentos as $agend) {
                                        if ($agend['status'] === 'realizado') $realizados++;
                                    }
                                }
                                echo $realizados;
                                ?>
                            </div>
                            <small class="text-muted">Realizados</small>
                        </div>
                    </div>
                    
                    <!-- Valor Total Gasto -->
                    <?php if (isset($agendamentos) && !empty($agendamentos)): ?>
                    <hr class="my-3">
                    <div class="text-center">
                        <div class="h5 text-info mb-0">
                            <?php
                            $valorTotal = 0;
                            foreach ($agendamentos as $agend) {
                                if ($agend['status'] === 'realizado' && !empty($agend['valor'])) {
                                    $valorTotal += $agend['valor'];
                                }
                            }
                            echo formatMoney($valorTotal);
                            ?>
                        </div>
                        <small class="text-muted">Total Gasto</small>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Histórico de Agendamentos -->
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-calendar-check me-2"></i>
                            Histórico de Agendamentos
                        </h6>
                        <a href="<?= url('agendamentos/create?cliente_id=' . $cliente['id']) ?>" class="btn btn-sm btn-success">
                            <i class="fas fa-plus me-1"></i>
                            Novo Agendamento
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (empty($agendamentos)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Nenhum agendamento encontrado</h5>
                            <p class="text-muted">Este cliente ainda não possui agendamentos.</p>
                            <a href="<?= url('agendamentos/create?cliente_id=' . $cliente['id']) ?>" class="btn btn-primary">
                                <i class="fas fa-calendar-plus me-1"></i>
                                Criar Primeiro Agendamento
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Data/Hora</th>
                                        <th>Serviço</th>
                                        <th>Barbeiro</th>
                                        <th>Status</th>
                                        <th>Valor</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($agendamentos as $agendamento): ?>
                                    <tr class="<?= isToday($agendamento['data_agendamento']) ? 'table-warning' : '' ?>
                                               <?= isTomorrow($agendamento['data_agendamento']) ? 'table-info' : '' ?>">
                                        <td>
                                            <div>
                                                <strong><?= formatDate($agendamento['data_agendamento']) ?></strong>
                                                <?php if (isToday($agendamento['data_agendamento'])): ?>
                                                    <span class="badge bg-warning text-dark">Hoje</span>
                                                <?php elseif (isTomorrow($agendamento['data_agendamento'])): ?>
                                                    <span class="badge bg-info">Amanhã</span>
                                                <?php endif; ?>
                                            </div>
                                            <small class="text-muted">
                                                <?= date('H:i', strtotime($agendamento['data_agendamento'])) ?>
                                                <span class="ms-1">(<?= getDayName(date('l', strtotime($agendamento['data_agendamento']))) ?>)</span>
                                            </small>
                                        </td>
                                        <td>
                                            <?= e($agendamento['servico_nome'] ?? 'Não informado') ?>
                                        </td>
                                        <td>
                                            <?= e($agendamento['barbeiro_nome'] ?? 'Não informado') ?>
                                        </td>
                                        <td>
                                            <?= getStatusBadge($agendamento['status']) ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($agendamento['valor'])): ?>
                                                <?= formatMoney($agendamento['valor']) ?>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="<?= url('agendamentos/show/' . $agendamento['id']) ?>" 
                                                   class="btn btn-outline-primary" title="Ver detalhes">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <?php if (in_array($agendamento['status'], ['agendado', 'confirmado'])): ?>
                                                <a href="<?= url('agendamentos/edit/' . $agendamento['id']) ?>" 
                                                   class="btn btn-outline-warning" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <?php endif; ?>
                                                <?php if ($agendamento['status'] === 'agendado'): ?>
                                                <button class="btn btn-outline-success" 
                                                        onclick="confirmarAgendamento(<?= $agendamento['id'] ?>)" 
                                                        title="Confirmar">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Filtros e Ordenação -->
                        <div class="mt-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-filter me-2 text-muted"></i>
                                        <small class="text-muted">
                                            Mostrando <?= count($agendamentos) ?> agendamento(s)
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-6 text-end">
                                    <button class="btn btn-sm btn-outline-secondary" onclick="imprimirHistorico()">
                                        <i class="fas fa-print me-1"></i>
                                        Imprimir
                                    </button>
                                    <button class="btn btn-sm btn-outline-success" onclick="exportarCSV()">
                                        <i class="fas fa-file-csv me-1"></i>
                                        Exportar
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
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
                <a href="<?= url('clientes/delete/' . $cliente['id']) ?>" class="btn btn-danger">
                    <i class="fas fa-trash me-1"></i>
                    Excluir Cliente
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-circle {
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

.card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #5a5c69;
    background-color: #f8f9fc;
}

.table-warning td {
    background-color: rgba(255, 193, 7, 0.1);
}

.table-info td {
    background-color: rgba(13, 202, 240, 0.1);
}

@media print {
    .btn, .modal { display: none !important; }
    .card { border: 1px solid #000 !important; }
}
</style>

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
    window.location.href = `<?= url('clientes/' . $cliente['id'] . '/export-csv') ?>`;
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
$content = ob_get_clean();

// Incluir layout principal (ajustar caminho conforme sua estrutura)
if (file_exists(__DIR__ . '/../layouts/main.php')) {
    include __DIR__ . '/../layouts/main.php';
} else {
    // Layout simples caso não encontre o main.php
    ?>
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= $title ?></title>
        
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        
        <!-- Font Awesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
        
        <style>
            body { background-color: #f8f9fa; }
            .card { border: none; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        </style>
    </head>
    <body>
        <div class="container-fluid">
            <div class="py-4">
                <?= $content ?>
            </div>
        </div>
        
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        
        <!-- SweetAlert2 (opcional) -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </body>
    </html>
    <?php
}
?>

