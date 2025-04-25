<?php 
    require_once '../../db/connect.php';
    require_once '../../utils/check_request_method.php';

    check_request_method('POST');
    
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;

    if(!$email || !$password) {
        echo json_encode(['status' => 'error', 'message' => 'All fields required']);
        exit;
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email']);
        exit;
    }

    try {
        $check_user_query = "SELECT * FROM users WHERE email = :email";
        $check_user_stmt = $conn->prepare($check_user_query);
        $check_user_stmt->bindParam(":email", $email);
        $check_user_stmt->execute();
        $user_exist = $check_user_stmt->fetch(PDO::FETCH_ASSOC);
    
        if(!$user_exist) {
            echo json_encode(['status' => 'error', 'message' => 'Account not found']);
            exit;
        }
        if(password_verify($password, $user_exist['password'])) {
            echo json_encode(['status' => 'error', 'message' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Incorrect password']);
        }

    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', "message" => $e->getMessage()]);

    }
?>