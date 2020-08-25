<?php

    $page_title = 'Вступ';
    include 'tml/top.php';

?>

<div class=join-content>
    <div class="lesya-pdf">
        <div role="toolbar" id="toolbar">
            <center><div id="pager">
                <p>Правила вступу до гімназії</p>
                <button class=icon-left-open data-pager="prev"></button>
                <button class=icon-right-open data-pager="next"></button>
                <button class=icon-download onclick="download('img/Вступ.pdf', 'Вступ до гімназії.pdf')"></button>
            </div></center>
            <div id="page-mode" style="display:none">
                <label>Page Mode <input type="number" value="1" min="1"/></label>
            </div>
        </div>
        <div id="viewport-container"><div role="main" id="viewport"></div></div>
    </div>
    <div class=join-info-box>
        <h1>Вступ до гімназії</h1>
        <h2>Обов'язкові документи</h2>
        <ol>
            <li>Копія свідоцтва про народження дитини або документа, що посвідчує її особу</li>
            <li>Оригінал або копія медичної довідки</li>
            <li>Оригінал або копія відповідного документа про освіту (за наявності)</li>
        </ol>
        <h2>Посилання</h2>
        <ul>
            <li><a href="https://zakon.rada.gov.ua/laws/show/z0416-17#Text">Екстернат</a></li>
            <li><a href="https://zakon.rada.gov.ua/laws/show/z0184-16#Text">Індивідуальне навчання</a></li>
        <a href=#>sus</a>
        <p>sas</p>
    </div>
</div>

<?php include 'tml/bottom.php'; ?>