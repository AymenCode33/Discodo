<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="./css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/style.css">
    <title>Connextion | Discodo</title>
</head>
<body>
    <form action="" method="post">
        <label style="color: white;">Mettre le pseudo</label>
        <input class="form-control" name="pseudo" type="text" placeholder="Mettre le pseudo" aria-label="default input example">
        <label style="color: white;">Mettre le mot de passe</label>
        <input class="form-control" name="password" type="text" placeholder="Mettre le mot de passe" aria-label="default input example">
        <button type="submit" name="submit" class="btn btn-primary">Valider</button>
    </form>
    <p style="color: white;">Vous avec pas de compte <a href="./inscription.php">Crée un compte</a></p>
</body>
</html>
<?php
session_start();
try {
    $bdd = new PDO('mysql:host=localhost;dbname=discodo;charset=utf8', 'root', '');
} catch(Exception $e) {
    die('Erreur : '. $e->getMessage());
}
if(isset($_POST["submit"])){
    $_POST["pseudo"] = htmlspecialchars($_POST["pseudo"]);
    $_POST["password"] = htmlspecialchars($_POST["password"]);
    $stmt = $bdd->prepare("SELECT * FROM `users` WHERE pseudo = :pseudo AND password = :password");
    $stmt->bindParam(":pseudo",$_POST["pseudo"]);
    $stmt->bindParam(":password",$_POST["password"]);
    if($stmt->execute())
    {
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if($result)
        {
            $_SESSION["pseudo"] = $_POST["pseudo"];
            $_SESSION["ami_code"] = $result["code_ami"];
            $_SESSION["id"] = $result["id"];
            header("Location: ./index.php");
            exit();
        }
        else
        {
            echo "<p style='color:red;'>Pseudo ou mot de passe incorrect</p>";
        }
    }
}

?>
