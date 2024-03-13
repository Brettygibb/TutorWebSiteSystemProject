<?php

require_once 'user.php';

class tutor extends User implements JsonSerializable {
    protected $tutorId;
    protected $classesTaught;

    public function __construct($firstName, $lastName, $tutorId, $classesTaught = [], $email = null) {
        parent::__construct($firstName, $lastName, $email);
        $this->tutorId = $tutorId;
        $this->classesTaught = $classesTaught;
    }

    
    public function getTutorId() {
        return $this->tutorId;
    }

    public function setTutorId($tutorId) {
        $this->tutorId = $tutorId;
    }
    

    public function getClassesTaught() {
        return $this->classesTaught;
    }

    public function setClassesTaught($classesTaught) {
        $this->classesTaught = $classesTaught;
    }

    public function jsonSerialize(): mixed {
        return get_object_vars($this);
    }
}


//look into optional parameters