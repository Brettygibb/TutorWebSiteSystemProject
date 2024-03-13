<?php
require_once 'User.php'; // Include the parent class definition

class Admin extends User {
    private $adminId;

    public function __construct($firstName, $lastName, $email, $adminId) {
        parent::__construct($firstName, $lastName, $email);
        $this->adminId = $adminId;
    }

    public function getAdminId() {
        return $this->adminId;
    }

    public function setAdminId($adminId) {
        $this->adminId = $adminId;
    }
}
