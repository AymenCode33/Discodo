<?php
session_start();
?>
<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/style.css">
    <title>Bootstrap Example</title>
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
      
    <div class="input_friend">
      <form method="post" >
          <input class="form-control form-control-lg" name="ami_code" type="text" placeholder="Code ami" aria-label=".form-control-lg example">
          <button type="submit" name="submit" class="btn btn-dark">Envoyer</button>
      </form>
      </div>
      <?php
try 
{
  $bdd = new PDO('mysql:host=localhost;dbname=discodo;charset=utf8', 'root', '');
} 
catch(Exception $e) 
{
      die('Erreur : '. $e->getMessage());
}
if(isset($_POST['submit']))
{
  $ami_code = htmlspecialchars($_POST['ami_code']);
  $_SESSION['ami_code'] = $ami_code; // Stockez la valeur de $ami_code dans une variable de session
  $stmt = $bdd->prepare('SELECT * FROM users WHERE code_ami = :ami_code');
  $stmt->bindParam(':ami_code', $ami_code);
  $stmt->execute();
  if($stmt->rowCount() > 0)
  {
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      echo "<p style='color: white;'>Ce code d'ami est à : {$result['pseudo']}.</p>";
      echo '<form method="post">
    <button type="submit" name="add" class="btn btn-dark">Ajouter</button>
</form>';
  }
  else
  {
    echo '<p style="color: white;">Personne n\'a ce code ami.</p>';
  }
}

if(isset($_POST['add']))
{
  $ami_code = $_SESSION['ami_code']; // Récupérez la valeur de $ami_code à partir de la variable de session
  if (!empty($ami_code)) {
    
    // Récupérez la liste actuelle des amis
    $stmt1 = $bdd->prepare('SELECT amis FROM users WHERE pseudo = :pseudo');
    $stmt1->bindParam(':pseudo', $_SESSION["pseudo"]);
    $stmt1->execute();
    $row = $stmt1->fetch(PDO::FETCH_ASSOC);
    $amis = explode(',', $row['amis']); // Convertir la chaîne en tableau

    // Ajoutez le nouveau ami à la liste
    $amis[] = $ami_code;

    // Convertissez le tableau en chaîne et mettez à jour la base de données
    $amis_str = implode(',', $amis);
    $stmt2 = $bdd->prepare('UPDATE users SET amis = :amis WHERE pseudo = :pseudo');
    $stmt2->bindParam(':amis', $amis_str);
    $stmt2->bindParam(':pseudo', $_SESSION["pseudo"]);
    if($stmt2->execute())
    {
      echo '<p style="color: white;">Ajouté avec succès.</p>';
    }
    else
    {
      echo '<p style="color: red;">Erreur lors de l\'ajout : ' . $stmt2->errorInfo()[2] . '</p>';
    }
  } else {
    echo '<p style="color: red;">Le code ami est vide.</p>';
  }
}
?>



</body>
</html>