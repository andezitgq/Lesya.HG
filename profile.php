<?php

    $page_title = 'Профіль';
    include 'tml/top.php';
    
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
    
    if(!isset($_SESSION['logged-user']) ||
       $_SESSION['logged-user']->login == 'root')
            echo '<script>window.location.href = "login";</script>';
            
    if(isset($_GET['id'])){
        if($_GET['id'] == 0 || $_GET['id'] == '')
            echo '<script>window.location.href = "profile";</script>';
        
        $user_is_you = false;
    } else {
        $user_is_you = true;
    }
?>
<?php if ($user_is_you == true): ?>
    <center><h1>Профіль</h1></center>
    <div class=profile>
        <div class=profile-preview>
            <form enctype="multipart/form-data" action=profile method=POST class=avatar style="background: url('img/profile.svg'); background-size: cover">
                <input type=file name=avatar-file class=avatar-file>
            </form>
            <h2 class=fullname title="Імʼя та прізвище">Sas Sasovich</h3>
            <h3 class=nickname title="Нікнейм">freelogger</h3>
        </div>
        <div class=profile-info>
            <div class=profile-nav>
                <button>1</button>
                <button>1</button>
                <button>1</button>
                <button>1</button>
            </div>
        </div>
    </div>
<?php else: ?>
    SAS
<?php endif; ?>

<?php include 'tml/bottom.php'; ?>