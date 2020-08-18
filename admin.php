<?php include 'tml/top.php'; ?>
<?php
    $data = $_POST;

    if(!isset($_SESSION['login'])){
        echo '<script>window.location.href = "login";</script>';
    }
    
    if(isset($_GET['unset-session'])){
        session_unset();
        echo '<script>window.location.href = "/";</script>';
    }

    require 'tml/Parsedown.php';
    $parsedown = new Parsedown();
    
    function custom_parse($pars){
        preg_match('#<yt>(.*)</yt>#', $pars, $link);
        
        $patterns[0] = '/<vid>/';
        $patterns[1] = '/<\/vid>/';
        $replacements[0] = '<video class=jplayer playsinline controls src="';
        $replacements[1] = '"></video>'."\r\n\r\n";
        
        $s_pattern = '#<yt>(.*)</yt>#';
        $s_replacement = '<div class="plyr__video-embed jplayer"><iframe src="'.$link[1].'"allowfulls0creen allowtransparency allow="autoplay"></iframe></div>'."\r\n\r\n";
        
        $a_patterns[0] = '/<mus>/';
        $a_patterns[1] = '/<\/mus>/';
        $a_replacements[0] = '<audio class=jplayer src="';
        $a_replacements[1] = '" controls></audio>'."\r\n\r\n";
        
        ksort($patterns);
        ksort($replacements);
        
        ksort($a_patterns);
        ksort($a_replacements);
        
        $first =  preg_replace($patterns, $replacements, $pars);
        $second = preg_replace($s_pattern, $s_replacement, $first);
        return preg_replace($a_patterns, $a_replacements, $second);
    }
    
    if(isset($data['do-preview'])){
        if(isset($data['post-editor'])){
            $preview = $parsedown->text(custom_parse($data['post-editor']));
        }
    }
    
    if(isset($data['send'])){
        $postfile = file_get_contents('post/post.php');
        $parse = $parsedown->text(custom_parse($data['post-editor']));
        $post = '<div class=post>
                     <i class=post-date>'.date('m.d.Y G:i', time()).'</i>'.$parse.'
                 </div>';
        if(!file_put_contents('./post/post.php', $post.$postfile)){
            echo 'ERROR';
        }
    }

?>

<center>
    <h1>Панель керування</h1><br>
    <div id="account" class="account admin-nav">
        <a href="#post-manager">Управління постами</a>
        <a href="#create-post">Створити пост</a>
        <a href="#preview">Швидкий перегляд</a>
        <a href="#comments">Коментарі</a>
        <a href="#media">Медіа</a>
        <a href="#create-post">Дистанційні завдання</a>
        <a href="?unset-session" id=unset-session>Вийти з аккаунту</a>
    </div><br><br><br>
    <a class="anchor" id="create-post"></a>
    <h2>Створити пост</h2>
    <form method=POST action="admin" class=blog-engine>
        <div class=edit-buttons>
            <div class=edit-buttons>
                <button type="button" id="bold"        class="edit-btn" title=Жирний>B</button>
                <button type="button" id="italic"      class="edit-btn" title=Курсив>I</button>
                <button type="button" id="underlined"  class="edit-btn" title=Підкреслений>U</button>
                <button type="button" id="intersected" class="edit-btn" title=Закреслений>S</button>
                <button type="button" class="icon-link edit-btn" title=Посилання value="[Назва_посилання](Посилання)"></button>
                <button type="button" class="icon-video edit-btn" title=Відео value="<vid>Посилання_на_відео</vid>"></button>
                <button type="button" class="icon-youtube-squared edit-btn" title="Відео з YouTube" value="<yt>Посилання_на_відео_YouTube</yt>"></button>
                <button type="button" class="icon-picture edit-btn" title=Зображення value="![Альтернативний текст](Зображення)"></button>
                <button type="button" class="icon-music edit-btn" title=Аудіо value="<mus>Посилання_на_аудіо</mus>"></button>
                <button type="button" class="icon-list-bullet edit-btn" id="list-btn" title=Список></button>
            </div>
            <div class=title-box>
                <input placeholder="Назва посту" class=post-title>
            </div>
        </div>
        <textarea name="post-editor" id="post-editor" class=post-editor><?php echo $data['post-editor']; ?></textarea>
        <div class=control-buttons>
            <button type="submit" name=send id=send class=icon-ok-circled>ОК</button>
            <button type="submit" name=do-preview>Перегляд</button>
            <button type="button" class=icon-cancel-circled>Стерти</button>
        </div>
        <br>
        <a class="anchor" id="preview"></a>
        <h2>Швидкий перегляд</h2>
        <div class=post-preview>
            <div class=post>
                <i class=post-date><?php echo date('m.d.Y G:i', time()); ?></i>
                <?php echo $preview; ?>
            </div>
        </div>
    </form>
    <br>
    <a class="anchor" id="media"></a>
    <h2>Медіа</h2>
    <form method=POST action="admin" class=media-engine>
        <?php
        
        $gallery = json_decode(file_get_contents('./gallery.json'));
        var_dump($gallery);
        
        ?>
    </form>
</center>
<?php include 'tml/bottom.php'; ?>