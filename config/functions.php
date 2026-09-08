<?php

function redirect($path){
    header("Location: ".BASE_URL.$path);
    exit;
}

function loginUser($pdo, $login, $password){
    // Application quer #2
    $sql = "
        SELECT 
            user_id,
            user_email,
            user_password,
            user_role
        FROM users
        WHERE user_email = :login
            OR user_username = :login
            LIMIT 1
    
    ";
    $stmt = $pdo->prepare($sql) ;
    $stmt->execute([':login' => $login]);

    $user = $stmt->fetch();

    if(!$user){
        return false;
    }
    

    if(!password_verify($password, $user['user_password'])){
        return false;
    }

    //Session Variables
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['user_email'] = $user['user_email'];
    $_SESSION['user_username'] = $user['user_username'];
    $_SESSION['user_role'] = $user['user_role'];
    return true;
}

function requireLogin(){
    if(!isset($_SESSION['user_id'])){
    header('Location: '.BASE_URL.'/index.php');
    exit;
    }
}
function requireRole($role){
    requireLogin();

    if($_SESSION ['user_role'] !== $role){
        http_response_code(403);
        die("Access Denied");
    }
};


?>

INSERT INTO users
(
    user_email,
    user_username,
    user_password,
    user_role
)
VALUES
(
    'admin@example.com',
    'admin',
    '$2y$10$HNfhClczEWBxcFuJwP53iu2Y75Tba7IEtmX8vX.1tp0dZ5EVt9CbO',
    'admin'
),
(
    'manager@example.com',
    'manager',
    '$2y$10$HNfhClczEWBxcFuJwP53iu2Y75Tba7IEtmX8vX.1tp0dZ5EVt9CbO',
    'manager'
),
(
    'user@example.com',
    'user',
    '$2y$10$HNfhClczEWBxcFuJwP53iu2Y75Tba7IEtmX8vX.1tp0dZ5EVt9CbO',
    'user'
);