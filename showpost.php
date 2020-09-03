<?php include 'tml/top.php'; ?>
    <?php
    
        session_start();
        $data = $_POST;
    
        if(!isset($_GET['postid']) || $_GET['postid'] == '' || $_GET['postid'] == 0)
            echo '<script>window.location.href = "/";</script>';
            
        $post = R::findOne('post', 'id = ?', array($_GET['postid']));
        if($post){
            $date = date_create(R::findOne('postdate', 'id = ?', array($post->date_id))->date);
            $author = R::findOne('postauthor', 'id = ?', array($post->author_id));
            $title = R::findOne('posttitle', 'id = ?', array($post->title_id));
            echo '<i class=post-date>';
            
                if(isset($date))
                    echo date_format($date, 'd.m.Y G:i');
                if(isset($author->author))
                    echo ' 📝 '.$author->author;
            
            echo '</i>';
            
            echo '<div class=post>';
                                
                    if(isset($title->title))
                        echo '<h1>'.$title->title.'</h1><hr><br>';
                    if(isset($post->content))
                        echo $post->content;
                        
            echo '</div>';
        }
        
        if(isset($data['comment'])){
            if($data['code'] == $_SESSION['rand_code']){
                preg_match('', $data['comment-field'], $matches);
                if($matches[1] == ''){
                    $answer = R::dispense('answers');
                    $answer->postid   = $_GET['postid'];
                    $answer->content  = $data['comment-field'];
                    $answer->date     = date('Y-m-d G:i:s', time());
                    $answer->comid    = '';
                    $answer->authorid = $_SESSION['logged-user']->id;
                    $cid = R::store($answer);
                    
                    $comuser = R::findOne('userinfo', 'id = ?', array($_SESSION['logged-user']->userinfo));
                    $comuser->comments = $comuser->comments + 1;
                    $uid = R::store($comuser);
                } else {
                    $comment = R::dispense('comments');
                    $comment->postid   = $_GET['postid'];
                    $comment->content  = $data['comment-field'];
                    $comment->date     = date('Y-m-d G:i:s', time());
                    $comment->authorid = $_SESSION['logged-user']->id;
                    $cid = R::store($comment);
                    
                    $comuser = R::findOne('userinfo', 'id = ?', array($_SESSION['logged-user']->userinfo));
                    $comuser->comments = $comuser->comments + 1;
                    $uid = R::store($comuser);
                }
            } else {
                $comerror = 'Код введений невірно!';
            }
        }
    ?>
    <?php if(isset($_SESSION['logged-user'])): ?>
        <form action="showpost?postid=<?php echo $_GET['postid']; ?>" method=POST enctype="multipart/form-data" class=comment-form>
            <h2>Залишити коментар</h2>
            <?php if(isset($comerror)) echo '<h1 class=comerror>'.$comerror.'</h1>'; ?>
            <textarea name="comment-field" required placeholder="Текст коментаря"></textarea>
            <img src="font/captcha/captcha"/>
            <p>
                <label style=margin-top:10px></label>
                <input type="text" name="code" required placeholder="Введіть число з зображення"/>
            </p>
            <button type="submit" name="comment">Надіслати</button>
        </form>
    <?php else: ?>
        <br><p>Щоб залишити коментар <a href=register>зареєструйтесь</a> або <a href=login>увійдіть в акканут</a></p>
    <?php endif; ?>
    <br><h2>Коментарі</h2>
    <?php
    
        $comments = R::getAll('SELECT * FROM comments WHERE postid = '.$_GET['postid'].' ORDER BY date DESC');
        for($i = -1; $i <= count($comments); $i++){
            if(isset($comments[$i])){
                $comdate = date_create($comments[$i]['date']);
                $author = R::findOne('users', 'id = ?', array($comments[$i]['authorid']));
                $authorinfo = R::findOne('userinfo', 'id = ?', array($author->userinfo));
                echo '<a name=com'.$comments[$i]['id'].'></a>'.
                     '<div class="comment-box">'.
                         '<div class=comment-user>'.
                             '<img src="'.$authorinfo->avatar.'">'.
                             '<p>'.$author->fullname.'</p>'.
                         '</div>'.
                         '<p>'.$comments[$i]['content'].'</p>'.
                         '<label class=comid>#'.$comments[$i]['id'].'</label>'.
                     '</div>';
            }
        }
        
        if(count($comments) <= 0){
            echo '<center><label>Коментарів не знайдено!</label></center>';
        }
    
    ?>

<?php include 'tml/bottom.php'; ?>
