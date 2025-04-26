<?php
    require_once '../../db/connect.php';
    require_once '../../utils/utils.php';

    check_request_method('POST');

    $title = $_POST['title'] ?? null;
    
    if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK){
        $imageName = $_FILES['image']['name'];
        $imageTemp = $_FILES['image']['tmp_name'];

        $ext = pathinfo($imageName, PATHINFO_EXTENSION);
        $new_image_name = $title.'_'.date('Hms-dmY').'.'.$ext;

        move_uploaded_file($imageTemp, '../../assets/images/quiz-images/' . $new_image_name);
    }
   
   if(!$title) {
    echo json_encode(['status' => 'error', "message" => "Title is required"]);
    exit;
   }

    try {
        $query = "INSERT INTO quizes (title, image) VALUES (:title, :image)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':image', $new_image_name);

        $stmt->execute();
        echo json_encode(['status' => 'success', "message" => "Quiz created succesfully"]);

    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', "message" => $e->getMessage()]);
    }
?>