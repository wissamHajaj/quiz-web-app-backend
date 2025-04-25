<?php 
    require_once '../../db/connect.php';

    $quiz_id = $_POST['id'] ?? null;

    if(!$quiz_id) {
        echo json_encode(['status' => 'error', "message" => "quiz id is required"]);
        exit;
    }

    try {
        $query = "SELECT * FROM quizes WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":id", $quiz_id);
        $stmt->execute();
        $quiz = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$quiz) {
            echo json_encode(["status" => "error", "message" => "Quiz not found"]);
            exit;
        }

        $get_query = "SELECT * FROM questions WHERE quiz_id = :id";
        $get_stmt = $conn->prepare($get_query);
        $get_stmt->bindParam(":id", $quiz_id);
        $get_stmt->execute();
        $questions = $get_stmt->fetchAll(PDO::FETCH_ASSOC);

        if(!$questions) {
            echo json_encode(['status' => 'error', "message" => "No questions found for this quiz "]);
            exit;
        }

        echo json_encode(['status' => 'success', "data" => $questions]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', "message" => $e->getMessage()]);
    }

?>