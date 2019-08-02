<?php

    error_reporting(E_ALL);
    ini_set('display_errors',1);

    include('dbcon.php');

    $android = strpos($_SERVER['HTTP_USER_AGENT'], "Android");

    if( (($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['submit']))|| $android)
    {

        $name=$_POST['name'];
        $title=$_POST['title'];
        $profile=$_POST['profile'];
        $description=$_POST['description'];

        if(empty($title)){
            $errMSG = "제목을 입력하세요.";
        }

        if(!isset($errMSG))
        {
            try{
                $stmt = $con->prepare('INSERT INTO topic(title, description, created, name, profile)
                  VALUES(:title, :description, NOW(), :name, :profile)');
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':title', $title);
                $stmt->bindParam(':profile', $profile);
                $stmt->bindParam(':description', $description);

                if($stmt->execute())
                {
                    $successMSG = "새로운 사용자를 추가했습니다.";
                }
                else
                {
                    $errMSG = "사용자 추가 에러";
                }

            } catch(PDOException $e) {
                die("Database error: " . $e->getMessage());
            }
        }

    }
?>

<?php
    if (isset($errMSG)) echo $errMSG;
    if (isset($successMSG)) echo $successMSG;

	     $android = strpos($_SERVER['HTTP_USER_AGENT'], "Android");

    if( !$android )
    {
?>

    <html>
       <body>
            <?php
            if (isset($errMSG)) echo $errMSG;
            if (isset($successMSG)) echo $successMSG;
            ?>

            <form action="<?php $_PHP_SELF ?>" method="POST">
                Name: <input type = "text" name = "name" />
                Title: <input type = "text" name = "title" />
                Profile: <input type = "text" name = "profile" />
                Description: <input type = "text" name = "description" />
                <input type = "submit" name = "submit" />
            </form>

       </body>
    </html>
  <?php
      }
  ?>
