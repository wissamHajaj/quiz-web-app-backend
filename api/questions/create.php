<?php 
    require_once '../../db/connect.php';


    $text = $_POST['text'] ?? null;
    $quiz_id = $_POST['quiz_id'] ?? null;

    if(!$text) {
        echo json_encode(['status' => 'error', "message" => "Text question is required"]);
        exit;
    }

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
            echo json_encode(["status" => "error", "message" => "Invalid quiz Id"]);
            exit;
        }



        $insert_query = "INSERT INTO questions (text, quiz_id) VALUES (:text, :quiz_id)";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bindParam(":text", $text);
        $insert_stmt->bindParam(":quiz_id", $quiz_id);
        $insert_stmt->execute();
        echo json_encode(['status' => 'success', "message" => "Quiz question created succesfully"]);


    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', "message" => $e->getMessage()]);
    }
?>