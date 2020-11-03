<?php

    $page_title = 'Панель адміністрування';
    include 'tml/top.php';

    $data = $_POST;

    if(!isset($_SESSION['logged-user']) ||
       $_SESSION['logged-user']->login != 'root')
            echo '<script>window.location.href = "login";</script>';

    require 'tml/Parsedown.php';
    $parsedown = new Parsedown();
    
    function saveToImgBB($image,$name = null){
        $API_KEY = 'ada56faf5a2545ab10970e17344ef4e4';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.imgbb.com/1/upload?key='.$API_KEY);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
        $extension = pathinfo($image['name'],PATHINFO_EXTENSION);
        $file_name = ($name)? $name.'.'.$extension : $image['name'] ;
        $data = array('image' => base64_encode(file_get_contents($image['tmp_name'])), 'name' => $file_name);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            return 'Error:' . curl_error($ch);
        }else{
            return json_decode($result, true);
        }
        curl_close($ch);
    }

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
    
    if(isset($data['do-preview']) && $_SESSION['logged-user']->login == 'root'){
        if(isset($data['post-editor'])){
            $preview = $parsedown->text(custom_parse($data['post-editor']));
        }
    }
    
    if(isset($data['send']) && $_SESSION['logged-user']->login == 'root'){
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

    if(isset($_GET['remove-post']) && $_SESSION['logged-user']->login == 'root'){
        $rmpost = R::findOne('post', 'id = ?', array($_GET['remove-post']));
        $rmdate = R::findOne('postdate', 'id = ?', array($rmpost->date_id));
        $rmtitle = R::findOne('posttitle', 'id = ?', array($rmpost->title_id));
        $rmauthor = R::findOne('postauthor', 'id = ?', array($rmpost->author_id));
        R::trash($rmpost);
        R::trash($rmdate);
        R::trash($rmtitle);
        R::trash($rmauthor);
    }

    if(isset($_GET['rename-post']) && $_SESSION['logged-user']->login == 'root'){
        $rnpost = R::findOne('post', 'id = ?', array($_GET['rename-post']));
        $rntitle = R::findOne('posttitle', 'id = ?', array($rnpost->title_id));
        $rntitle->title = $_GET['rename-field'];
        R::store($rntitle);
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
    
    if(isset($_GET['unset-session']) && $_SESSION['logged-user']->login == 'root'){
        unset($_SESSION['logged-user']);
        echo '<script>window.location.href = "/";</script>';
    }
    
    if(isset($_GET['old-parse']) && $_SESSION['logged-user']->login =='root'){
        old_parse(); //Y-m-d G:i:s
    }
    
    if(isset($data['create-album']) && $_SESSION['logged-user']->login =='root'){
        $albumid = rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9);
        $album_check = R::findOne('albums', 'albumid = ?', array($albumid));
        if(isset($album_check)){
            return;
        }

        if(isset($_FILES['poster-file'])){
            if(preg_match('/image/', $_FILES['poster-file']['type']) && !preg_match('/svg/', $_FILES['poster-file']['type'])){
                if($_FILES['poster-file']['size'] <= 15728640) {
                    $return = saveToImgBB($_FILES['poster-file']);
                    $album = R::dispense('albums');
                    $album->albumid     = $albumid;
                    $album->poster      = $return['data']["url"];
                    $album->discription = $data['album-discription'];
                    R::store($album);
                    $media_status = "Альбом створений!.\n";
                } else
                    echo "<h2 style='background: #1b466f; text-align:center; color: white; padding: 5px; border-radius: 10px'>Завантажений файл більше 15МБ!</h2>";
            } else
                echo "<h2 style='background: #1b466f; text-align:center; color: white; padding: 5px; border-radius: 10px'>Завантажений файл не є зображенням!</h2>";
        }
    }
    
    if(isset($_GET['delete-album']) && $_SESSION['logged-user']->login == 'root'){
        $album = R::findOne('albums', 'albumid = ?', array($_GET['delete-album']));
        $photoss = R::getAll('SELECT * FROM photos WHERE albumid = '.$_GET['delete-album']);
        if($album){
            if($photoss)
                R::trashAll($photoss);
            unlink($album->poster);
            R::trash($album);
            $media_status = 'Альбом видалений!';
        }
    }
    
    if(isset($data['submit-photo']) && $_SESSION['logged-user']->login == 'root'){
        $photoid = rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9).rand(0, 9);
        $photoid_check = R::findOne('photos', 'photoid = ?', array($photoid));
        if(isset($photoid_check)){
            return;
        }
        
        $albumid = $data['albumid'];
        $discription = $data['photo-discription'];

        if(isset($_FILES['photo-file'])){
            if(preg_match('/image/', $_FILES['photo-file']['type']) && !preg_match('/svg/', $_FILES['photo-file']['type'])){
                if($_FILES['photo-file']['size'] <= 15728640) {
                    $return = saveToImgBB($_FILES['photo-file']);
                    $photo = R::dispense('photos');
                    $photo->albumid     = $albumid;
                    $photo->source      = $return['data']["url"];
                    $photo->discription = $discription;
                    $photo->photoid     = $photoid;
                    R::store($photo);
                    echo '<script>window.location.href = "admin?select-album='.$albumid.'#media"</script>';
                } else
                    echo "<h2 style='background: #1b466f; text-align:center; color: white; padding: 5px; border-radius: 10px'>Завантажений файл більше 15МБ!</h2>";
            } else
                echo "<h2 style='background: #1b466f; text-align:center; color: white; padding: 5px; border-radius: 10px'>Завантажений файл не є зображенням!</h2>";
        }
    }
    
    if(isset($_GET['remove-photo']) && $_GET['remove-photo'] != '' && $_SESSION['logged-user']->login == 'root'){
        $photo = R::findOne('photos', 'photoid = ?', array($_GET['remove-photo']));
        if($photo){
            unlink($photo->source);
            R::trash($photo);
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
        <a href="#documents">Документи</a>
        <a href="?unset-session">Вийти з аккаунту</a>
        <!--<a href="?old-parse">Старий парс</a>-->
    </div><br><br><br>
    <a class="anchor" id="post-manager"></a>
    <h2>Управлінная постами</h2>
    <div class=post-manager>
        <div class="pm-posts">
            <?php 
            
            $all = R::getAll( 'SELECT * FROM postdate ORDER BY date DESC LIMIT 11' );
            for($i = -1; $i <= max(array_keys($all)); $i++){
                if(isset($all[$i])){
                    $date = date_create($all[$i]['date']);
                    $post = R::findOne('post', 'date_id = ?', array($all[$i]['id']));
                    $author = R::findOne('postauthor', 'id = ?', array($post->author_id));
                    $title = R::findOne('posttitle', 'id = ?', array($post->title_id));
                    if($post->id != 88)
                    {
                        echo '<form method=GET action="admin" class="pm-post">'.
                                 '<input type=text name="rename-field" value="'.$title->title.'" required>'.
                                 '<i>'.date_format($date, 'd.m.Y G:i').'</i>'.
                                 '<button name=remove-post value="'.$all[$i]['id'].'">Видалити</button>'.
                                 '<button name=rename-post value="'.$all[$i]['id'].'">Перейменувати</button>'.
                                 "<button type=button onclick=\"window.location.href = 'editpost?postid=".$all[$i]['id']."';\">Редагувати як HTML</button>".
                             '</form>';
                    }
                }
            }

            ?>
        </div>
    </div>
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
            <i class=post-date><?php echo date('m.d.Y G:i', time()); ?> 📝 <?php if(isset($data['p-author'])) echo $data['p-author']; else echo 'Автор посту'; ?></i>
            <div class=post>
                <?php if(isset($data['p-title'])) echo '<h1>'.$data['p-title'].'</h1><hr><br>'; ?>
                <?php if(isset($preview)) echo $preview; ?>
            </div>
        </div>
    </form>
    <br>
    <a class="anchor" id="comments"></a>
    <h2>Коментарі та відповіді</h2>
    <div class="comment-editor">
        <div class=post-manager>
            <div class="pm-posts">
                <h3 style="color: white; background: #1b466f; border-bottom: 2px solid #13324f">Коментарі</h3>
                <?php 
                
                $all = R::getAll( 'SELECT * FROM comments ORDER BY date DESC LIMIT 26' );
                for($i = -1; $i <= max(array_keys($all)); $i++){
                    if(isset($all[$i])){
                        $date = date_create($all[$i]['date']);
                        $content = $all[$i]['content'];
                        $author = R::findOne('users', 'id = ?', array($all[$i]['authorid']));
                            echo '<form method=GET action="admin" class="pm-post">'.
                                    '<p style=color:white>'.substr($content, 0, 21).'...</p>'.
                                    '<i style="color:white; margin-right: 20px">'.date_format($date, 'd.m.Y G:i').'</i>'.
                                    '<p style="color:white; margin-right: auto">'.$author->login.'</p>'.
                                    '<button name=remove-comment value="'.$all[$i]['id'].'">Видалити</button>'.
                                '</form>';
                    }
                }

                ?>
            </div>
        </div>
        <div class=post-manager>
            <div class="pm-posts">
                <h3 style="color: white; background: #1b466f; border-bottom: 2px solid #13324f;">Відповіді</h3>
                <?php 
                
                $all = R::getAll( 'SELECT * FROM answers ORDER BY date DESC LIMIT 26' );
                for($i = -1; $i <= max(array_keys($all)); $i++){
                    if(isset($all[$i])){
                        $date = date_create($all[$i]['date']);
                        $content = preg_replace('#<a href=".*?</a>#si', '', $all[$i]['content']);
                        $author = R::findOne('users', 'id = ?', array($all[$i]['authorid']));
                            echo '<form method=GET action="admin" class="pm-post" style="flex-direction: row;">'.
                                    '<p style=color:white>'.substr($content, 0, 21).'...</p>'.
                                    '<i style="color:white; margin-right: 20px">'.date_format($date, 'd.m.Y G:i').'</i>'.
                                    '<p style="color:white; margin-right: auto">'.$author->login.'</p>'.
                                    '<button name=remove-comment value="'.$all[$i]['id'].'">Видалити</button>'.
                                '</form>';
                    }
                }

                ?>
            </div>
        </div>
    </div>
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
                
                    $albums = R::getAll( 'SELECT * FROM albums ORDER BY id DESC' );
                    for($i = -1; $i <= max(array_keys($albums)); $i++){
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
                    <?php
                        $albumid_n = $_GET['select-album'];
                        $photosk = R::getAll('SELECT * FROM photos WHERE albumid = '.$albumid_n);
                        for($i = -1; $i <= max(array_keys($photosk)); $i++){
                            if(isset($photosk[$i])){
                                echo '<div class=add-photo>'.
                                         '<input type=text class=photo-discription value="'.$photosk[$i]['discription'].'" readonly>'.
                                         '<a href="?remove-photo='.$photosk[$i]['photoid'].'&select-album='.$albumid_n.'#media" class="icon-minus-squared submit-photo"></a>'.
                                     '</div>';
                            }
                        }
                    
                    ?>
                    <form enctype="multipart/form-data" id=add-photo method=POST action="admin#media" class=add-photo>
                        <input type=text placeholder="Опис фото" class=photo-discription name=photo-discription required>
                        <input type=file name=photo-file required>
                        <input type=hidden name=albumid value="<?php echo $_GET['select-album'] ?>">;
                        <button type=submit name=submit-photo class="icon-plus-squared submit-photo"></button>
                    </form>
                <?php endif ?>
            </div>
        </div>
    </div>
    <br>
    <a class="anchor" id="documents"></a>
    <h2>Документи</h2>
    <form action="">
        123
    </form>
    <br>
</center>
<?php include 'tml/bottom.php'; ?>