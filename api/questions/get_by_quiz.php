<?php 
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Content-Type: application/json');
    require_once '../../db/connect.php';
    require_once '../../utils/utils.php';

    check_request_method('GET');

    $quiz_id = $_GET['id'] ?? null;

    if(!$quiz_id) {
        echo json_encode(['status' => 'error', "message" => "quiz id is required"]);
        exit;
    }

    try {
        $quiz = find_quiz_by_id($conn, $quiz_id);

        $query = "SELECT * FROM questions WHERE quiz_id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":id", $quiz_id);
        $stmt->execute();
        $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(!$questions) {
            echo json_encode(['status' => 'error', "message" => "No questions found for this quiz "]);
            exit;
        }

        $question_with_options = [];
        foreach($questions as $question) {
            $query_options = "SELECT * FROM options WHERE question_id = :id";
            $stmt_options = $conn->prepare($query_options);
            $stmt_options->bindParam(":id", $question['id']);
            $stmt_options->execute();
            $options = $stmt_options->fetchAll(PDO::FETCH_ASSOC);

            $question_with_options[] = [
                'id' => $question['id'],
                'text' => $question['text'],
                'options' => $options
            ];
        }

        echo json_encode(['status' => 'success', 'data' => $question_with_options, 'title' => $quiz['title']]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
?>