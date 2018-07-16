<?php
require_once("inc/header.php");

$page = "Change your Password";
if ($_POST) 
{
    if (isset($_POST['pseudo']) && isset($_POST['email'])) 
    {

        $req = "SELECT * FROM user WHERE pseudo = :pseudo AND email=:email ";

        $result1 = $pdo->prepare($req);
        $result1->bindValue(":pseudo", $_POST['pseudo'], PDO::PARAM_STR);
        $result1->bindValue(":email", $_POST['email'], PDO::PARAM_STR);

        if ($result1->execute()) 
        {
            $user = $result1->fetch();
            debug($user);

            $result = $pdo->prepare("UPDATE user SET pseudo=:pseudo,  pwd=:pwd, email=:email, address=:address, zip_code=:zc, city=:city, gender=:gender WHERE id_user = $user[id_user]");

            $hashed_pwd = password_hash($_POST['pwd'], PASSWORD_BCRYPT);

            $result->bindValue(':pwd', $hashed_pwd, PDO::PARAM_STR);
            $result->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
            $result->bindValue(':email', $_POST['email'], PDO::PARAM_STR);

            if ($result->execute()) {
            echo 'Password changed with sucess';
             //header('location:login.php');
            }
            }else{
                echo 'Something got wrong....';

        }
    }
}

debug($_POST);

?>
<h1><?= $page ?></h1>
<?= $msg_error ?>
<form method="post">

    <div class="form-group">
        <input type="text" class="form-control" name= "pseudo" placeholder = "your pseudo...">
    </div>

    <div class="form-group">
        <input type="text" class="form-control" name = "email" placeholder= "your email..." >
    </div>

    <div class="form-group">
        <input type="text" class="form-control" name = "pwd" placeholder= "your password..." >
    </div>

    <input type="submit" value="Submit" class="btn btn-success btn-lg btn-block">


</form>

<?php
require_once("inc/footer.php");
?>