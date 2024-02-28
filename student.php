<?php
class Student extends User {
    private $studentId;

    public function __construct($first_name, $last_name, $email, $studentId) {
        parent::__construct($first_name, $last_name, $email);
        $this->studentId = $studentId;
    }
    public function getStudentId() {
        return $this->studentId;
    }

    public function setStudentId($studentId) {
        $this->studentId = $studentId;
    }
}