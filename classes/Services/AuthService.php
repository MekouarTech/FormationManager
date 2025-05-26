<?php

class AuthService
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        session_start();
    }

    public function login($username, $password)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM Users WHERE username = :username LIMIT 1");
        $stmt->execute(['username' => $username]);

        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userData && password_verify($password, $userData['password'])) {
            $_SESSION['user'] = [
                'id' => $userData['id'],
                'authenticated' => true,
                'roleId' => $userData['roleId'],
                'username' => $userData['username'],
                'firstName' => $userData['firstName'],
                'lastName' => $userData['lastName'],
                'email' => $userData['email'],
                'isAdmin' => $userData['roleId'] == 2
            ];
            
            return true;
        }

        return false;
    }

    public function isLoggedIn()
    {
        return isset($_SESSION['user']) && $_SESSION['user']['authenticated'] === true;
    }

    public function logout()
    {
        session_destroy();
    }

    public function isAdmin()
    {
        return isset($_SESSION['user']) && $_SESSION['user']['roleId'] == 2;
    }

    public function isClient()
    {
        return isset($_SESSION['user']) && $_SESSION['user']['roleId'] == 1;
    }
}
?>