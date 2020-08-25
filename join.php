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
        <h2>hell :)</h2>
        <a href=#>sus</a>
        <p>sas</p>
    </div>
</div>

<?php include 'tml/bottom.php'; ?>