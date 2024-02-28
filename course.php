<?php
class Course implements JsonSerializable {
    protected $courseId;
    protected $courseName;

    public function __construct($courseId, $courseName) {
        $this->courseId = $courseId;
        $this->courseName = $courseName;
    }

    public function getCourseId() {
        return $this->courseId;
    }

    public function setCourseId($courseId) {
        $this->courseId = $courseId;
    }

    public function getCourseName() {
        return $this->courseName;
    }

    public function setCourseName($courseName) {
        $this->courseName = $courseName;
    }
    public function jsonSerialize(): mixed {
        return get_object_vars($this);
    }
}
?>
