<?php
    require_once("inc/header.php");

    $page = "My Profile";

    if(!userConnect())
    {
        header('location:login.php');
        exit();
    }
//------------------Delete--------------------------------------------------------------
    if(isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id']))
    {
        $req = "SELECT * FROM user WHERE id_user = :id_user";

        $result = $pdo->prepare($req);

        $result->bindValue(':id_user', $_GET['id'], PDO::PARAM_INT);

        $result->execute();

        if($result->rowCount() == 1)
        {
            $user = $result->fetch();

            $delete_req = "DELETE FROM user WHERE id_user = $user[id_user]";

            $delete = $pdo->exec($delete_req);

            if ($delete) {
               header("location:".URL.'logout.php');
            }
         } 
         else
            {
                echo "b;a;a";
                //header('location:signup.php');
            }
        }
//--------------------------------------------------------------------------------------
?>

    <h1><?= $page ?></h1>
          

        <div class="card" style="width: 20rem;padding: 5px; margin: auto;" id="product-eshop">
                        <img class="card-img-top rounded" src="">
                        <div class="card-body">
                            <img class="card-img-top rounded" src="<?= URL ?>uploads/user_img/<?= $_SESSION['user']['picture'] ?>">
                            <h5 class="card-title"><?= $_SESSION['user']['pseudo'] ?></h5>
                            <p>Info:</p>
                            <li style="list-style-type: none;">Firstname: <?= $_SESSION['user']['firstname'] ?></li>
                            <li style="list-style-type: none;">Lastname: <?= $_SESSION['user']['lastname'] ?></li>
                            <li style="list-style-type: none;">Address: <?= $_SESSION['user']['address'] ?></li>
                            <li style="list-style-type: none;">Zip Code: <?= $_SESSION['user']['zip_code'] ?></li>
                            <li style="list-style-type: none;">City: <?= $_SESSION['user']['city'] ?></li>
                            <a href="update.php?id=<?=$_SESSION['user']['id_user']?>" class="btn btn-success" style="background-color: cadetblue;">Update your profile</a>
                            <a href="?id=<?=$_SESSION['user']['id_user']?>" class="btn btn-danger">Delete your profile</a>
                        </div>
                    </div>
    
<?php
    require_once("inc/footer.php");
?>