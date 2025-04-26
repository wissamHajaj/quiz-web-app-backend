<?php 
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Content-Type: application/json');
    
    require_once '../../db/connect.php';
    require_once '../../utils/utils.php';

    check_request_method('POST');
    $input = json_decode(file_get_contents("php://input"), true);

    $email = $input['email'] ?? null;
    $password = $input['password'] ?? null;

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
            echo json_encode(['status' => 'success', 'message' => 'Login successful']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Incorrect password']);
        }

    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', "message" => $e->getMessage()]);

    }
?>