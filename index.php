<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Friends</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>


<?php
$pdo =connexion();
$myFirstName="";$myName="";

if (isset($_POST['firstName']) && isset($_POST['Name'])){
    $data=array_map("trim", $_POST);
    $errors=[];

    if(empty($data["firstName"])) {
        $errors=["Le prénom est obligatoire"];
    }

    if(strlen($data["firstName"])>45) {
        $errors=["Prénom trop long"];
    }

    if(strlen($data["Name"])>45) {
        $errors=["Nom trop long"];
    }
    
    
    if(empty($data["Name"])) {
        $errors=["Le nom est obligatoire"];
    }
    foreach ($errors as $getErrors) {
        echo "<br/>".$getErrors."<br/>";
    }

    //mémoriser les données saisies pour ne pas avoir à la resaisir sur reload page en cas d'erreurs
    $myFirstName = $data["firstName"];
    $myName = $data["Name"];


    //enregistrer les données dans la base
    insertData ($myFirstName,$myName, $pdo);
}

?>

<div>
<h1>Gestion de ma liste d'amis<h1></div>
<div>
<table name ="friendTable" id= "firendTable"  width="50%">
<tr><th>prénom</th><th>Nom</th></tr>

<?php
//parcourir les amis enregistrés dans la base de données et afficher leurs noms et prénoms dans un tableau
$friends=getFriends($pdo);
foreach($friends as $myFriend) {

    $firstName = $myFriend[1] ;
    $lastName = $myFriend[2] ;
?>
<?php
echo "<tr><td>".$firstName."</td><td>".$lastName."</td></tr>";
?>
<?php
}
?>
</table>
<br>
<form mame="addFriend" id="addFriend" action ="" method= "POST">
<label for="firstName">Prénom</label>
<input type="text" name="firstName"  value="<?=$myFirstName;?>" required />
<label for="Name">Nom</label>
<input type="text" name="Name" value="<?=$myName;?>" required/>
<input type="submit"  value = "Enregistrer" name="validation"/>
</form>
</div>


<?php

function connexion() {
    //connexion bd
    require_once "_connect.php";
    $pdo= new \PDO(DSN,USER,PASS);
    return $pdo;
}


function getFriends($pdo):array {
    //récup données amis
    $query = "select* from friend";
    $statement = $pdo->query($query);
    $friends = $statement->fetchAll(); 
    return $friends;
}

function insertData(string $firstName, string $lastName, $pdo) {
    $query = "INSERT INTO friend (firstname, lastname) VALUES (:firstname, :lastname)";
    $statement = $pdo->prepare($query);
    $statement->bindValue(':lastname', $lastName, \PDO::PARAM_STR);
    $statement->bindValue(':firstname', $firstName, \PDO::PARAM_STR);
    $statement->execute();
}
?>

</body>
</html>
