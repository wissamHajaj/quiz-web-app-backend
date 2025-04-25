<?php 

    require_once '../../db/connect.php';

    function check_request_method($method) {
        if ($_SERVER['REQUEST_METHOD'] !== $method) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
            exit;
        }
    }

    
    function find_quiz_by_id($conn, $id) {
        $query = "SELECT * FROM quizes WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $quiz = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$quiz) {
            echo json_encode(["status" => "error", "message" => "Quiz not found"]);
            exit;
        }
        return $quiz;
    }
?>