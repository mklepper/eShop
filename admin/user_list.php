<?php
    require_once('inc/header_user.php');
    
    //---------------------------------User List---------------------------------------------------------------------------------------------------------
    $result = $pdo->query("SELECT * FROM user");
    $users = $result->fetchAll();

    $content .= "<table class='table table-striped'>";
    $content .= "<thead class='thead-dark'><tr>";

    for ($i = 0; $i < $result->columnCount(); $i++) {
        $columns = $result->getColumnMeta($i); /// password in the table head cannot be shown --> neesd to be solved still
        $content .= "<th scope='col'>" . ucfirst(str_replace('_', ' ', $columns['name'])) . "</th>";
    }
    $content .= '<th colspan="2">Actions</th>';
    $content .= "</tr></thead><tbody>";
    foreach ($users as $user) {
        $content .= "<tr>";
        foreach ($user as $key => $value) {
            if($key =='pwd'){
                
            }elseif ($key == 'picture') {
                $content .= '<td><img height="100" src="' . URL . 'uploads/img/' . $value . '" alt="' . $product['title'] . '"/></td>';
            } else {
                $content .= "<td>" . $value . "</td>";
            }
        }

        //---------------------------------Update and Delete BTN----------------------------------------------------------------
        $content .= "<td><a href='" . URL . "admin/user_update.php?id=" . $user['id_user'] . "'><i class='fas fa-pen'></i></a></td>";
        $content .=
        "<td>
            <a href='#exampleModal' id='delete' data-toggle='modal' data-target='#exampleModal'><i class='fas fa-trash-alt'></i></a>
                <div id='myModal' class='modal'>
                    <div class='modal-content'>
                        <span class='close'>&times;</span>
                        <a href='?id=" . $user['id_user'] . "'><button type='button' class='btn btn-success'>Yes</button></a>
                        <button type='button' class='btn btn-danger' id='no'>No</button>
                    </div>
                </div>
        </td>";
        $content .= "</tr>";
    }
    $content .= "</tbody></table>";
    //----------------------------------------------------------------------------------------------------------------------
    //-----------------------------------------------------------------------------------------------------------------------------------------------------

    if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) {
        $req = "SELECT * FROM user WHERE id_user = :id_user";

        $result = $pdo->prepare($req);

        $result->bindValue(':id_user', $_GET['id'], PDO::PARAM_INT);

        $result->execute();

        if ($result->rowCount() == 1) {
            $user = $result->fetch();

            $delete_req = "DELETE FROM user WHERE id_user = $user[id_user]";

            $delete_result = $pdo->exec($delete_req);
        }
    }

?>

    <?= $content ?>




<?php

    require_once('inc/footer_user.php');

?>