<?php 
    require_once '../../db/connect.php';
    require_once '../../utils/utils.php';

    check_request_method('POST');


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
        find_quiz_by_id($conn, $quiz_id);

        $query = "INSERT INTO questions (text, quiz_id) VALUES (:text, :quiz_id)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":text", $text);
        $stmt->bindParam(":quiz_id", $quiz_id);
        $stmt->execute();
        echo json_encode(['status' => 'success', "message" => "Quiz question created succesfully"]);


    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', "message" => $e->getMessage()]);
    }
?>