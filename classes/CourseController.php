<?php
class CourseController {
    private $courseManager;

    public function __construct(CourseManager $courseManager) {
        $this->courseManager = $courseManager;
    }

    // Recherche les cours
    public function searchCourses($search) {
        return $this->courseManager->searchCourses($search);
    }

    // Supprime un cours
    public function deleteCourse($courseId) {
        return $this->courseManager->deleteCourse($courseId);
    }
}
