<?php include 'tml/top.php'; ?>
    <?php
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
    ?>
    <?php if(isset($_SESSION['logged-user'])): ?>
        <form method=POST action="showpost?postid=<?php echo $_GET['postid']; ?>" class=comment-form>
            <button type=submit>sas</button>
        </form>
    <?php else: ?>
        <br><p>Щоб залишити коментар <a href=register>зареєструйтесь</a> або <a href=login>увійдіть</a> в акканут</p>
    <?php endif; ?>
<?php include 'tml/bottom.php'; ?>
