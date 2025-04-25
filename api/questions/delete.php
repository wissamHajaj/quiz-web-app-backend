<?php 
    require_once '../../db/connect.php';

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
        exit;
    }
    $id = $_POST['id'] ?? null;

    if(!$id) {
        echo json_encode(["status" => "error", "Message" => "Question id is required"]);
        exit;
    }

    try {
        $query = "SELECT * FROM questions WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $question = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$question) {
            echo json_encode(["status" => "error", "message" => "Question not found"]);
            exit;
        }

        $delete_query = "DELETE FROM questions WHERE id = :id";
        $delete_stmt = $conn->prepare($delete_query);
        $delete_stmt->bindParam(":id" , $id);
        $delete_stmt->execute();

        echo json_encode(["status" => "success", "message" => "question deleted successfuly"]);
        } catch (PDOException $e) {
        echo json_encode(['status' => 'error', "message" => $e->getMessage()]);
    }
?>