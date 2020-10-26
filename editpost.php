<?php

    $page_title = 'Редактор постів';
    include 'tml/top.php';

    $test_post = R::findOne('post', 'id = ?', array($_GET['postid']));
    if(!isset($_GET['postid']) ||
       !isset($test_post)      ||
       $_GET['postid'] == ''   ||
       $_GET['postid'] == 0    ||
       $_SESSION['logged-user']->login != 'root')
            echo '<script>window.location.href = "/";</script>';

?>

<form method=GET action="editpost?postid=<?php echo $_GET['postid']; ?>" class="postedit">
    <textarea name="post-field" id="post-field" class="post-edit-field">
        <?php echo $test_post->content; ?>
    </textarea>
</form>

<?php include 'tml/bottom.php'; ?>