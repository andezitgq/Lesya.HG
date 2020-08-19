<?php include 'tml/top.php'; ?>

<form action="register" method="POST" class="login-form">
    <label>Логін</label>
    <input name=login type="text" required>
    <br>
    <label>Ім'я та прізвище</label>
    <input name=login type="text" required>
    <br>
    <label>Пошта</label>
    <input name=login type="email" required>
    <br>
    <label>Пароль</label>
    <input name=pswd type="password" required>
    <button name=do_reg type="submit">Увійти</button>
</form>
<br>
<center><p>Вже маєте аккаунт? <a href=login>Увійдіть</a></p></center>

<?php include 'tml/bottom.php'; ?>