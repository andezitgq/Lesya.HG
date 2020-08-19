<?php include 'tml/top.php'; ?>
<?php

    if(isset($_SESSION['logged-user']))
        echo '<script>window.location.href = "/";</script>';

    $data = $_POST;
    if(isset($data['do_reg'])){
        $errors = array();
        if(strlen($data['login']) < 4)
            $errors[] = 'Логін занадто короткий';
        if(strlen($data['pswd']) < 8)
            $errors[] = 'Пароль занадто короткий';
        if(empty($errors)){
            $conf_subject = 'Website Change Request';
            
            $headers = "From: "."Gymnasium n.a Lesya Ukrainka <no-reply@lesya.org>";
            $headers .= "CC: susan@example.com\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
            $msg = '<h1>Hello, World!</h1><br>'.$data['sname'].",\n\nThank you for your recent enquiry. A member of our team will respond to your message as soon as possible.";
            
            var_dump(mail($data['email'], $conf_subject, $msg, $headers));
        }
    }
    
    if(!empty($errors)){
        echo '<center><div class=error>'.array_shift($errors).'</div><center>';
    }

?>
<form action="register" method="POST" class="login-form">
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
    <button name=do_reg type="submit">Увійти</button>
</form>
<br>
<center><p>Вже маєте аккаунт? <a href=login>Увійдіть</a></p></center>

<?php include 'tml/bottom.php'; ?>