<?php

    $page_title = 'Профіль';
    include 'tml/top.php';
    
    if(!isset($_SESSION['logged-user']) ||
       $_SESSION['logged-user']->login == 'root')
            echo '<script>window.location.href = "login";</script>';
        
?>

<?php include 'tml/bottom.php'; ?>