<?php 
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Content-Type: application/json');
    require_once '../../db/connect.php';
    require_once '../../utils/utils.php';

    check_request_method('GET');
  
      $question_id = $_GET['id'] ?? null;

    if(!$question_id) {
        echo json_encode(['status' => 'error', "message" => "question id is required"]);
        exit;
    }

    try {
        $check_question_query = "SELECT * FROM questions WHERE id = :id";
        $check_question_stmt = $conn->prepare($check_question_query);
        $check_question_stmt->bindParam(":id", $question_id);
        $check_question_stmt->execute();
        $question = $check_question_stmt->fetch(PDO::FETCH_ASSOC);

        if(!$question) {
            echo json_encode(['status' => 'error', "message" => "No question found"]);
            exit;
        }

        $query = "SELECT * FROM options WHERE question_id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":id", $question_id);
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(!$options) {
            echo json_encode(['status' => 'error', "message" => "No options found for this question"]);
            exit;
        }

        echo json_encode(['status' => 'success', 'data' => $options]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
?>