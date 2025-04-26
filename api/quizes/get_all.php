<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Content-Type: application/json');
    
    require_once '../../db/connect.php';
    require_once '../../utils/utils.php';

    check_request_method('GET');

    try {
        $query = "SELECT * FROM quizes";
        $stmt = $conn->query($query);
        $quizes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(empty($quizes)) {
            echo json_encode(["status" => "error", "message" => "No quizzes found"]);
            exit;
        }

        echo json_encode(["status" => "success", "data" => $quizes]);
    } catch (PDOException $e) {

        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
?>