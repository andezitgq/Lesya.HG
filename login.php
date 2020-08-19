<?php include 'tml/top.php'; ?>
<?php

    if(isset($_SESSION['logged-user']))
        echo '<script>window.location.href = "/";</script>';

    $data = $_POST;
    if(isset($data['do_login'])){
        $errors = array();
        $user = R::findOne('users', 'login = ?', array($data['login']));
        if($user){
            if(password_verify($data['pswd'], $user->pswd)){
                $_SESSION['logged-user'] = $user;
            } else
                $errors[] = 'Пароль невірний!';
        } else
            $errors[] = 'Користувач с таким логіном не знайдений';
    }
    
    if(!empty($errors)){
        echo '<h1 style="color:red">'.array_shift($errors).'</h1>';
    }

?>
    <form action="login" method="POST" class="login-form">
        <label>Логін</label>
        <input name=login type="text" required>
        <br>
        <label>Пароль</label>
        <input name=pswd type="password" required>
        <button name=do_login type="submit">Увійти</button>
    </form>
    <br>
    <center><p>Ще не маєте аккаунту? <a href=register>Зареєструйтесь</a></p></center>
<?php include 'tml/bottom.php'; ?>