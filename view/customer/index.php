<?php
$title = 'Portal';
$currentPage = 'customer';

// Garantir que as variáveis existam
$customers = $customers ?? [];

$paginacao = $paginacao ?? null;

include(__DIR__ . '/../layout/header.php');

?>
   
    <div class="container-fluid pt-2">
        <div class="row">
            <!-- Sidebar -->
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

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
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
                        Clientes
                    </h1>
                    <div>
                        <button class="btn btn-light me-2" data-bs-toggle="modal" data-bs-target="#pesquisaModal">
                            <i class="fas fa-search me-1"></i>
                            Pesquisar
                        </button>
                        <a href="<?php echo(SITE_URL.'/cliente');?>" class="btn btn-success">
                            <i class="fas fa-plus me-1"></i>
                            Novo Cliente
                        </a>
                    </div>
                </div>


                <!-- Tabela de Clientes -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-list me-2"></i>
                            Lista de Clientes
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($customers)): ?>
                            <div class="text-center py-5">
                                <i class="fas fa-user-plus fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Nenhum cliente encontrado</h5>
                                <p class="text-muted">Cadastre um novo cliente para começar</p>
                                <a href="<?php echo(SITE_URL.'/cliente');?>" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i>
                                    Cadastrar Cliente
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Cliente</th>
                                            <th>Contato</th>
                                            <th>Data Nascimento</th>
                                            <th>Cadastro</th>
                                            <th width="120">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($customers as $customer): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle bg-primary text-white me-3">
                                                        <?= strtoupper(substr($customer['name'], 0, 2)) ?>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold"><?= e($customer['name']) ?></div>
                                                        <?php if (!empty($customer['email'])): ?>
                                                            <small class="text-muted"><?= e($customer['email']) ?></small>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div><?= e($customer['phone']) ?></div>
                                                <!-- <?php if (!empty($customer['endereco'])): ?>
                                                    <small class="text-muted"><?= e($customer['endereco']) ?></small>
                                                <?php endif; ?> -->
                                            </td>
                                            <td>
                                                <?php if (!empty($customer['date_birth']) && $customer['date_birth'] !== '0000-00-00'): ?>
                                                    <?= date('d/m/Y', strtotime($customer['date_birth'])) ?>
                                                    <br><small class="text-muted">
                                                        <?= floor((time() - strtotime($customer['date_birth'])) / (365*24*60*60)) ?> anos
                                                    </small>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div><?= date('d/m/Y', strtotime($customer['created_at'] ?? 'now')) ?></div>
                                                <small class="text-muted"><?= date('H:i', strtotime($customer['created_at'] ??  'now')) ?></small>
                                            </td>
                                           
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="<?php echo(SITE_URL.'/detalhes_cliente').'/'. $customer['id'] ?>" 
                                                       class="btn btn-outline-primary" title="Visualizar">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="<?php echo(SITE_URL.'/edit_cliente') .'/'. $customer['id'] ?>" 
                                                       class="btn btn-outline-warning" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button class="btn btn-outline-danger" 
                                                            onclick="confirmarExclusao(<?= $customer['id'] ?>)" title="Excluir">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Paginação -->
                            <?php if (isset($paginacao) && $paginacao['total_page'] > 1): ?>
                            <nav class="mt-4">
                                <ul class="pagination justify-content-center">
                                    <?php if ($paginacao['page'] > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?pagina=<?= $paginacao['page'] - 1 ?>">Anterior</a>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <?php for ($i = 1; $i <= $paginacao['total_page']; $i++): ?>
                                        <li class="page-item <?= $i == $paginacao['page'] ? 'active' : '' ?>">
                                            <a class="page-link" href="?pagina=<?= $i ?>"><?= $i ?></a>
                                        </li>
                                    <?php endfor; ?>
                                    
                                    <?php if ($paginacao['page'] < $paginacao['total_page']): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?pagina=<?= $paginacao['page'] + 1 ?>">Próximo</a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal de Pesquisa -->
    <div class="modal fade" id="pesquisaModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pesquisar Clientes</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="GET">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Buscar por</label>
                            <input type="text" name="busca" class="form-control" 
                                   placeholder="Nome, telefone ou email..."
                                   value="<?= $_GET['busca'] ?? '' ?>">
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <a href="<?php echo(SITE_URL.'/portal')?>" class="btn btn-outline-warning">Limpar</a>
                        <button type="submit" class="btn btn-primary">Pesquisar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    // Garantir que a função está sempre disponível
    window.confirmarExclusao = function(id) {
        
        if (confirm('Tem certeza que deseja excluir este cliente?\n\nEsta ação não pode ser desfeita.')) {
            
            // Mostrar loading no botão
            const button = event.target.closest('button');
            if (button) {
                const originalHTML = button.innerHTML;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                button.disabled = true;
                
                // Restaurar botão após 5 segundos (fallback)
                setTimeout(() => {
                    button.innerHTML = originalHTML;
                    button.disabled = false;
                }, 5000);
            }
            
            // Criar formulário para envio
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = ` ${SITE_URL}/delete_cliente/${id}`;
            form.style.display = 'none';
            
            // Adicionar método DELETE
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);
            
            // Adicionar ao DOM e enviar
            document.body.appendChild(form);
            
            form.submit();
        } 
    };
    
    // Função alternativa usando event delegation
    document.addEventListener('click', function(e) {
        if (e.target.closest('button[onclick*="confirmarExclusao"]')) {
            e.preventDefault();
            
            // Extrair ID do onclick
            const onclickAttr = e.target.closest('button').getAttribute('onclick');
            const match = onclickAttr.match(/confirmarExclusao\((\d+)\)/);
            
            if (match && match[1]) {
                const id = match[1];
                
                window.confirmarExclusao(id);
            }
        }
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
                    
                }
            });
        }, 5000);
        
        // Verificar se as funções estão funcionando
        
        
        // Adicionar event listeners extras para debug
        const deleteButtons = document.querySelectorAll('button[onclick*="confirmarExclusao"]');
        
        
        deleteButtons.forEach((button, index) => {
            
            
            // Adicionar evento de click extra para teste
            button.addEventListener('click', function(e) {
                
                
            });
        });
    });
    </script>

<?php 
include(__DIR__ . '/../layout/footer.php');
?>
