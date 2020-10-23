<?php

$page_title = 'Наша гімназія';
include 'tml/top.php';

?>

<h1 align=center>Документи</h1>
<div class="document-panel">
    <div class="document-nav">
        <input class="document-search" placeholder="Пошук документів">
        <div class="document-tree">
            list
        </div>
    </div>
    <div class="document-view">
        <div class="lesya-pdf" style="width: 100%">
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
    </div>
</div>

<?php include 'tml/bottom.php'; ?>