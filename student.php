<?php
require_once 'User.php'; // Include the parent class definition

class Student extends User {
    private $studentId;
    

    public function __construct($firstName, $lastName, $studentId, $email= null, $password= null) {
        parent::__construct($firstName, $lastName, $email,$password);
        $this->studentId = $studentId;
    }

    public function getStudentId() {
        return $this->studentId;
    }

    public function setStudentId($studentId) {
        $this->studentId = $studentId;
    }
    
    public function getFullName() {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }
}
