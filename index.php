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
          <input type="text" name="nom" id="nom" placeholder="Entrer votre nom..." required>

          <label for="email">Email :</label>
          <input type="email" name="email" id="email" placeholder="Entrer votre email..." required>

          <label for="message">Message :</label>
          <textarea name="message" id="message" rows="5" placeholder="Entrer votre message..." required></textarea>

          <input type="submit" class="btn" value="Envoyer">
        </form>
      </article>
    </section>
  </main>
</body>
</html>