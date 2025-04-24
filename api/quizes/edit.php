<?php
   require_once '../../db/connect.php';

   $id = $_POST['id'] ?? null;
   $title = $_POST['title'] ?? null;
   $image = $_POST['image'] ?? null;
   if(!$id) {
    echo json_encode(["status" => "error", "Message" => "quiz id is required"]);
    exit;
   }

   // check if the quiz exist
   $query = "SELECT * FROM quizes WHERE id = :id";
   $stmt = $conn->prepare($query);
   $stmt->bindParam(':id', $id);
   $stmt->execute();
   $quiz = $stmt->fetch(PDO::FETCH_ASSOC);

   if(!$quiz) {
       echo json_encode(["status" => "error", "message" => "Quiz not found"]);
       exit;
    }



   $fields = [];
   $params = [];

   if($title !== null) {
    $fields[] = "title = :title";
    $params[":title"] = $title;
   }

   if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK){
        $query = "SELECT image FROM quizes WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $quiz = $stmt->fetch(PDO::FETCH_ASSOC);

        if($quiz && $quiz['image']) {
            $oldImagePath = '../../assets/images/quiz-images/' . $quiz['image'];
            if(file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }


        $imageName = $_FILES['image']['name'];
        $imageTemp = $_FILES['image']['tmp_name'];
        move_uploaded_file($imageTemp, '../../assets/images/quiz-images/' . $imageName);
        $fields[] = "image = :image";
        $params[":image"] = $imageName;
    }

   if(empty($fields)) {
    echo json_encode(["status" => "error", "message" => "No fields to update"]);
    exit;
   }

   $params[":id"] = $id;
   $query = "UPDATE quizes SET " . implode(", ", $fields) . " WHERE id = :id";



    try {
        $stmt = $conn->prepare($query);
        $stmt->execute($params);

        echo json_encode(["status" => "success", "message" => "quiz updated successfuly"]);
    } catch (PDOException $e) {

        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
?>