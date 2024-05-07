<?php
session_start();
try {
    $bdd = new PDO('mysql:host=localhost;dbname=discodo;charset=utf8', 'root', '');
} catch(Exception $e) {
    die('Erreur : '. $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="./css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/style.css">
    <title>Discodo | Supprimer votre compte</title>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary ma-classe-personnalisee">
      <div class="container-fluid">
        <a class="navbar-brand" href="index.php" _msttexthash="349570" _msthash="210">Home</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" _msthidden="A" _mstaria-label="320099" _msthash="211">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="ami.php" _msttexthash="111306" _msthash="212">Ami</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" _msttexthash="313989" _msthash="214">Compte</a>
              <ul class="dropdown-menu" _msthidden="3">
                <li _msthidden="1"><a class="dropdown-item" href="inscription.php" _msttexthash="76466" _msthidden="1" _msthash="215">Crée un compte</a></li>
                <li _msthidden="1"><a class="dropdown-item" href="connection.php" _msttexthash="232752" _msthidden="1" _msthash="216">Connection</a></li>
                <li _msthidden="1"><a class="dropdown-item" href="#" _msttexthash="349791" _msthidden="1" _msthash="217">Votre compte : <?=$_SESSION['pseudo']?></a></li>
                <li _msthidden="1"><a class="dropdown-item" href="./clear.php" _msttexthash="349791" _msthidden="1" _msthash="217"><button type="submit" class="btn btn-outline-danger">Supprimer votre compte</button></a></li>
              </ul>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>
<div class="delete">
  <form method="post">
    <div class="col-6">
        <input class="form-control form-control-sm" type="name" name="password_delete" placeholder="Mettre votre mot de passe" aria-label="default input example">
    </div>
        <button type="submit" name="delete" class="btn btn-outline-danger primary btn-lg">Supprimer votre compte</button>
        </form>    
    </div>
    <?php
    if(isset($_POST['delete'])) 
    {
    $password_delete = htmlspecialchars($_POST['password_delete']);
    $stmt = $bdd->prepare("DELETE FROM users WHERE password = :password AND pseudo = :pseudo");
    $stmt->bindParam(':password', $password_delete);
    $stmt->bindParam(':pseudo', $_SESSION["pseudo"]);
    if($stmt->execute()){
       echo "<p style=\"color:red;\">Votre compte a bien été supprimé</p>";
       $_SESSION["pseudo"] = "";
       header("./index.php");
    }  
    
    } 
    ?>
</body>
</html>