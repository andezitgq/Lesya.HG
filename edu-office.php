<?php

    $page_title = 'Виховна служба';
    include 'tml/top.php';

?>

<div>
    <div class="tab">
        <button class="tablinks" onclick="openTab(event, 'Info')" id="defaultOpen">Інфо</button>
        <button class="tablinks" onclick="openTab(event, 'Comments')">Коментарі</button>
        <button class="tablinks" onclick="openTab(event, 'Account')">Аккаунт</button>
    </div>
      
    <form method=POST action=profile id="Info" class="tabcontent">
        <h2>Інфо</h2><hr><br>
        <p class=icon-statusnet>Статус: <?php echo $uinfo->actype; ?></p>
        <p class=icon-commenting>Коментарі: <?php echo $uinfo->comments; ?></p>
        <p class=icon-rocket>Дата реєстрації: <?php echo $uinfo->regdate; ?></p>
        <p class=icon-text-width>Про мене: <br><br>
            <span><i class=icon-quote-left></i><input style="border:none; border-bottom: 2px solid #1b466f" required name=change-uinfo type=text value="<?php echo $uinfo->aboutme; ?>"><i class=icon-quote-right></i></span>
        </p>
    </form>
      
    <div id="Comments" class="tabcontent">
        <h2>Коментарі</h2><hr><br>
    </div>
      
    <div id="Account" class="tabcontent">
        <h2>Управління аккаунтом</h2><hr><br>
        <a href="?unset-session">Вийти з аккаунту</a>
    </div>
</div>

<?php include 'tml/bottom.php'; ?>