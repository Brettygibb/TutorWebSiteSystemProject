<?php
require_once 'user.php'; // Include the parent class definition

class admin extends user {
    private $adminId;

    public function __construct($first_name, $last_name, $email, $adminId) {
        parent::__construct($first_name, $last_name, $email);
        $this->adminId = $adminId;
    }
    public function getAdminId() {
        return $this->adminId;
    }

    public function setAdminId($adminId) {
        $this->adminId = $adminId;
    }
}