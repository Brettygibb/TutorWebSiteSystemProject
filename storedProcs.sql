DELIMITER $$
CREATE PROCEDURE SearchTutors(
    IN searchSubmit VARCHAR(255)
)
BEGIN
    SELECT tutors.*, users.FirstName, users.LastName, courses.CourseName 
    FROM tutors 
    JOIN users ON tutors.UserId = users.UserId
    JOIN courses ON tutors.CourseId = courses.CourseId
    WHERE TRIM(LOWER(users.FirstName)) LIKE TRIM(LOWER(CONCAT('%', searchSubmit, '%'))) 
       OR TRIM(LOWER(users.LastName)) LIKE TRIM(LOWER(CONCAT('%', searchSubmit, '%'))) 
       OR TRIM(LOWER(courses.CourseName)) LIKE TRIM(LOWER(CONCAT('%', searchSubmit, '%')));
END$$
DELIMITER ;