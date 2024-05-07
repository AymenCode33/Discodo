<?php
session_start();
$bdd_user = 'root';
$bdd_pass = '';
try 
{
    $bdd = new PDO('mysql:host=localhost;dbname=discodo;charset=utf8', $bdd_user, $bdd_pass);
} 
catch(PDOException $e) 
{
    die('Erreur : '. $e->getMessage());
}

// Vérifier si le champ de formulaire 'msg' est défini et n'est pas vide
if(isset($_POST["msg"]) && !empty($_POST["msg"])) {
  // Sécuriser le message avec htmlspecialchars
  $message = htmlspecialchars($_POST["msg"]);

  // Vérifier si $_SESSION["id"] est défini et n'est pas vide
  if(isset($_SESSION["id"]) && !empty($_SESSION["id"])) {
      // Préparer la requête SQL
      $mettreMSG = $bdd->prepare("INSERT INTO msg (msg, id_send, id_receveur) VALUES (:msg, :id_send, :id_receveur)");

      // Lier les paramètres et exécuter la requête
      $mettreMSG->bindParam(":msg", $message);
      $mettreMSG->bindParam(':id_send', $_SESSION["id"]);
      $mettreMSG->bindParam(':id_receveur', $_GET["id"]);
      $mettreMSG->execute();
  } else {
      // Gérer le cas où $_SESSION["id"] n'est pas défini ou vide
      echo "Erreur : Identifiant de l'expéditeur non défini.";
  }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/style.css">
    <title>Discodo | Messages</title>
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
                <li _msthidden="1"><a class="dropdown-item" href="./inscription.php" _msttexthash="76466" _msthidden="1" _msthash="215">Crée un compte</a></li>
                <li _msthidden="1"><a class="dropdown-item" href="./connection.php" _msttexthash="232752" _msthidden="1" _msthash="216">Connection</a></li>
                <li _msthidden="1"><a class="dropdown-item" href="#" _msttexthash="349791" _msthidden="1" _msthash="217">Votre compte : <?=$_SESSION['pseudo']?></a></li>
                <li _msthidden="1"><a class="dropdown-item" href="./clear.php" _msttexthash="349791" _msthidden="1" _msthash="217"><button type="submit" class="btn btn-outline-danger">Supprimer votre compte</button></a></li>
              </ul>
            </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>
<div class="sendmsg">
    <form method="post" class="d-flex">
        <textarea name="msg" id="msg"cols="30" rows="5"></textarea>
        <button type="submit" name="send" class="btn btn-dark">Envoyer le message</button>
    </form>
</div>
<div class="msg">
<?php
    // Assurez-vous que $_GET["id"] est défini et est une valeur valide
    if(isset($_GET["id"]) && !empty($_GET["id"])) {
        $id_receveur = $_GET["id"];
        
        // Requête pour récupérer les messages envoyés par l'utilisateur connecté à l'utilisateur spécifié dans l'URL
        $recupMSG_envoyes = $bdd->prepare("SELECT * FROM `msg` WHERE id_send = :id_me AND id_receveur = :id_receveur");
        $recupMSG_envoyes->bindParam(':id_me', $_SESSION["id"]);
        $recupMSG_envoyes->bindParam(':id_receveur', $id_receveur);
        $recupMSG_envoyes->execute();
        $resultMSG_envoyes = $recupMSG_envoyes->fetchAll(PDO::FETCH_ASSOC);
        
        // Affichage des messages
        foreach($resultMSG_envoyes as $message_me) {
            echo '<p style="color: white;"> ' . $message_me["msg"] . ' </p>';
        }

        $recupMSG_envoyes = $bdd->prepare("SELECT * FROM `msg` WHERE id_send = :id_me AND id_receveur = :id_receveur");
        $recupMSG_envoyes->bindParam(':id_me', $id_receveur);
        $recupMSG_envoyes->bindParam(':id_receveur', $_SESSION["id"]);
        $recupMSG_envoyes->execute();
        $resultMSG_envoyes = $recupMSG_envoyes->fetchAll(PDO::FETCH_ASSOC);
        
        // Affichage des messages
        foreach($resultMSG_envoyes as $message_me) {
            echo '<p style="color: blue;"> ' . $message_me["msg"] . ' </p>';
        }
    }
    
?>
</div>
</body>
</html>
