<?php

class Certificate extends Model {
    protected $table = 'certificates';
    
    public function getUserCertificates($userId) {
        $stmt = $this->db->prepare("
            SELECT uc.*, c.title as course_title, uc.certificate_number, uc.issued_at 
            FROM user_certificates uc 
            INNER JOIN courses c ON uc.course_id = c.id 
            WHERE uc.user_id = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    public function issueCertificate($userId, $courseId) {
        $stmt = $this->db->prepare("
            SELECT * FROM user_certificates 
            WHERE user_id = ? AND course_id = ?
        ");
        $stmt->execute([$userId, $courseId]);
        
        if ($stmt->fetch()) {
            return false;
        }
        
        $certificateNumber = $this->generateCertificateNumber();
        
        $stmt = $this->db->prepare("
            INSERT INTO user_certificates (user_id, course_id, certificate_number) 
            VALUES (?, ?, ?)
        ");
        
        return $stmt->execute([$userId, $courseId, $certificateNumber]);
    }
    
    public function getCertificateByNumber($certificateNumber) {
        $stmt = $this->db->prepare("
            SELECT uc.*, c.title as course_title, 
                   CONCAT(u.first_name, ' ', u.last_name) as user_name 
            FROM user_certificates uc 
            INNER JOIN courses c ON uc.course_id = c.id 
            INNER JOIN users u ON uc.user_id = u.id 
            WHERE uc.certificate_number = ?
        ");
        $stmt->execute([$certificateNumber]);
        return $stmt->fetch();
    }
    
    private function generateCertificateNumber() {
        return 'CERT-' . date('Y') . '-' . strtoupper(bin2hex(random_bytes(6)));
    }
}

