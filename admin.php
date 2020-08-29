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
    
    if(isset($data['create-album'])){
        $albumid = rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9);
        $album_check = R::findOne('albums', 'albumid = ?', array($albumid));
        if(isset($album_check)){
            return;
        }
        $ext = pathinfo($_FILES['poster-file']['name'], PATHINFO_EXTENSION);
        
        $uploaddir = 'img/poster/';
        $uploadfile = $uploaddir.$albumid.'.'.$ext;
        if(preg_match('/image/', $_FILES['poster-file']['type'])){
            if (move_uploaded_file($_FILES['poster-file']['tmp_name'], $uploadfile)) {
                $album = R::dispense('albums');
                $album->albumid     = $albumid;
                $album->poster      = $uploadfile;
                $album->discription = $data['album-discription'];
                R::store($album);
                $media_status = "Альбом створений!.\n";
            } else
                $media_status = 'Файл не був завантажений!';
        } else
            $media_status = "Завантажений файл не є зображенням!";
    }
    
    if(isset($_GET['delete-album'])){
        $album = R::findOne('albums', 'albumid = ?', array($_GET['delete-album']));
        if($album){
            R::trash($album);
            unlink($album->poster);
            $media_status = 'Альбом видалений!';
        }
    }
    
    if(isset($data['submit-photo'])){
        $albumid = $_GET['select-album'];
        $discription = $data['photo-discription'];
        
        $ext = pathinfo($_FILES['photo-file']['name'], PATHINFO_EXTENSION);
        
        $uploaddir = 'img/photos/';
        $uploadfile = $uploaddir.$albumid.'.'.$ext;
        if(preg_match('/image/', $_FILES['photo-file']['type'])){
            if (move_uploaded_file($_FILES['photo-file']['tmp_name'], $uploadfile)) {
                $photo = R::dispense('photos');
                $photo->albumid     = $albumid;
                $photo->source      = $uploadfile;
                $photo->discription = $discription;
                R::store($photo);
                $media_status = "Фото додано!.\n";
            } else
                $media_status = 'Файл не був завантажений!';
        } else
            $media_status = "Завантажений файл не є зображенням!";
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
    <?php if(isset($media_status)) echo '<p class=media-status>'.$media_status.'</p>'; ?>
    <div class=media-engine>
        <div class=media-headers>
            <p class="header-list">Список альбомів</p>
            <p class="header-field">Список фото</p>
        </div>
        <div class=media-content>
            <div class=album-list>
                <?php
                
                    $albums = R::getAll( 'SELECT * FROM albums ORDER BY id ASC' );
                    for($i = -1; $i <= count($albums); $i++){
                        if(isset($albums[$i])){
                            echo '<form method=GET action="admin#media" class=album>'.
                                     '<button type=submit name=delete-album value="'.$albums[$i]['albumid'].'" class="album-delete icon-minus-squared" title="Видалити альбом"></button>'.
                                     '<a href="?select-album='.$albums[$i]['albumid'].'#media"><img class=poster-preview src="'.$albums[$i]['poster'].'"/></a>'.
                                     '<input class="album-discription" type=text value="'.$albums[$i]['discription'].'" readonly>'.
                                 '</form>';
                        }
                    }
                
                ?>
                <form enctype="multipart/form-data" class=add-album id=add-album method=POST action="admin#media">
                    <button type=submit name=create-album class="album-create icon-plus-squared" title="Створити альбом"></button>
                    <div class=poster-select-div><input type=file name=poster-file class=poster-select accept="image/*" required></div>
                    <input name=album-discription class=album-discription type=text placeholder="Назва альбому" required>
                </form>
            </div>
            <div class=album-field>
                <?php if (!isset($_GET['select-album']) || $_GET['select-album'] == ''): ?>
                    <label class="unselect">Виберіть альбом</label>
                <?php else: ?>
                    <!--<form method=GET action=admin#media class=add-photo>
                        <input type=text class=photo-discription readonly>
                        <button type=submit name=remove-photo value=123 class="icon-minus-squared submit-photo"></button>
                    </form>-->
                    <?php
                        $photos = R::findAll('photos', 'albumid = ?', array($_GET['select-album']));
                        for($i = -1; $i <= count($photos); $i++){
                            if(isset($photos[$i])){
                                echo '<form method=GET action=admin#media class=add-photo>'.
                                         '<input type=text class=photo-discription value="'.$photos[$i]['discription'].'" readonly>'.
                                         '<button type=submit name=remove-photo value="'.$photos[$i]['id'].'" class="icon-minus-squared submit-photo"></button>'.
                                     '</form>';
                            }
                        }
                    
                    ?>
                    <form enctype="multipart/form-data" id=add-photo method=POST action="admin#media" class=add-photo>
                        <input type=text placeholder="Опис фото" class=photo-discription name=photo-discription required>
                        <input type=file name=photo-file required>
                        <button type=submit name=submit-photo class="icon-plus-squared submit-photo"></button>
                    </form>
                <?php endif ?>
            </div>
        </div>
    </div>
</center>
<?php include 'tml/bottom.php'; ?>