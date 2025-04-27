<?php 
    require_once '../../db/connect.php';
    require_once '../../utils/utils.php';

    check_request_method('GET');

    try {
    $user_info_query = "SELECT name, email, title, score, taken_at 
                        FROM user_scores 
                        JOIN quizes ON quizes.id = quiz_id
                        JOIN users ON users.id = user_id";

    $user_info_query_stmt = $conn->prepare($user_info_query);
    $user_info_query_stmt->execute();
    $results = $user_info_query_stmt->fetchAll(PDO::FETCH_ASSOC);

    $users = [];
    foreach($results as $result) {
        $email = $result['email'];

        if(!isset($users[$email])) {
            $users[$email] = [
                'name'=> $result['name'],
                'email'=> $result['email'],
                'quizes'=> []
            ];
        }

        $users[$email]['quizes'][] = [
            'title' => $result['title'],
            'score' => $result['score'],
            'taken_at' => $result['taken_at']
        ];
    }

    $users = array_values($users);

    echo json_encode(['status' => 'success', 'data' => $users]);
        
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
?>