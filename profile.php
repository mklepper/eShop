<?php
    require_once("inc/header.php");

    $page = "My Profile";

    if(!userConnect())
    {
        header('location:login.php');
        exit();
    }

?>

    <h1><?= $page ?></h1>
          

        <div class="card" style="width: 18rem;" id="product-eshop">
                        <img class="card-img-top rounded" src="">
                        <div class="card-body">
                            <h5 class="card-title"><?= $_SESSION['user']['pseudo'] ?></h5>
                            <p>Info:</p>
                            <li>Firstname: <?= $_SESSION['user']['firstname'] ?></li>
                            <li>Lastname: <?= $_SESSION['user']['lastname'] ?></li>
                            <li>Address: <?= $_SESSION['user']['address'] ?></li>
                            <li>Zip Code: <?= $_SESSION['user']['zip_code'] ?></li>
                            <li>City: <?= $_SESSION['user']['city'] ?></li>
                            <a href="product_page.php?id=<?= $product['id_product'] ?>" class="btn btn-primary">Update your profile</a>
                        </div>
                    </div>
    
<?php
    require_once("inc/footer.php");
?>