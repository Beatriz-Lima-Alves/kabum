<?php
require_once __DIR__ . '/../config/database.php';

/**
 * Model para customer
 */
class customer {
    
    /**
     * Buscar cliente por ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM customer WHERE id = ?";
        return DB::selectOne($sql, [$id]);
    }
    
    /**
     * Buscar cliente por phone
     */
    public function getByphone($phone) {
        $sql = "SELECT * FROM customer WHERE phone = ? AND active = 1";
        return DB::selectOne($sql, [$phone]);
    }
    
    /**
     * Buscar cliente por email
     */
    public function getByEmail($email) {
        $sql = "SELECT * FROM customer WHERE email = ? AND active = 1";
        return DB::selectOne($sql, [$email]);
    }
    
    /**
     * Listar todos os customer
     */
    public function getAll($active = null, $limit = null, $search = null, $offset = null) {
        $sql = "SELECT * FROM customer";
        $params = [];
        $conditions = [];
        
        if ($active !== null) {
            $conditions[] = "active = ?";
            $params[] = $active;
        }
        
        if ($search) {
            $conditions[] = "(name LIKE ? OR phone LIKE ? OR email LIKE ?)";
            $searchTerm = "%$search%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }
        
        $sql .= " ORDER BY name";
        
        if ($limit) {
            $sql .= " LIMIT ?";
            $params[] = $limit;
            if ($offset !== null) {
                $sql .= " OFFSET ?";
                $params[] = (int)$offset;
            }
        }
        
        return DB::select($sql, $params);
    }
    
    /**
     * Criar novo cliente
     */
    public function create($dados) {
        $sql = "INSERT INTO customer (name, phone, email, date_birth) 
                VALUES (?, ?, ?, ?)";
        
        $params = [
            $dados['name'],
            $dados['phone'],
            $dados['email'] ?? null,
            $dados['date_birth']
        ];
        
        return DB::insert($sql, $params);
    }
    
    /**
     * Atualizar cliente
     */
    public function update($id, $dados) {

        $sql = "UPDATE customer SET 
                name = ?, 
                phone = ?, 
                email = ?, 
                date_birth = ?,
                active = ?
                WHERE id = ?";
        
        $params = [
            $dados['name'],
            $dados['phone'],
            $dados['email'] ?? null,
            $dados['date_birth'] ?? null,
            $dados['active'] ?? 1,
            $id
        ];
        
        return DB::execute($sql, $params);
    }
    
    /**
     * Desativar cliente
     */
    public function deactivate($id) {
        $sql = "UPDATE customer SET active = 0 WHERE id = ?";
        return DB::execute($sql, [$id]);
    }
    
    /**
     * Verificar se phone já existe
     */
    public function phoneExists($phone, $excludeId = 0) {
        $sql = "SELECT COUNT(*) as total FROM customer WHERE phone = ? AND active = 1";
        $params = [$phone];
        
        if ($excludeId >0) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $result = DB::selectOne($sql, $params);
        if ($result && isset($result['total'])) {
            return $result['total'] > 0;
        }

        return false;
    }
    
    /**
     * Verificar se email já existe
     */
    public function emailExists($email, $excludeId = null) {
        if (empty($email)) return false;
        
        $sql = "SELECT COUNT(*) as total FROM customer WHERE email = ? AND active = 1";
        $params = [$email];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $result = DB::selectOne($sql, $params);
        return $result['total'] > 0;
    }
    
    
    /**
     * Contar total de customer
     */
    public function count() {
        $sql = "SELECT COUNT(*) as total FROM customer WHERE active = 1";
        $result = DB::selectOne($sql);
        return $result['total'];
    }
    
    
}
?>
