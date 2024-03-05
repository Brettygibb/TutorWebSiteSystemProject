<?php
session_start();
include '../Database.php';

$db = new Database($servername, $username, $password, $dbname);

$conn = $db->getConnection();

if (!isset($_SESSION['tutorId']) || !isset($_GET['sessionId']) || !isset($_GET['action'])) {
    // Redirect to login or error page
    header("Location: login.php");
    exit;
}

$sessionid =filter_input(INPUT_GET, 'sessionId', FILTER_SANITIZE_STRING);
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);

if(!in_array($action, ['accept', 'deny'])){
    // Redirect to login or error page
    header("Location: login.php");
    exit;
}

$conn->begin_transaction();

try{

    $status = $action === 'accept' ? 'Approved' : 'Denied';
    $stmt = $conn->prepare("UPDATE session_request SET status = ? WHERE RequestId = ?");
    $stmt->bind_param("si", $status, $sessionid);
    $success = $stmt->execute();
    $stmt->close();

    if($success){
        $stmt = $conn->prepare("Delete from tutor_availability where tutorId = ? and date = ? and starttime = ?");
    }
}



if($success){
    header("Location: ../UpcomingTutorSessions.php?success=true");
} else {
    header("Location: ../UpcomingTutorSessions.php?success=false");
}