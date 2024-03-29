<?php
require_once 'user.php'; // Include the parent class definition

class Tutor extends User implements JsonSerializable {
    protected $tutorId;
    protected $classesTaught;
    protected $rating;

    public function __construct($firstName, $lastName, $tutorId, $rating=null, $classesTaught = [], $email = null, $password = null) {
        parent::__construct($firstName, $lastName, $email, $password);
        $this->tutorId = $tutorId;
        $this->classesTaught = $classesTaught;
        $this->rating = $rating;
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

    public function getRating() {
        return $this->rating;
    }

    public function setRating($rating) {
        $this->rating = $rating;
    }

    public function jsonSerialize(): mixed {
        return get_object_vars($this);
    }
}

