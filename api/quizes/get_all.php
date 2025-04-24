<?php
   require_once '../../db/connect.php';

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