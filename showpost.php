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
                $comment = R::dispense('comments');
                $comment->postid   = $_GET['postid'];
                $comment->content  = $data['comment-field'];
                $comment->authorid = $_SESSION['logged-user']->id;
                $cid = R::store($comment);
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
        <br><p>Щоб залишити коментар <a href=register>зареєструйтесь</a> або <a href=login>увійдіть</a> в акканут</p>
    <?php endif; ?>
<?php include 'tml/bottom.php'; ?>
