<?php
require_once 'User.php'; // Include the parent class definition

class Tutor extends User {
    private $tutorId;

    public function __construct($firstName, $lastName, $email, $tutorId) {
        parent::__construct($firstName, $lastName, $email);
        $this->tutorId = $tutorId;
    }

    public function getTutorId() {
        return $this->tutorId;
    }

    public function setTutorId($tutorId) {
        $this->tutorId = $tutorId;
    }
}
