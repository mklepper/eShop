<?php
require_once("inc/header.php");

$page = "Update";

if ($_POST) {
    // debug($_POST);
    
    // check pseudo
    if (!empty($_POST['pseudo'])) {
        $pseudo_verif = preg_match('#^[a-zA-Z0-9-._]{3,20}$#', $_POST['pseudo']); // function preg_match() allows me to check what info will be be allowed in a result. It takes 2 arguments: REGEX (Regular Expressions) + the result to check. At the end, I will have a TRUE or FALSE condition

        if (!$pseudo_verif) {
            $msg_error .= "<div class='alert alert-danger'>Your pseudo should countain letters (upper/lower), numbers, between 3 and 20 characters and only '.' and '_' are accepted. Please try again !</div>";
        }
    }
    else 
    {
    $msg_error .= "<div class='alert alert-danger'>Please enter a valid pseudo.</div>";
    }

    // check email
    if (!empty($_POST['email'])) {
        $email_verif = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL); // function filter_var() allows us to check a result (STR -> email, URL ...). It takes 2 arguments: the result to check + the method. It returns a BOOLEAN

        $forbidden_mails = [
            'mailinator.com',
            'yopmail.com',
            'mail.com'
        ];

        $email_domain = explode('@', $_POST['email']); // function explode() allow me to explode a result into 2 parts regarding the element I've chosen

       // debug($email_domain);

        if (!$email_verif || in_array($email_domain[1], $forbidden_mails))
        {
            $msg_error .= "<div class='alert alert-danger'>Please enter a valid email.</div>";
        }

    } else 
    {
        $msg_error .= "<div class='alert alert-danger'>Please enter a valid email.</div>";
    }

    if (!isset($_POST['gender']) || ($_POST['gender'] != "m" && $_POST['gender'] != "f" && $_POST['gender'] != "o")) 
    {
        $msg_error .= "<div class='alert alert-danger'>Choose a valid gender.</div>";
    }
//----------------------------------Picture----------------------------------------------------------------------------------------------------------------
    if (!empty($_FILES['user_picture']['name']))
    {
            // I give a random name for my picture.
        $picture_name = $_POST['pseudo'] . '_' . $_POST['gender'] . '_' . time() . '-' . rand(1, 999) . '_' . $_FILES['user_picture']['name'];

        $picture_name = str_replace(' ', '-', $picture_name);

            // we register the path of my file
        $picture_path = ROOT_TREE . 'uploads/user_img/' . $picture_name;

        $max_size = 2000000;

        if ($_FILES['user_picture']['size'] > $max_size || empty($_FILES['user_picture']['size'])) {
            $msg_error .= "<div class='alert alert-danger'>Please select a 2Mo file maximum !</div>";
        }

        $type_picture = ['image/jpeg', 'image/png', 'image/gif'];

        if (!in_array($_FILES['user_picture']['type'], $type_picture) || empty($_FILES['user_picture']['type'])) {
            $msg_error .= "<div class='alert alert-danger'>Please select a JPEG/JPG, a PNG or a GIF file.</div>";
        }

    } elseif (!empty($_POST['actual_picture'])) // if I update a product, I target the new input created with my $update_product
    {
        $picture_name = $_POST['actual_picture'];
    } else {
        $picture_name = 'default.jpg';
    }
//----------------------------------------------------------------------------------------------------------------------------------------------------------
    // OTHER CHECKS POSSIBLE HERE


    if (empty($msg_error)) {
        $result = $pdo->prepare("SELECT pseudo FROM user WHERE pseudo = :pseudo AND pseudo != :pseudo");

        $result->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);

        $result->execute();

        if ($result->rowCount() == 1) 
        {
            $msg_error .= "<div class='alert alert-secondary'>The pseudo $_POST[pseudo] is already taken, please choose another one.</div>";
                 
            }

        if (!empty($_GET['id'])) // we register the update
        {
            $result = $pdo->prepare("UPDATE user SET pseudo=:pseudo,  pwd=:password, firstname=:firstname,lastname=:lastname, email=:email, address=:address, zip_code=:zc, city=:city, gender=:gender, picture=:picture WHERE id_user = :id_user");

            $result->bindValue(':id_user', $_GET['id'], PDO::PARAM_INT);
        } else // we register for the first time in the DTB
        {
            $result = $pdo->prepare("INSERT INTO user (pseudo, pwd, firstname, lastname, email, address, zip_code, city, gender, picture) VALUES (:pseudo, :pwd, :fisrtname, :lastname, :email, :address, :zc, :city, :gender, :picture)");
        }

        
        $result->bindValue(':password', $_POST['password'], PDO::PARAM_STR);
        $result->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
        $result->bindValue(':firstname', $_POST['firstname'], PDO::PARAM_STR);
        $result->bindValue(':lastname', $_POST['lastname'], PDO::PARAM_STR);
        $result->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
        $result->bindValue(':address', $_POST['address'], PDO::PARAM_STR);
        $result->bindValue(':zc', $_POST['zc'], PDO::PARAM_STR);
        $result->bindValue(':gender', $_POST['gender'], PDO::PARAM_STR);
        $result->bindValue(':city', $_POST['city'], PDO::PARAM_STR);

        $result->bindValue(':picture', $picture_name, PDO::PARAM_STR);

        
        if ($result->execute()) 
        {
            if (!empty($_FILES['user_picture']['name'])) {
                copy($_FILES['user_picture']['tmp_name'], $picture_path);
            }

            if (!empty($_POST['id'])) {
                header('location:login.php?m=update');
            }
        }

    
    }

}
if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) {
    $req = "SELECT * FROM user WHERE id_user = :id_user";

    $result = $pdo->prepare($req);
    $result->bindValue(':id_user', $_GET['id'], PDO::PARAM_INT);
    $result->execute();

    if ($result->rowCount() == 1) {
        $update_user = $result->fetch();
    }
}

// Keep the values entered by the user if problem during the page reloading
$pseudo = (isset($update_user['pseudo'])) ? $update_user['pseudo'] : ''; // if we receive a POST, the variable will keep the value or if no POST, value = empty
$firstname = (isset($update_user['firstname'])) ? $update_user['firstname'] : '';
$lastname = (isset($update_user['lastname'])) ? $update_user['lastname'] : '';
$email = (isset($update_user['email'])) ? $update_user['email'] : '';
$address = (isset($update_user['address'])) ? $update_user['address'] : '';
$zip_code = (isset($update_user['zc'])) ? $update_user['zc'] : '';
$city = (isset($update_user['city'])) ? $update_user['city'] : '';
$gender = (isset($update_user['gender'])) ? $update_user['gender'] : '';
$pwd = (isset($update_user['pwd'])) ? $update_user['pwd'] : '';

$picture = (isset($update_user['picture'])) ? $update_user['picture'] : '';

$id_user = (isset($update_user)) ? $update_user['id_user'] : '';

$action = (isset($update_user)) ? "Update" : 'Add';

debug($_POST);
debug($_FILES);
debug($update_user);


?>

        <h1 style="color: cadetblue;"><?= $page ?></h1>
        
        <form action="" method="post" enctype="multipart/form-data"> <!--IMPORTANT! It did not change the picture because it missed! -->
            <small class="form-text text-muted">We will never use your datas for commercial use.</small>
            <?= $msg_error ?>
            <div class="form-group">
            <input type='hidden' name="pseudo" value="<?=$pseudo?>">
                <input type="text" class="form-control" name="pseudo" placeholder="Choose a pseudo..." value="<?= $pseudo ?>" required>
            </div>
            <div class="form-group">
            <input type='hidden' name="password" value="<?= $pwd?>">
            </div>

            <div class="form-group">
            
                <input type="text" class="form-control" name="firstname" placeholder="Your firstname..." value="<?= $firstname ?>">
            </div>
            <div class="form-group">
            
                <input type="text" class="form-control" name="lastname" placeholder="Your lastname..." value="<?= $lastname ?>">
            </div>
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="Your email..." value="<?= $email ?>">
            </div>
            <div class="form-group">
            
                <select name="gender" class="form-control">
                    <option value="m" <?php if ($gender == 'm') {
                                            echo 'selected';
                                        } ?>>Men</option>
                    <option value="f" <?php if ($gender == 'f') {
                                            echo 'selected';
                                        } ?>>Women</option>
                    <option value="o" <?php if ($gender == 'o') {
                                            echo 'selected';
                                        } ?>>Other</option>
                </select>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="address" placeholder="Address..." value="<?= $address ?>">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="zc" placeholder="Zip code..." value="<?= $zip_code ?>">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="city" placeholder="City..." value="<?= $city ?>">
            </div>

            <!-- Picture _________________________________________________________________________________________________________-->
            <div class="form-group">
                <label for="user_picture">User picture:</label>
                <input type="file" class="form-control-file" id="user_picture" name="user_picture">
                <?php
                    if (isset($update_user)) {
                        echo "<input name='actual_picture' value='$picture' type='hidden'>";
                        echo "<img style='width:25%;' src='" . URL . "uploads/user_img/$picture'>";
                    }
                ?>
            </div>
            <!--_________________________________________________________________________________________________________________ _-->         

            <input type="submit" value="<?= $page ?> Your account" style="background-color: cadetblue;" class="btn btn-success btn-lg btn-block">
        </form>
    
<?php
require_once("inc/footer.php");
?>