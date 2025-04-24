<?php 
    require_once '../../db/connect.php';

    $id = $_POST['id'] ?? null;

    if(!$id) {
        echo json_encode(["status" => "error", "Message" => "id is required"]);
        exit;
    }

    try {
        $query = "SELECT * FROM quizes WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $quiz = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$quiz) {
            echo json_encode(["status" => "error", "message" => "Quiz not found"]);
            exit;
        }

        if($quiz['image']) {
            $oldImagePath = '../../assets/images/quiz-images/' . $quiz['image'];
            if(file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }

        $delete_query = "DELETE FROM quizes WHERE id = :id";
        $delete_stmt = $conn -> prepare($delete_query);
        $delete_stmt->bindParam(":id" , $id);
        $delete_stmt->execute();

        echo json_encode(["status" => "success", "message" => "quiz deleted successfuly"]);

     } catch (PDOException $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
?>