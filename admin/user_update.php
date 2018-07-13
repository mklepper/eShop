<?php
    require_once('inc/header_user.php');
if ($_POST) {
    foreach ($_POST as $key => $value) {
        $_POST[$key] = addslashes($value);
    }

        // debug($_POST);
        // debug($_FILES);

    if (empty($msg_error)) {

        if (!empty($_POST['id_product'])) // we register the update
        {
            $result = $pdo->prepare("UPDATE product SET reference=:reference, category=:category, title=:title, description=:description, color=:color, size=:size, gender=:gender, picture=:picture, picture2=NULL, price=:price, stock=:stock WHERE id_product = :id_product");

            $result->bindValue(':id_product', $_POST['id_product'], PDO::PARAM_INT);
        } else // we register for the first time in the DTB
        {
            $result = $pdo->prepare("INSERT INTO product (reference, category, title, description, color, size, gender, picture, picture2, price, stock) VALUES (:reference, :category, :title, :description, :color, :size, :gender, :picture, NULL, :price, :stock)");
        }

        $result->bindValue(':id_user', $_POST['id_user'], PDO::PARAM_INT);
        $result->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
        $result->bindValue(':pwd', $_POST['pwd'], PDO::PARAM_STR);
        $result->bindValue(':firstname', $_POST['lastname'], PDO::PARAM_STR);
        $result->bindValue(':email', $_POST['email'], PDO::PARAM_STR);


        if ($result->execute()) // if the request was inserted ine the DTB
        {
            if (!empty($_FILES['product_picture']['name'])) { //---------------------------------------------------------------
                copy($_FILES['product_picture']['tmp_name'], $picture_path);
            }

            if (!empty($_POST['id_user'])) {
                header('location:user_list.php?m=update');
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
        $update_product = $result->fetch();
    }
}

$id_user = (isset($update_user)) ? $update_user['id_user'] : '';
$pseudo = (isset($update_user)) ? $update_user['pseudo'] : '';
$pwd = (isset($update_user)) ? $update_user['pwd'] : '';
$firstname = (isset($update_user)) ? $update_user['firstname'] : '';
$lastname = (isset($update_user)) ? $update_user['lastname'] : '';
$email = (isset($update_user)) ? $update_user['email'] : '';


$action = (isset($update_product)) ? "Update" : 'Add';

?>
<h1 class="h2"><?= $action ?> a product</h1>

    <form action="" method="post" enctype="multipart/form-data">
        <?= $msg_error ?>
        <input type='hidden' name="id_product" value="<?= $id_product ?>">
        <div class="form-group">
            <input type="text" class="form-control" name="id_user" placeholder="Reference of the product..." value="<?= $id_user ?>">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="pseudo" placeholder="Category of the product..." value="<?= $pseudo ?>">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="pwd" placeholder="Title of the product..."  value="<?= $pwd ?>">
        </div>
        <div class="form-group">
            <textarea class="form-control" name="description" cols="30" rows="5" placeholder="Description of the product..."><?= $description ?></textarea>
        </div>
       
        <div class="form-group">
            <select class="form-control" name="gender">
                <option disabled selected>Public of the product...</option>
                <option value="m" <?php if($gender == 'm'){ echo 'selected';} ?>>Men</option>
                <option value="f" <?php if($gender == 'f'){ echo 'selected';} ?>>Women</option>
                <option value="u" <?php if($gender == 'u'){ echo 'selected';} ?>>Undefined</option>
            </select>
        </div>
        <div class="form-group">
            <label for="product_picture">Product picture</label>
            <input type="file" class="form-control-file" id="product_picture" name="product_picture">
            <?php
                if(isset($update_product))
                {
                    echo "<input name='actual_picture' value='$picture' type='hidden'>";
                    echo "<img style='width:25%;' src='" . URL . "uploads/img/$picture'>";
                }
            ?>
        </div>
        <div class="form-group">
            <label for="product_picture2">Second product picture (optional)</label>
            <input type="file" class="form-control-file" id="product_picture2" name="product_picture2">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="price" placeholder="Price of the product..."  value="<?= $price ?>">
        </div>
        <div class="form-group">
            <input type="number"  class="form-control" name="stock" placeholder="Stock of the product..."  value="<?= $stock ?>">
        </div>
        <input type="submit" value="<?= $action ?> the product" class="btn btn-info btn-lg btn-block">
    </form>

<?php
    require_once('inc/footer_user.php');
?>