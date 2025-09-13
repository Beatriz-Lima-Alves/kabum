<?php
require_once __DIR__ . '/../config/database.php';

/**
 * Model para customer address
 */
class CustomerAddress {
    
    /**
     * Criar novo endereço
     */
    public function create($dados) {
        $sql = "INSERT INTO customer_address (id_customer, address) 
                VALUES (?, ?)";
        
        $params = [
            $dados['id_customer'],
            $dados['address']
        ];
        
        return DB::insert($sql, $params);
    }
    
    /**
     * Atualizar endereço
     */
    public function update($id, $dados) {
        $sql = "UPDATE customer_address SET 
                active = ?, 
                address = ? 
                WHERE id = ?";
        
        $params = [
            $dados['active'] ?? 1,
            $dados['address'],
            $id
        ];
        
        return DB::execute($sql, $params);
    }
    
    /**
     * Desativar endereço
     */
    public function deactivate($id) {
        $sql = "UPDATE customer_address SET active = 0 WHERE id = ?";
        return DB::execute($sql, [$id]);
    }
/**
     * Desativar todos os endereço
     */
    public function deactivateAll($id_customer) {
        $sql = "UPDATE customer_address SET active = 0 WHERE id_customer = ?";
        return DB::execute($sql, [$id_customer]);
    }
    /**
     * Todos os endereços de um cliente
     */
    public function getAddresses($customerId) {
    $sql = "SELECT * FROM customer_address WHERE id_customer = ? and active = 1";
    return DB::select($sql, [$customerId]);
}
}
?>
