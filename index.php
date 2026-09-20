<?php

$method = $_SERVER['REQUEST_METHOD'];
$nom = "";
$email = "";
$message = "";
$erreurs = [];

if ($method === 'POST') {
  $nom = trim($_POST['nom'] ?? "");
  $email = trim($_POST['email'] ?? "");
  $message = trim($_POST['message'] ?? "");

  if ($nom === "") {
    $erreurs[] = "Le nom est obligatoire";
  }

  if ($email === "") {
    $erreurs[] = "L’email est obligatoire";
  } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    $erreurs[] = "L’email n’est pas valide !";
  }

  if ($message === "") {
    $erreurs[] = "Le message est obligatoire";
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
          <label for="nom">Nom :</label>
          <input type="text" name="nom" id="nom" value="<?= htmlspecialchars($nom) ?>" placeholder="Entrer votre nom..." required>

          <label for="email">Email :</label>
          <input type="email" name="email" id="email" value="<?= htmlspecialchars($email) ?>" placeholder="Entrer votre email..." required>

          <label for="message">Message :</label>
          <textarea name="message" id="message" rows="5" placeholder="Entrer votre message..." required><?= htmlspecialchars($message) ?></textarea>

          <input type="submit" class="btn" value="Envoyer">
        </form>

        <ul class="list_erreur">

          <?php if (!empty($erreurs)): ?>
            <?php foreach($erreurs as $erreur): ?>
              <li>
                <?= htmlspecialchars($erreur) ?>
              </li>
            <?php endforeach; ?>
          <?php endif; ?>

          <?php if (empty($erreurs) && $method === 'POST'): ?>
            <?php echo "<p>Formulaire valide !</p>"; ?>
          <?php endif; ?>
        </ul>
      </article>
    </section>
  </main>
</body>
</html>