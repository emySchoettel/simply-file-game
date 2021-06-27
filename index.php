<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h1>Simply file game</h1>
        <?php 
           $path = "fichier_json.json";
           $data = [ 
               "name" => "test",
               "calls" => []
           ];

           $jsonencode = json_encode($data);
           $fichier = fopen($path, "w");
           fputs($fichier, $jsonencode);
           fclose($fichier);

           //ouvrir le fichier créé 
           $read = fopen($path, "r");
           $contenu = fread($read, filesize($path));
           fclose($read);
           var_dump($contenu);

        ?>
    </body>
</html>

<?php

?>

