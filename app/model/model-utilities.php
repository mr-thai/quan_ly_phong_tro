<?php
/**
 * Model: Tiện ích
 */

class ModelUtilities {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    /**
     * Get all utilities
     */
    public function getAll() {
        $query = "SELECT * FROM tien_ich ORDER BY ten";
        $result = $this->db->query($query);
        
        $utilities = [];
        while ($row = $result->fetch_assoc()) {
            $utilities[] = $row;
        }
        
        return $utilities;
    }
}
