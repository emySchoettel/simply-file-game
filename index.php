<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <?php header('Content-Type: application/json; charset=utf-8'); ?>
    </head>
    <body>
        <h1>Simply file game</h1>
        <?php 
            echo "<h2>Debut Etape 1</h2>";
        
            etape1();
        
            echo "<h2>Fin Etape 1</h2>";

            echo "<h2>Debut Etape 2</h2>";
        ?>
        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" name="highscores">
            <label>Username : </label> 
            <input type="text" name="username"/>
            <label>Scores : </label>
            <input type="text" name="scores"/>
            <input type="submit" value="envoyer highscores"/>
        </form>

        <form action="<?php $_PHP_SELF ?>" method="GET" name="highscores">
            <input type="submit" value="get highscores"/>
        </form>

        <?php 
            etape2(); 

            echo "<h2>Fin étape 2</h2>";

            echo "<h2>Debut etape 3</h2>";
        ?>

        <form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>" name="set_comments">
            <label>Commentaire : </label>
            <textarea placeholder="Votre commentaire ici" name="comments"> </textarea>
            <input type="submit" value="Envoyer">
        </form>


        <form method="GET" action="<?php echo $_SERVER['PHP_SELF'];?>" name="get_comments">
            <input type="submit" value="Get comments"/>
        </form>

        <?php 

            etape3();

            etape4(); 
        ?>


    </body>
</html>
<?php 


function success($data)
{
    echo json_encode(
        [
            "success" => true, 
            "data" => $data
        ]
    );
    die; 
}

function error($code, $message)
{
    echo json_encode(
        [
            "success" => false, 
            "error" => [
                "code" => $code, 
                "message" => $message
            ]
        ]
    );
    die;
}

function etape4()
{
    $config_file_path = "./config.json";

    check_file($config_file_path, 1);
    error(1, "SFG n'est pas installé");

}

function etape3()
{
    $comments_file_path = "./comments.json";

    check_file($comments_file_path, true);

    switch($_SERVER['REQUEST_METHOD'])
    {
        case "POST": 
            if(array_key_exists("comments", $_POST))
            {
                $commentaire = $_POST['comments'] ? $_POST['comments'] : ""; 
                $file = openThisFile($comments_file_path);

                $dataArray = array();
                foreach ($file->comments as $key) {
                   array_push($dataArray, $key);
                }
                array_push($dataArray, $commentaire);
    
                sort($dataArray, SORT_NUMERIC);
    
                $data = [
                    "gameId" => $file->gameId,
                    "comments" => $dataArray
                ];
            
                $jsonencode = json_encode($data);
                writeInThisFile($comments_file_path, $jsonencode);
                error(3, "le fichier commentaire est corrumpu");
            }
            break; 

        case "GET":
            //var_dump(openThisFile($comments_file_path));
            echo_file($comments_file_path);
            break; 
    }
}

function check_file($file_path, $intAction)
{
    $data = []; 
    switch($intAction)
    {
        case 0: 
            $data = [
                "game-id" => "my-game-id",
                "comments" => []
            ];
            break; 
    }
    if(!file_exists($file_path))
    {
        $contenu = json_encode($data);
        writeInThisFile($file_path, $contenu);
    }
}

function echo_file($file_path)
{
    $file = openThisFile($file_path);
    foreach ($file as $key => $value) {
        if($key != "comments")
        {
            echo $key . " : " . $value . "<br>";
        }
        else
        {
            foreach ($value as $keyComments) {
                echo "Commentaire : " . $keyComments . "<br>";
            }
        }
    }
}


function set_file_from_object($file_path, $data)
{
    $contenu = json_encode($data);
    writeInThisFile($file_path, $contenu);
}

function etape2()
{
    //Etape 2

    $highscore_file_path  = "./hightscores.json";

    $fichier = check_highscore_file($highscore_file_path);
    if(!isset($fichier))
    {
        error(2, "lefichier highscores est corrompu");
    }

    switch($_SERVER['REQUEST_METHOD'])
    {
        case 'POST':
            if(array_key_exists('username', $_POST) || array_key_exists('scores', $_POST))
            {
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
                
            }
    
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
}

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

