<?php

    $page_title = 'Профіль';
    include 'tml/top.php';
    
    if(!isset($_SESSION['logged-user']) ||
       $_SESSION['logged-user']->login == 'root' ||
       $_GET['id'] == '' ||
       $_GET['id'] == 0)
            echo '<script>window.location.href = "login";</script>';
        
?>

<?php include 'tml/bottom.php'; ?>