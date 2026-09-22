<?php

session_start();

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require_once __DIR__ . '/config/database.php';

$method = $_SERVER['REQUEST_METHOD'];
$nom = "";
$email = "";
$message = "";
$erreurs = [];
$succes = false;

$succes = $_SESSION['succes'] ?? null;
unset($_SESSION['succes']);

if ($method === 'POST') {
  $nom = trim($_POST['nom'] ?? "");
  $email = trim($_POST['email'] ?? "");
  $message = trim($_POST['message'] ?? "");

  $csrfToken = $_POST['csrf_token'] ?? '';

  if (
    !isset($_SESSION['csrf_token']) ||
    !is_string($csrfToken) ||
    !hash_equals($_SESSION['csrf_token'], $csrfToken)
  ) {
    $erreurs[] = "La requête est invalide.";
  }

  if ($nom === "") {
    $erreurs[] = "Le nom est obligatoire";
  } elseif (mb_strlen($nom) > 100) {
    $erreurs[] = "Le nom ne doit pas dépasser 100 caractères.";
  } elseif (!preg_match("/^[\p{L}\p{N}\s'-]+$/u", $nom)) {
    $erreurs[] = "Le nom contient des caractères non autorisés.";
  }

  if ($email === "") {
    $erreurs[] = "L’email est obligatoire";
  } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    $erreurs[] = "L’email n’est pas valide !";
  } elseif (mb_strlen($email) > 255) {
    $erreurs[] = "L'adresse email ne doit pas dépasser 255 caractères.";
  }

  if ($message === "") {
    $erreurs[] = "Le message est obligatoire";
  } elseif (mb_strlen($message) > 5000) {
    $erreurs[] = "Le message ne doit pas dépasser 5000 caractères.";
  }

  if (empty($erreurs)) {

  try {
    $stmt = $pdo->prepare("
      INSERT INTO contacts (nom, email, message)
      VALUES (:nom, :email, :message)
    ");

    $stmt->execute([
      ':nom' => $nom,
      ':email' => $email,
      ':message' => $message
    ]);

    $_SESSION['succes'] = "Message envoyé avec succès !";

    header('Location: index.php');
    exit;

  } catch (PDOException $e) {
    error_log($e->getMessage());
    $erreurs[] = "Une erreur est survenue lors de l'envoi du message.";
  }
}
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact - Form</title>
</head>
<body>
  <main class="container">
    <section class="section">
      <article class="formulaire">
        <form action="" method="post" class="form">
          <input
    type="hidden"
    name="csrf_token"
    value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>"
>
          <label for="nom">Nom :</label>
          <input
            type="text"
            name="nom"
            id="nom"
            value="<?= htmlspecialchars($nom) ?>"
            placeholder="Entrer votre nom..."
            required
          >

          <label for="email">Email :</label>
          <input
            type="email"
            name="email"
            id="email"
            value="<?= htmlspecialchars($email) ?>"
            placeholder="Entrer votre email..."
            required
          >

          <label for="message">Message :</label>
          <textarea
            name="message"
            id="message"
            rows="5"
            placeholder="Entrer votre message..."
            required
          ><?= htmlspecialchars($message) ?></textarea>

          <input type="submit" class="btn" value="Envoyer">
        </form>

        <ul class="list_erreur">
          <?php if (!empty($erreurs)): ?>
            <?php foreach ($erreurs as $erreur): ?>
              <li>
                <?= htmlspecialchars($erreur) ?>
              </li>
            <?php endforeach; ?>
          <?php endif; ?>

          <?php if ($succes !== null): ?>
            <p><?= htmlspecialchars($succes) ?></p>
          <?php endif; ?>
        </ul>
      </article>
    </section>
  </main>
</body>
</html>