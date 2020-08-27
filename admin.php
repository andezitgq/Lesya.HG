<?php

    $page_title = 'Панель адміністрування';
    include 'tml/top.php';

    $data = $_POST;

    if(!isset($_SESSION['logged-user']) ||
       $_SESSION['logged-user']->login != 'root')
            echo '<script>window.location.href = "login";</script>';

    require 'tml/Parsedown.php';
    $parsedown = new Parsedown();
    
    function custom_parse($pars){
        preg_match('#<yt>(.*)</yt>#', $pars, $link);
        
        $patterns[0] = '/<vid>/';
        $patterns[1] = '/<\/vid>/';
        $replacements[0] = '<video class=jplayer playsinline controls src="';
        $replacements[1] = '"></video>'."\r\n\r\n";
        
        $s_pattern = '#<yt>(.*)</yt>#';
        if(count($link) > 0)
            $s_replacement = '<div class="plyr__video-embed jplayer"><iframe src="'.$link[1].'"allowfulls0creen allowtransparency allow="autoplay"></iframe></div>'."\r\n\r\n";
        else
            $s_replacement = '';
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
        $post = $parsedown->text(custom_parse($data['post-editor']));
        
        $postdate         = R::dispense('postdate');
        $postdate->date   = date('Y-m-d G:i:s', time());
        $date_id          = R::store($postdate);
        
        $posttitle         = R::dispense('posttitle');
        $posttitle->title  = $data['p-title'];
        $title_id          = R::store($posttitle);
        
        $postauthor         = R::dispense('postauthor');
        $postauthor->author = $data['p-author'];
        $author_id          = R::store($postauthor);
        
        $postdb = R::dispense('post');
        $postdb->content   = $post;
        $postdb->date_id   = $date_id;
        $postdb->title_id  = $title_id;
        $postdb->author_id = $author_id;
        R::store($postdb);
    }
    
    function old_parse(){
        $html = file_get_contents('./post/temp.php');
        
        $dom = new DOMDocument;
        $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'utf-8'));
        $xpath = new DOMXpath($dom);
        $sql_data = array();
        
        foreach($xpath->query('//div[contains(@class, "inner-post")]') as $node){
            preg_match('/опублікував (.*)/', $node->textContent, $author);
            
            preg_match('/Новина від (.*) о/', $node->textContent, $date);
            $date     = preg_replace('/о/', '', $date);
            $datetime = date_create($date[1]);
            $newdate  = date_format($datetime, 'Y-m-d G:i:s');
            
            $htmlString = $dom->saveHTML($node);
            preg_match('#<h1>(.*?)</h1>#si', $htmlString, $title);
            
            $content = preg_replace('#<h1>.*?</h1>#si', '',
                       preg_replace('/<div style="text-align:justify; padding:3px; margin-top:3px; margin-bottom:5px; border-top:1px solid #D3D3D3;">/', '<div>',
                       preg_replace('#<div style="float: right;">.*?</div>#si', '',
                       preg_replace('#<div><em>.*?</em></div>#si', '', $htmlString))));
            
            /*
            echo 'Content: '.$content.'<br>';
            echo 'Date   : '. $newdate .'<br>';
            echo 'Author : '.$author[1].'<br>';
            echo 'Title  : '.$title[1].'<br><br>';
            */
            
            $sql_data['content'][] = $content;
            $sql_data['date'][]    = $newdate;
            $sql_data['author'][]  = $author[1];
            $sql_data['title'][]   = $title[1];
        }
        for($i = count($sql_data['content']) - 1; $i >=0; $i--){
            $post = $sql_data['content'][$i];
        
            $postdate         = R::dispense('postdate');
            $postdate->date   = $sql_data['date'][$i];
            $date_id          = R::store($postdate);
            
            $posttitle         = R::dispense('posttitle');
            $posttitle->title  = $sql_data['title'][$i];
            $title_id          = R::store($posttitle);
            
            $postauthor         = R::dispense('postauthor');
            $postauthor->author = $sql_data['author'][$i];
            $author_id          = R::store($postauthor);
            
            $postdb = R::dispense('post');
            $postdb->content   = $post;
            $postdb->date_id   = $date_id;
            $postdb->title_id  = $title_id;
            $postdb->author_id = $author_id;
            
            R::store($postdb);
        }
    }
    
    if(isset($_GET['unset-session'])){
        unset($_SESSION['logged-user']);
        echo '<script>window.location.href = "/";</script>';
    }
    
    if(isset($_GET['old-parse'])){
        old_parse(); //Y-m-d G:i:s
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
        <a href="?unset-session">Вийти з аккаунту</a>
        <!--<a href="?old-parse">Старий парс</a>-->
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
                <select class=post-headers>
                    <option disabled selected value>Тип заголовка</option>
                    <option disabled></option>
                    <option>Заголовок 1</option>
                    <option>Заголовок 2</option>
                    <option>Заголовок 3</option>
                    <option>Заголовок 4</option>
                    <option>Заголовок 5</option>
                    <option>Заголовок 6</option>
                </select>
                <input placeholder="Назва посту" class=post-title id=p-title name=p-title required value="<?php if(isset($data['p-title'])) echo $data['p-title']; ?>">
            </div>
        </div>
        <textarea name="post-editor" id="post-editor" class=post-editor><?php if(isset($data['post-editor'])) echo $data['post-editor']; ?></textarea>
        <div class=control-buttons>
            <div class=control-buttons>
                <div class=title-box>
                    <input placeholder="Автор посту" class='post-title author' id=p-author name=p-author required value="<?php if(isset($data['p-author'])) echo $data['p-author']; ?>">
                </div>
                <button type="submit" name=send id=send class=icon-ok>ОК</button>
                <button type="submit" name=do-preview>Перегляд</button>
                <button type="button" class=icon-cancel>Стерти</button>
            </div>
        </div>
        <br>
        <a class="anchor" id="preview"></a>
        <h2>Швидкий перегляд</h2>
        <div class=post-preview>
            <div class=post>
                <i class=post-date><?php echo date('m.d.Y G:i', time()); ?> 📝 <?php if(isset($data['p-author'])) echo $data['p-author']; else echo 'Автор посту'; ?></i>
                <?php if(isset($data['p-title'])) echo '<h1>'.$data['p-title'].'</h1><hr><br>'; ?>
                <?php if(isset($preview)) echo $preview; ?>
            </div>
        </div>
    </form>
    <br>
    <a class="anchor" id="media"></a>
    <h2>Медіа</h2>
    <form enctype="multipart/form-data" method=POST action="admin" class=media-engine>
        <div class=media-headers>
            <p class="header-list">Список альбомів</p>
            <p class="header-field">Список фото</p>
        </div>
        <div class=media-content>
            <div class=album-list>
                <div class=album>
                    <a href=# class=icon-cancel-circled></a>
                </div>
            </div>
            <div class=album-field>
                <p class="unselect">Виберіть альбом</p>
            </div>
        </div>
    </form>
</center>
<?php include 'tml/bottom.php'; ?>