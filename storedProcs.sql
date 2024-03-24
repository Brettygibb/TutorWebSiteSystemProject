DELIMITER $$
CREATE PROCEDURE SearchTutors(
    IN searchSubmit VARCHAR(255)
)
BEGIN
    SELECT tutors.*, users.FirstName, users.LastName, courses.CourseName
FROM tutors
JOIN users ON tutors.UserId = users.UserId
JOIN tutor_courses ON tutors.TutorId = tutor_courses.TutorId
JOIN courses ON tutor_courses.CourseId = courses.CourseId
WHERE TRIM(LOWER(users.FirstName)) LIKE TRIM(LOWER(CONCAT('%', searchSubmit, '%')))
   OR TRIM(LOWER(users.LastName)) LIKE TRIM(LOWER(CONCAT('%', searchSubmit, '%')))
   OR TRIM(LOWER(courses.CourseName)) LIKE TRIM(LOWER(CONCAT('%', searchSubmit, '%')));
END$$
DELIMITER ;


DELIMITER $$
CREATE PROCEDURE GetUserByEmail (
IN userEmail VARCHAR(255))
BEGIN
select * from users where email = userEmail;
END$$
DELIMITER ;

DELIMITER$$
CREATE PROCEDURE AddUser(
    IN p_FirstName VARCHAR(255), IN p_LastName VARCHAR(255), IN p_Email VARCHAR(255), IN p_PasswordHash VARCHAR(255), IN p_ConfirmationToken VARCHAR(255))
BEGIN
INSERT INTO users (FirstName, LastName, Email, PasswordHash, ConfirmationToken)
    VALUES (p_FirstName, p_LastName, p_Email, p_PasswordHash, p_ConfirmationToken);
END$$
DELIMITER ;