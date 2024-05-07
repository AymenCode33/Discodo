<?php
session_start();

if(isset($_POST['submit'])) {
    $pseudo = htmlspecialchars($_POST['pseudo']);
    $password = htmlspecialchars($_POST['password']);

    try {
        $bdd = new PDO('mysql:host=localhost;dbname=discodo;charset=utf8', 'root', '');
    } catch(Exception $e) {
        die('Erreur : '. $e->getMessage());
    }

    // Vérifier si le pseudo existe déjà
    $stmt = $bdd->prepare('SELECT * FROM users WHERE pseudo = :pseudo');
    $stmt->bindParam(':pseudo', $pseudo);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if($result) {
        // Si le pseudo existe déjà, afficher un message d'erreur
        echo "<p style='color:red;'>Ce pseudo est déjà pris, veuillez en choisir un autre.</p>";
    } else {
        // Si le pseudo n'existe pas, continuer l'inscription

        // Récupérer le dernier id
        $stmt = $bdd->prepare('SELECT id FROM users ORDER BY id DESC LIMIT 1');
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if($result) {
            // Si un id a été trouvé, ajouter 1 à cet id
            $_SESSION['id'] = $result['id'] + 1;
        } else {
            // Si aucun id n'a été trouvé, initialiser l'id à 1
            $_SESSION['id'] = 1;
        }

        // Récupérer le dernier code ami
        $stmt = $bdd->prepare('SELECT code_ami FROM users ORDER BY code_ami DESC LIMIT 1');
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if($result) {
            // Si un code ami a été trouvé, ajouter 1 à ce code
            $_SESSION['code_ami'] = $result['code_ami'] + 1;
        } else {
            // Si aucun code ami n'a été trouvé, initialiser le code ami à 1
            $_SESSION['code_ami'] = 1;
        }

        // Préparer la requête SQL pour insérer le nouvel utilisateur
        $stmt = $bdd->prepare("INSERT INTO users (pseudo, password, id, code_ami) VALUES (:pseudo, :password, :id, :code_ami)");

        // Lier les paramètres à la requête SQL
        $stmt->bindParam(':pseudo', $pseudo);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':id', $_SESSION['id']);
        $stmt->bindParam(':code_ami', $_SESSION['code_ami']);

        // Exécuter la requête préparée
        if($stmt->execute()) {
            $_SESSION['pseudo'] = $pseudo;
            // Redirection vers la page d'accueil
            header('Location: ./index.php');
            exit;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <title>Bootstrap Example</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body class="p-3 m-0 border-0 bd-example m-0 border-0">
    <form action="" method="post">
        <div class="mb-3">
        <label style="color: white !important;" for="exampleFormControlInput1" class="form-label">Pseudo</label>
        <input type="text" class="form-control" id="exampleFormControlInput1" name="pseudo">
      </div>
      <div class="mb-3">
        <label style="color: white !important;" for="exampleFormControlInput1" class="form-label">Password</label>
        <input type="password" class="form-control" id="exampleFormControlInput1" placeholder="" name="password">
      </div>
      <div class="col-auto">
        <button type="submit" name="submit" class="btn btn-primary mb-3">Crée le compte</button>
      </div>
    </form>
    <p style="color: white;">Vous avec deja un compte <a href="./connection.php">Connecter vous</a></p>
</body>
</html>