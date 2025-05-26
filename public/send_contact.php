<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/autoload.php';

$response = ['success' => false, 'message' => ''];

// Validate POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (!$name || !$email || !$subject || !$message) {
        $response['message'] = 'Tous les champs sont obligatoires.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Adresse email invalide.';
    } else {
            try {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    throw new Exception("Adresse e-mail invalide.");
                }

                // Initialize DB connection
                $db = new Database();
                $pdo = $db->getConnection();

                // Save to database
                $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, subject, message, created_at) 
                    VALUES (:name, :email, :subject, :message, :created_at)");

                $stmt->bindValue(':name', $name);
                $stmt->bindValue(':email', $email);
                $stmt->bindValue(':subject', $subject);
                $stmt->bindValue(':message', $message);
                $stmt->bindValue(':created_at', date('Y-m-d H:i:s'));
                $stmt->execute();

            /*

            // PHPMailer
            $mail = new PHPMailer(true);

            // SMTP settings
            $mail->isSMTP();
            $mail->Host = 'smtp.example.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'your_email@example.com';
            $mail->Password = 'your_password';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('email@example.com', 'FormationPro Contact');
            $mail->addAddress('admin@admin.com', 'Admin');

            $mail->Subject = "Nouveau message de contact : $subject";
            $mail->Body    = "Nom: $name\nEmail: $email\nSujet: $subject\n\nMessage:\n$message";

            $mail->send();

*/
                $response['success'] = true;
                $response['message'] = "Votre message a été envoyé avec succès.";

        } catch (Exception $e) {
            $response['message'] = 'Erreur lors de l\'envoi ou de l\'enregistrement: ' . $e->getMessage();
        }
    }
} else {
    $response['message'] = 'Méthode non autorisée.';
}

header('Content-Type: application/json');
echo json_encode($response);
?>