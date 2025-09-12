<?php
/**
 * VIEW DE EDIÇÃO DE CLIENTES - VERSÃO FUNCIONAL
 * Salvar como: C:\xampp\htdocs\barbearia-new\app\views\clientes\edit.php
 * 
 * Esta versão funciona tanto na versão simplificada quanto na principal
 * porque define suas próprias funções e carrega os dados necessários
 */

// Funções auxiliares simples (caso não existam)
if (!function_exists('old')) {
    function old($field, $default = '') {
        return $_POST[$field] ?? $_SESSION['form_data'][$field] ?? $default;
    }
}

if (!function_exists('e')) {
    function e($string) {
        return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
    }
}

$title = 'Editar Cliente - Sistema de Barbearia';
$currentPage = 'clientes';

// Os dados do cliente já vêm do controller na variável $cliente
// Não precisamos buscar novamente, apenas usar os dados que já temos

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
        :root {
            --primary-color: #1700e6ff;
            --secondary-color: #0c0d4eff;
            --accent-color: #e74c3c;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --info-color: #3B82F6;
        }

        .avatar-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 14px;
}
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar-brand {
            font-weight: bold;
            color: var(--primary-color) !important;
        }
        
        .sidebar {
            min-height: calc(100vh - 56px);
            background: linear-gradient(180deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        
        .sidebar .nav-link {
            color: #ecf0f1;
            padding: 12px 20px;
            margin: 2px 0;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .sidebar .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
            color: #fff;
            transform: translateX(5px);
        }
        
        .sidebar .nav-link.active {
            background-color: var(--accent-color);
            color: #fff;
        }
        
        .sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
        }
        
        .main-content {
            padding: 20px;
        }
        
        .page-header {
            background: linear-gradient(135deg, #1700e6, #3225e9);
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            color: white;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        
        .modern-card {
            background: white;
            border-radius: 16px;
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .modern-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .form-control, .form-select {
            border-radius: 12px;
            border: 2px solid #e5e7eb;
            padding: 0.75rem 1rem;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }
        
        .btn {
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #3498db, #2980b9);
            border: none;
        }
        
        .btn-success {
            background: linear-gradient(45deg, var(--success-color), #2ecc71);
            border: none;
            border-radius: 8px;
        }
        
        .btn-danger {
            background: linear-gradient(45deg, var(--accent-color), #c0392b);
            border: none;
            border-radius: 8px;
        }
        
        .btn-warning {
            background: linear-gradient(45deg, var(--warning-color), #e67e22);
            border: none;
            border-radius: 8px;
        }
        
        .dropdown-menu {
            border-radius: 8px;
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
            }
            
            .main-content {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="/barbearia-new/dashboard">
                <i class="fas fa-cut me-2"></i>
                Sistema Barbearia
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-2"></i>
                            <?= $_SESSION['user_nome'] ?? 'Usuário' ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="/barbearia-new/perfil">
                                <i class="fas fa-user me-2"></i>Meu Perfil
                            </a></li>
                            
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="/barbearia-new/logout">
                                <i class="fas fa-sign-out-alt me-2"></i>Sair
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?= $currentPage == 'dashboard' ? 'active' : '' ?>" href="/barbearia-new/dashboard">
                                <i class="fas fa-tachometer-alt"></i>
                                Dashboard
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?= $currentPage == 'agendamentos' ? 'active' : '' ?>" href="/barbearia-new/agendamentos">
                                <i class="fas fa-calendar-alt"></i>
                                Agendamentos
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?= $currentPage == 'clientes' ? 'active' : '' ?>" href="/barbearia-new/clientes">
                                <i class="fas fa-users"></i>
                                Clientes
                            </a>
                        </li>
                        
                        <?php if (($_SESSION['user_tipo'] ?? '') == 'administrador'): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= $currentPage == 'barbeiros' ? 'active' : '' ?>" href="/barbearia-new/usuarios">
                                <i class="fas fa-user-tie"></i>
                                Barbeiros
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?= $currentPage == 'servicos' ? 'active' : '' ?>" href="/barbearia-new/servicos">
                                <i class="fas fa-scissors"></i>
                                Serviços
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?= $currentPage == 'financeiro' ? 'active' : '' ?>" href="/barbearia-new/financeiro">
                                <i class="fas fa-chart-line"></i>
                                Financeiro
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <!-- <li class="nav-item">
                            <a class="nav-link <?= $currentPage == 'agenda' ? 'active' : '' ?>" href="/barbearia-new/agenda">
                                <i class="fas fa-calendar-week"></i>
                                Minha Agenda
                            </a>
                        </li> -->
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content pt-4">
                <!-- Cabeçalho -->
                <div class="page-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="h2 mb-2">
                                <i class="fas fa-user-edit me-3"></i>
                                Editar Cliente
                            </h1>
                            <p class="mb-0 opacity-90">Atualize as informações do cliente</p>
                        </div>
                        <div>
                            <a href="/barbearia-new/clientes" class="btn btn-light me-2">
                                <i class="fas fa-arrow-left me-2"></i>
                                Voltar aos Clientes
                            </a>
                            <a href="/barbearia-new/clientes/show/<?= $cliente['id'] ?>" class="btn btn-outline-light">
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

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['errors']) && is_array($_SESSION['errors'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <ul class="mb-0">
                            <?php foreach ($_SESSION['errors'] as $error): ?>
                                <li><?= e($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['errors']); ?>
                <?php endif; ?>

                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="modern-card">
                            <div class="card-header bg-transparent border-0 pt-4 px-4">
                                <h5 class="card-title mb-0 fw-bold">
                                    <i class="fas fa-user-edit me-2 text-primary"></i>
                                    Dados do Cliente
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <form method="POST" action="/barbearia-new/clientes/update/<?= $cliente['id'] ?>" id="formEditarCliente">
                                    <input type="hidden" name="id" value="<?= $cliente['id'] ?>">
                                    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="nome" class="form-label">
                                                    <i class="fas fa-user me-1"></i>
                                                    Nome Completo *
                                                </label>
                                                <input type="text" name="nome" id="nome" class="form-control" 
                                                       value="<?= e(old('nome', $cliente['nome'] ?? '')) ?>" 
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
                                                <input type="tel" name="telefone" id="telefone" class="form-control" 
                                                       value="<?= e(old('telefone', $cliente['telefone'] ?? '')) ?>" 
                                                       placeholder="(11) 99999-9999" required>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="data_nascimento" class="form-label">
                                                    <i class="fas fa-birthday-cake me-1"></i>
                                                    Data de Nascimento
                                                </label>
                                                <input type="date" name="data_nascimento" id="data_nascimento" class="form-control" 
                                                       value="<?= e(old('data_nascimento', $cliente['data_nascimento'] ?? '')) ?>" 
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
                                                       value="<?= e(old('email', $cliente['email'] ?? '')) ?>" 
                                                       placeholder="cliente@email.com">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="endereco" class="form-label">
                                                    <i class="fas fa-map-marker-alt me-1"></i>
                                                    Endereço
                                                </label>
                                                <input type="text" name="endereco" id="endereco" class="form-control" 
                                                       value="<?= e(old('endereco', $cliente['endereco'] ?? '')) ?>" 
                                                       placeholder="Rua, número, bairro">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="observacoes" class="form-label">
                                            <i class="fas fa-comment me-1"></i>
                                            Observações
                                        </label>
                                        <textarea name="observacoes" id="observacoes" class="form-control" rows="4" 
                                                  placeholder="Observações sobre o cliente..."><?= e(old('observacoes', $cliente['observacoes'] ?? '')) ?></textarea>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <a href="/barbearia-new/clientes" class="btn btn-secondary">
                                                <i class="fas fa-times me-1"></i>
                                                Cancelar
                                            </a>
                                        </div>
                                        <div>
                                            <button type="button" class="btn btn-danger me-2" onclick="confirmarExclusao(<?php echo $id; ?>)">
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
                    </div>
                </div>
            </main>
        </div>
    </div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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

function confirmarExclusao(id) {
    if (confirm('Tem certeza que deseja excluir este cliente?\n\nEsta ação não pode ser desfeita.')) {
        
        // Mostrar loading
        const button = event.target.closest('button');
        const originalHTML = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        button.disabled = true;
        
        // Tentar primeiro com fetch DELETE
        fetch(`/barbearia-new/clientes/delete/${id}`, {
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
                enviarExclusaoViaPOST(id);
            } else {
                throw new Error('Erro na exclusão');
            }
        })
        .catch(error => {
            console.log('Fetch falhou, tentando POST...', error);
            // Fallback: usar POST
            enviarExclusaoViaPOST(id);
        })
        .finally(() => {
            // Restaurar botão
            button.innerHTML = originalHTML;
            button.disabled = false;
        });
    }
}

function enviarExclusaoViaPOST(id) {
    // Criar formulário oculto
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/barbearia-new/clientes/delete/${id}`;
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
</script>

</body>
</html>