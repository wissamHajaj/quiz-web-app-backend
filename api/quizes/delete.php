<?php 
    require_once '../../db/connect.php';
    require_once '../../utils/utils.php';
    check_request_method('POST');

    $id = $_POST['id'] ?? null;

    if(!$id) {
        echo json_encode(["status" => "error", "Message" => "id is required"]);
        exit;
    }

    try {
        $quiz =  find_quiz_by_id($conn, $id);
        if($quiz['image']) {
            $oldImagePath = '../../assets/images/quiz-images/' . $quiz['image'];
            if(file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }

        $query = "DELETE FROM quizes WHERE id = :id";
        $stmt = $conn -> prepare($query);
        $stmt->bindParam(":id" , $id);
        $stmt->execute();

        echo json_encode(["status" => "success", "message" => "quiz deleted successfuly"]);

     } catch (PDOException $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
?>