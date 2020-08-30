<?php

    $page_title = 'Реєстрація';
    include 'tml/top.php';

    if(isset($_SESSION['logged-user']))
        echo '<script>window.location.href = "/";</script>';

    $code_field  = false;
    
    $data = $_POST;
    if(isset($data['do_reg'])){
        $errors = array();
        if(R::count('users', "login = ?", array($data['login'])))
            $errors[] = 'Користувач з таким логіном вже існує';
        if(R::count('users', "email = ?", array($data['email'])))
            $errors[] = 'Користувач з таким Email вже існує';
        if(strlen($data['login']) < 4)
            $errors[] = 'Логін занадто короткий';
        if(strlen($data['pswd']) < 8)
            $errors[] = 'Пароль занадто короткий';
        if(empty($errors)){
            $_SESSION['confirm_int'] = rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9);
            $conf_subject = 'Підтвердження e-mail';
            
            $headers = "From: "."Lesya Ukrainka Gymnasium <no-reply@lesya.org>\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            $msg = '<style>
                        @import url(\https://fonts.googleapis.com/css2?family=Montserrat:wght@300;500&display=swap");
                        @import url("https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300&display=swap");
                        * {
                            font-weight: lighter;
                            font-size: 18px;
                        }
                        .main {
                            width:100%;
                            font-size: 34px;
                            font-family: "Cormorant Garamond", serif;
                            background: #1b466f;
                            height: 50px;
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            color: white;
                            font-weight: lighter;
                        }
                        .code {
                            font-family: "Montserrat", sans-serif;
                            color: #1b466f;
                            font-size: 50px;
                            margin-top: 50px;
                            margin-bottom: 50px;
                        }
                    </style>
                    <div class=main><p>Гуманітарна гімназія ім. Лесі Українки</p></div>
                    <center><h1 class=code>'.$_SESSION['confirm_int'].'</h1></center>
                    Привіт, '.$data['sname'].'. Ви майже зареєструвалися на сайті Гуманітарної гімназії ім. Лесі Українки. Для завершення реєстрації введіть цей код в полі для коду на сторінці реєстрації.
            ';
                    
            $_SESSION['q-login'] = $data['login'];
            $_SESSION['q-sname'] = $data['sname'];
            $_SESSION['q-email'] = $data['email'];
            $_SESSION['q-pswd']  = password_hash($data['pswd'], PASSWORD_DEFAULT);
            
            $code_field = true;
            
            mail($data['email'], $conf_subject, $msg, $headers);
        }
    }
    
    if(isset($data['do_submit'])){
        $errors = array();
        if($data['code'] == $_SESSION['confirm_int']){
            $userinfo = R::dispense('userinfo');
            $userinfo->avatar = 'img/profile.svg';
            $userinfo->aboutme = 'Змінити інформацію про себе';
            $userinfo->comments = 0;
            $userinfo->regdate = date('m.d.Y G:i', time());
            $userinfo->actype = 'Гість';
            $info_id = R::store($userinfo);
            
            $user = R::dispense('users');
            $user->login    = $_SESSION['q-login'];
            $user->fullname = $_SESSION['q-sname'];
            $user->email    = $_SESSION['q-email'];
            $user->password = $_SESSION['q-pswd'];
            $user->userinfo = $info_id;
            R::store($user);
            $_SESSION['logged-user'] = $user;
            echo '<script>window.location.href = "/";</script>';
        } else
            $code_field = true;
            $errors[] = 'Код введений невірно';
    }
    
    if(!empty($errors)){
        echo '<center><div class=error>'.array_shift($errors).'</div><center>';
    }

?>
<?php if($code_field == true): ?>
<center><form action="register" method="POST" class="login-form">
    <input name=code type="number" required value="<?php if(isset($data['code'])) echo $data['code']; ?>">
    <button name=do_submit type="submit">Підтвердити</button>
</form></center>
<?php else: ?>
<center><form action="register" method="POST" class="login-form">
    <label>Логін</label>
    <input name=login type="text" required value="<?php if(isset($data['login'])) echo $data['login']; ?>">
    <br>
    <label>Ім'я та прізвище</label>
    <input name=sname type="text" required value="<?php if(isset($data['sname'])) echo $data['sname']; ?>">
    <br>
    <label>Пошта</label>
    <input name=email type="email" required value="<?php if(isset($data['email'])) echo $data['email']; ?>">
    <br>
    <label>Пароль</label>
    <input name=pswd type="password" required value="<?php if(isset($data['pswd'])) echo $data['pswd']; ?>">
    <button name=do_reg type="submit">Реєстрація</button>
</form></center>
<br>
<center><p>Вже маєте аккаунт? <a href=login>Увійдіть</a></p></center>
<?php endif; ?>


<?php include 'tml/bottom.php'; ?>