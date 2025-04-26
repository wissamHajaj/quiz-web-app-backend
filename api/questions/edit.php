<?php 
    require_once '../../db/connect.php';
    require_once '../../utils/utils.php';

    check_request_method('POST');
    
    $id = $_POST['id'] ?? null; 
    $text = $_POST['text'] ?? null;
    $quiz_id = $_POST['quiz_id'] ?? null;

    if(!$id) {
        echo json_encode(["status" => "error", "Message" => "Question id is required"]);
        exit;
    }

    try {
        $check_question_query = "SELECT * FROM questions WHERE id = :id";
        $check_question_stmt = $conn->prepare($check_question_query);
        $check_question_stmt->bindParam(":id", $id);
        $check_question_stmt->execute();
        $question = $check_question_stmt->fetch(PDO::FETCH_ASSOC);

        if(!$question) {
            echo json_encode(["status" => "error", "Message" => "Question not found"]);
            exit;
        }
    
        if($quiz_id && $quiz_id !== '') {
            find_quiz_by_id($conn, $quiz_id);
        }

        $fields = [];
        $params = [];

        if($text !== null && $text !== '') {
            $fields[] = "text = :text";
            $params[":text"] = $text;
        }

        if($quiz_id !== null && $quiz_id !== '') {
            $fields[] = "quiz_id = :quiz_id";
            $params[":quiz_id"] = $quiz_id;
        }

        if(empty($fields)) {
            echo json_encode(["status" => "error", "message" => "No fields to update"]);
            exit;
        }
        $params[":id"] = $id;
        $query = "UPDATE questions SET " . implode(", ", $fields) . " WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->execute($params);

        echo json_encode(["status" => "success", "message" => "question updated successfuly"]);

    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
?>