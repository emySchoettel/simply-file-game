<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h1>Simply file game</h1>
    </body>
</html>
<?php 

    date_default_timezone_set('UTC');

    $path = "fichier_json.json";
    $data = [ 
        "name" => "test",
        "calls" => []
    ];
    $jsonencode = json_encode($data);

    //ecrire dans le fichier
    writeInThisFile($path, $jsonencode);

    //ouvrir le fichier créé 
    $file = openThisFile($path);
    var_dump($file);

    //ajouter la date du jour dans calls 
    $date = date("l d F Y");
    array_push($data["calls"], $date);
    $jsonencode = json_encode($data);
    var_dump($jsonencode);

    writeInThisFile($path, $jsonencode);

    $file = openThisFile("./fichier_json.json");
    var_dump($file);

    function openThisFile($nom)
    {
        $read = fopen($nom, "r");
        $contenu = fread($read, filesize($nom));
        fclose($read);
    
        $jsondecode = json_decode($contenu);
        return $jsondecode;
    }

    function writeInThisFile($nom, $data)
    {
        $write = fopen($nom, "w");
        fputs($write, $data);
        fclose($write);
    }
?>

