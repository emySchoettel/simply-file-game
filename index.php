<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h1>Simply file game</h1>
        <?php 
            echo "<h2>Debut Etape 1</h2>";
        
            etape1();
        
            echo "<h2>Fin Etape 1</h2>";
        ?>
        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
            <label>Username : </label> 
            <input type="text" name="username"/>
            <label>Scores : </label>
            <input type="text" name="scores"/>
            <input type="submit" value="POST"/>
        </form>

        <form action="<?php $_PHP_SELF ?>" method="GET">
            <input type="submit" value="GET"/>
        </form>

    </body>
</html>
<?php 
    echo "<h2>Debut Etape 2</h2>";

    $highscore_file_path  = "./hightscores.json";

    // if(!isset($POST['action']))
    // {
    //     die; 
    // }

    $fichier = check_highscore_file($highscore_file_path);

    switch($_SERVER['REQUEST_METHOD'])
    {
        case 'POST': 
            $file = openThisFile($highscore_file_path);

            $dataArray = array();
            foreach ($file->scores as $key) {
               array_push($dataArray, $key);
            }
            array_push($dataArray, intval($_POST['scores']));

            sort($dataArray, SORT_NUMERIC);

            $data = [
                "gameId" => $file->gameId,
                "username" => $_POST['username'],
                "scores" => $dataArray
            ];
        
            $jsonencode = json_encode($data);
            writeInThisFile($highscore_file_path, $jsonencode);

            break; 
        case 'GET':
            $file = fopen($highscore_file_path, "r");
            $contenu = fread($file, filesize($highscore_file_path));
            $jsondecode = json_decode($contenu);
            foreach ($jsondecode as $key => $value) {
                if($key != 'scores')
                    echo $key . " : " . $value . "<br>";
            }
            echo "Meilleurs scores : <br>";
            foreach ($jsondecode->scores as $value) {
                echo $value . '<br>';
            }
            break; 
    }


    //Etape 2

    function check_highscore_file($path)
    {
        if(!file_exists($path))
        {
            $data = [
                "gameId" => "my-hame-id",
                "scores" => []
            ];

            $json_encode = json_encode($data);

            writeInThisFile($path, $json_encode);
        }
        return openThisFile($path);
    }


    //Etape 1

    function etape1()
    {
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
    }

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

