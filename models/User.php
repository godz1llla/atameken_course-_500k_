<?php

class User extends Model {
    protected $table = 'users';
    
    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }
    
    private static $rateColumnExists = null;
    
    public function createUser($data) {
        if (!$this->hasRatePerClassColumn() && isset($data['rate_per_class'])) {
            unset($data['rate_per_class']);
        }
        
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        return $this->create($data);
    }
    
    public function updatePassword($id, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        return $this->update($id, ['password' => $hashedPassword]);
    }
    
    public function getAllByRole($role) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE role = ?");
        $stmt->execute([$role]);
        return $stmt->fetchAll();
    }
    
    public function toggleActive($id) {
        $user = $this->findById($id);
        $newStatus = $user['is_active'] ? 0 : 1;
        return $this->update($id, ['is_active' => $newStatus]);
    }
    
    public function hasRatePerClassColumn() {
        if (self::$rateColumnExists !== null) {
            return self::$rateColumnExists;
        }
        
        $stmt = $this->db->prepare("SHOW COLUMNS FROM {$this->table} LIKE 'rate_per_class'");
        $stmt->execute();
        self::$rateColumnExists = (bool) $stmt->fetch();
        
        return self::$rateColumnExists;
    }
}

