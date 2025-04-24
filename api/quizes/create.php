<?php
    require_once '../../db/connect.php';

    $title = $_POST['title'] ?? null;
    if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK){
        $imageName = $_FILES['image']['name'];
        $imageTemp = $_FILES['image']['tmp_name'];
        move_uploaded_file($imageTemp, '../../assets/images/quiz-images/' . $imageName);
    }
   

   if(!$title) {
    echo json_encode(['status' => 'error', "message" => "Title is required"]);
    exit;
   }

    try {
        $query = "INSERT INTO quizes (title, image) VALUES (:title, :image)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':image', $imageName);

        $stmt->execute();
        echo json_encode(['status' => 'success', "message" => "Quiz created succesfully"]);

    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', "message" => $e->getMessage()]);

    }
?>