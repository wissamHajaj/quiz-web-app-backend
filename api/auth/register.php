<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Content-Type: application/json');

    require_once '../../db/connect.php';
    require_once '../../utils/utils.php';
   
    check_request_method('POST');

    $input = json_decode(file_get_contents("php://input"), true);

    $name = $input['name'] ?? null;
    $email = $input['email'] ?? null;
    $password = $input['password'] ?? null;

    if(!$name || !$email || !$password) {
        echo json_encode(['status' => 'error', 'message' => 'All fields required']);
        exit;
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email']);
        exit;
    }

    try {
        $check_email_query = "SELECT * FROM users WHERE email = :email";
        $check_email_stmt = $conn->prepare($check_email_query);
        $check_email_stmt->bindParam(":email", $email);
        $check_email_stmt->execute();
        $email_exist = $check_email_stmt->fetch(PDO::FETCH_ASSOC);
    
        if($email_exist) {
            echo json_encode(['status' => 'error', 'message' => 'This email is already taken try another one']);
            exit;
        }
        
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', "message" => $e->getMessage()]);

    }

   
    if(strlen($password) < 8) {
        echo json_encode(['status' => 'error', 'message' => 'password should be at least 8 characters']);
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    try {
        $insert_query = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
        $insert_query_stmt = $conn->prepare($insert_query);
        $insert_query_stmt->bindParam(':name', $name);
        $insert_query_stmt->bindParam(':email', $email);
        $insert_query_stmt->bindParam(':password', $hashed_password);
        $insert_query_stmt->execute();
    
        echo json_encode(['status' => 'success', 'message' => 'Registration successful']);

    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', "message" => $e->getMessage()]);

    }
?>