<?php include 'tml/top.php'; ?>
<?php

    if(isset($_SESSION['login'])){
        echo '<script>window.location.href = "/";</script>';
    }

    $data = $_POST;
    $parse_pswd = parse_ini_file('pswd.ini');
    if(isset($data['do_login'])){
        $errors = array();
        if($data['login'] == $parse_pswd['login']){
            if($data['pswd'] == $parse_pswd['pswd']){
                $_SESSION['login'] = $data['login'];
                $_SESSION['pswd']  = $data['pswd'];
                echo '<script>window.location.href = "admin";</script>';
            } else {
                $errors[] = "Логін чи пароль введені неправильно!";
            }
        } else {
            $errors[] = "Логін чи пароль введені неправильно!";
        }
    }
    
    if(!empty($errors)){
        echo '<h1 style="color:red">'.array_shift($errors).'</h1>';
    }

?>
    <form action="login.php" method="POST" class="login-form">
        <label>Логін</label>
        <input name=login type="text">
        <br>
        <label>Пароль</label>
        <input name=pswd type="password" >
        <button name=do_login type="submit">Увійти</button>
    </form>
<?php include 'tml/bottom.php'; ?>