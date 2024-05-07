<?php
session_start();
if(empty($_SESSION["pseudo"])) {
    // Rediriger vers inscription.php si la session n'est pas active
    header('Location: ./inscription.php');
    exit(); // Assurez-vous de terminer le script après la redirection
}

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

// Sélectionner les amis de l'utilisateur
$stmt = $bdd->prepare("SELECT amis FROM `users` WHERE pseudo = :pseudo");
$stmt->bindParam(':pseudo', $_SESSION["pseudo"]);
$stmt->execute();

$amis = array();
while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $amis[] = array_map('intval', explode(',', $result['amis']));
}

//Faire la deconnection

if(isset($_POST['deco']))
{
    session_destroy();
    header('Location: ./index.php');
    exit();
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Discodo | Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/style.css">
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
                        <li _msthidden="1"><a class="dropdown-item" href="./inscription.php" _msttexthash="76466" _msthidden="1" _msthash="215">Créer un compte</a></li>
                        <li _msthidden="1"><a class="dropdown-item" href="./connection.php" _msttexthash="232752" _msthidden="1" _msthash="216">Connexion</a></li>
                        <li _msthidden="1"><a class="dropdown-item" href="#" _msttexthash="349791" _msthidden="1" _msthash="217">Votre compte : <?=$_SESSION['pseudo']?></a></li>
                        <li _msthidden="1"><a class="dropdown-item" href="#" _msttexthash="349791" _msthidden="1" _msthash="217">Votre code d'ami : <?=$_SESSION['ami_code']?></a></li>
                        <form action="" method="post">
                        <li _msthidden="1"><a class="dropdown-item" href="#" _msttexthash="349791" _msthidden="1" _msthash="217"><button type="submit" name="deco" class="btn btn-outline-danger">Deconnecter votre compte</button></a></li>
                        </form>
                        <li _msthidden="1"><a class="dropdown-item" href="./clear.php" _msttexthash="349791" _msthidden="1" _msthash="217"><button type="submit" class="btn btn-outline-danger">Supprimer votre compte</button></a></li>
                    </ul>
                </ul>
            </li>
        </ul>
    </div>
</div>
</nav>
<div class="contacts">
    <?php
    // Pour chaque ami, récupérer les détails et afficher un lien vers msg.php
    foreach($amis as $ami) {
        foreach($ami as $a) {
            $stmt2 = $bdd->prepare("SELECT pseudo, id FROM `users` WHERE id = :id");
            $stmt2->bindParam(':id', $a);
            $stmt2->execute();
            $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
            if ($result2 && array_key_exists('pseudo', $result2)) {
                $id = $result2['id'];
                $pseudo = $result2['pseudo'];
                // Afficher le lien avec l'ID comme paramètre dans l'URL
                echo '<p><a href="msg.php?id=' . $id . '" style="color:white; text-decoration:none;">'. $pseudo . '</a></p>';
            }
        }
    }
    $recupami = $bdd->prepare("SELECT * FROM `users` WHERE FIND_IN_SET(:id, amis)");
    $recupami->bindParam(':id', $_SESSION["id"]);
    $recupami->execute();
    
    if ($recupami->rowCount() > 0) {
        while ($result = $recupami->fetch(PDO::FETCH_ASSOC)) {
            echo '<p><a style="color:white; text-decoration:none;" href="msg.php?id=' . $result["id"] . '">' . $result["pseudo"] . '</a></p>';
        }
    } else {
        // Gérer le cas où aucun résultat n'est retourné
        $_SESSION["id"] = $_SESSION["id"];
    }
    

  
    ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
