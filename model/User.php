<?php
require_once __DIR__ . '/../config/database.php';

/**
 * Model para gerenciamento de usuários
 */
class User {
    
    /**
     * Busca usuário por email
     */
    public function getByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = ? AND active = 1";
        return DB::selectOne($sql, [$email]);
    }
    
    /**
     * Busca usuário por ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM users WHERE id = ? AND active = 1";
        return DB::selectOne($sql, [$id]);
    }
    
    /**
     * Lista todos os usuários actives
     */
    public function getAll($name = null) {
        $sql = "SELECT * FROM users WHERE active = 1";
        $params = [];
        
        if ($name) {
            $sql .= " AND name = '?' ";
            $params[] = $name;
        }
        
        $sql .= " ORDER BY name";
        return DB::select($sql, $params);
    }
    
    /**
     * Cria novo usuário
     */
    public function create($dados) {
        $sql = "INSERT INTO users (name, email, password) 
                VALUES (?, ?, ?)";
        
        $params = [
            $dados['name'],
            $dados['email'],
            $dados['password']
        ];
        
        return DB::insert($sql, $params);
    }
    
    /**
     * Atualiza dados do usuário
     */
    public function update($id, $dados) {
        $campos = [];
        $params = [];
        
        $camposPermitidos = [
            'name', 'email', 'password'
        ];
        
        foreach ($camposPermitidos as $campo) {
            if (isset($dados[$campo])) {
                $campos[] = "$campo = ?";
                $params[] = $dados[$campo];
            }
        }
        
        if (empty($campos)) {
            return false;
        }
        
        $params[] = $id;
        $sql = "UPDATE users SET " . implode(', ', $campos) . " WHERE id = ?";
        
        return DB::execute($sql, $params);
    }
    
    /**
     * Atualiza password do usuário
     */
    public function updatePassword($id, $novapassword) {
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        $passwordHash = password_hash($novapassword, PASSWORD_DEFAULT);
        return DB::execute($sql, [$passwordHash, $id]);
    }
    
    /**
     * Desativa usuário
     */
    public function delete($id) {
        $sql = "UPDATE users SET active = 0 WHERE id = ?";
        return DB::execute($sql, [$id]);
    }


     /**
     * Validar dados para atualização de perfil
     */
    public function validarDadosAtualizacao($dados, $userId) {
        $erros = [];
        
        // Validar name
        if (empty($dados['name'])) {
            $erros[] = 'name é obrigatório';
        }
        
        // Validar email
        if (empty($dados['email']) || !filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
            $erros[] = 'Email válido é obrigatório';
        }
        
        // Verificar se email já existe em outro usuário
        if ($this->emailExiste($dados['email'], $userId)) {
            $erros[] = 'Este email já está sendo usado por outro usuário';
        }
        
        // Validar password se fornecida
        if (!empty($dados['new_password'])) {
            if (empty($dados['password_actual'])) {
                $erros[] = 'password atual é obrigatória para alterar a password';
            } else {
                // Verificar password atual
                $usuario = $this->getById($userId);
                if (!$usuario || !password_verify($dados['password_actual'], $usuario['password'])) {
                    $erros[] = 'password atual incorreta';
                }
            }
            
            if (strlen($dados['new_password']) < 6) {
                $erros[] = 'Nova senha deve ter pelo menos 6 caracteres';
            }
            
            if ($dados['new_password'] !== $dados['confirm_password']) {
                $erros[] = 'Confirmação de senha não confere';
            }
        }
        
        return [
            'valido' => empty($erros),
            'erros' => $erros
        ];
    }

     /**
     * Verificar se email já existe (excluindo um ID específico) - Funcionalidade futura
     */
    public function emailExiste($email, $excluirId = null) {
        try {
            if ($excluirId) {
                $sql = "SELECT id FROM users WHERE email = ? AND id != ?";
                $result = DB::select($sql, [$email, $excluirId]);
            } else {
                $sql = "SELECT id FROM users WHERE email = ?";
                $result = DB::select($sql, [$email]);
            }
            return !empty($result);
        } catch (Exception $e) {
            error_log("Erro ao verificar se email existe: " . $e->getMessage());
            return false;
        }
    }
    
}
?>
