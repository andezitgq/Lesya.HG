<?php

    require 'include/db.php';

    session_start();
    
    if(isset($_POST['confirm-admin'])){
        if(isset($_SESSION['logged-user']) && $_SESSION['logged-user']->login == 'root'){
            header("Location: admin");
        } elseif(isset($_SESSION['logged-user'])){
            header("Location: profile");
        } else {
            header("Location: login");
        }
    }
    
    if(isset($_POST['m-confirm-admin'])){
        if(isset($_SESSION['logged-user']) && $_SESSION['logged-user']->login == 'root'){
            header("Location: admin");
        } elseif(isset($_SESSION['logged-user'])){
            header("Location: profile");
        } else {
            header("Location: login");
        }
    }

?>
    
<html>
    <head>
        <title><?php if(isset($page_title)) echo $page_title.' | '; ?>Гуманітарна гімназія ім. Лесі Українки</title>
        <link href="css/style.css?version=51" type="text/css" rel="stylesheet">
        <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.css">
        <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
        <link href="css/fontello-embedded.css" rel="stylesheet">
        <link href="img/logo.svg" rel="icon">
        <link rel="stylesheet" href="https://cdn.plyr.io/3.6.2/plyr.css" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="js/script.js"></script>
    </head>
    <body>
        <div class=header>
            <a href="/" title="Головна"><img class=logo src="img/logo.svg"></a>
            <h1 class=title>Гуманітарна гімназія<br>ім. Лесі Українки</h1>
            <!--<i class="icon-users soc-nav-button" title="Соціальні мережі"></i>-->
            <form class=nav-pc method=POST>
                <button onclick="window.location.href='/'" type="button" class=nav-button>Головна</button>
                <button onclick="window.location.href='join'" type="button" class=nav-button>Вступ</button>
                <button onclick="window.location.href='info'" type="button" class=nav-button>Наша гімназія</button>
                <button onclick="window.location.href='distance'" type="button" class=nav-button>Дистанційні завдання</button>
                <button onclick="window.location.href='media'" type="button" class=nav-button>Медіа</button>
                <button onclick="window.location.href='victory'" type="button" class=nav-button>ДО "VICTORY"</button>
                <button onclick="window.location.href='contact'" type="button" class=nav-button>Зв'язок</button>
                <?php if(isset($_SESSION['logged-user'])): ?>
                <button name="confirm-admin" type="submit" class=nav-button><?php echo $_SESSION['logged-user']->login; ?></button>
                <?php else: ?>
                <button name="confirm-admin" type="submit" class=nav-button>Увійти</button>
                <?php endif; ?>
            </form>
            <i class="icon-menu nav-mob"></i>
        </div>
        <form class=full-nav style=display:none method=POST>
            <button onclick="window.location.href='/'" type="button" class=m-nav-button>Головна</button>
            <button onclick="window.location.href='join'" type="button" class=m-nav-button>Вступ</button>
            <button onclick="window.location.href='info'" type="button" class=m-nav-button>Наша гімназія</button>
            <button onclick="window.location.href='distance'" type="button" class=m-nav-button>Дистанційні завдання</button>
            <button onclick="window.location.href='media'" type="button" class=m-nav-button>Медіа</button>
            <button onclick="window.location.href='victory'" type="button" class=m-nav-button>ДО "VICTORY"</button>
            <button onclick="window.location.href='contact'" type="button" class=m-nav-button>Зв'язок</button>
            <?php if(isset($_SESSION['logged-user'])): ?>
            <button name="m-confirm-admin" type="submit" class=m-nav-button><?php echo $_SESSION['logged-user']->login; ?></button>
            <?php else: ?>
            <button name="m-confirm-admin" type="submit" class=m-nav-button>Увійти</button>
            <?php endif; ?>
            <div class=m-social>
                <a title="Ми в Facebook" target="_blank" href="https://www.facebook.com/lesya.org" class="icon-facebook-squared"></a>
                <a title="Канал YouTube" target="_blank" href="https://www.youtube.com/gimnaziyazhv" class="icon-youtube-squared"></a>
                <a title="Профіль Instagram" target="_blank" href="https://www.instagram.com/gimnaziya.zhv/" class="icon-instagram"></a>
                <a title="Група Telegram" target="_blank" href="https://t.me/lesyazhv" class="icon-telegram"></a>
                <a title="Сервер Discord" target="_blank" href="https://discord.gg/sKUkNBK" class="icon-simplybuilt"></a>
                <a href="#" class="icon-cancel-circled" style=color:red></a>
            </div>
        </form>
        <div class=social-nav style=display:none>
            <a title="Ми в Facebook" target="_blank" href="https://www.facebook.com/lesya.org" class="icon-facebook-squared"></a>
            <a title="Канал YouTube" target="_blank" href="https://www.youtube.com/gimnaziyazhv" class="icon-youtube-squared"></a>
            <a title="Профіль Instagram" target="_blank" href="https://www.instagram.com/gimnaziya.zhv/" class="icon-instagram"></a>
            <a title="Група Telegram" target="_blank" href="https://t.me/lesyazhv" class="icon-telegram"></a>
            <a title="Сервер Discord" target="_blank" href="https://discord.gg/sKUkNBK" class="icon-simplybuilt"></a>
        </div>
        <div class=content>