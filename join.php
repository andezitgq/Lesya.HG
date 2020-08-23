<?php

    $page_title = 'Вступ';
    include 'tml/top.php';

?>

<div class="lesya-pdf">
    <div role="toolbar" id="toolbar">
        <div id="pager">
            <button data-pager="prev">prev</button>
            <button data-pager="next">next</button>
        </div>
        <div id="page-mode" style="display:none">
            <label>Page Mode <input type="number" value="1" min="1"/></label>
        </div>
    </div>
    <div id="viewport-container"><div role="main" id="viewport"></div></div>
</div>

<?php include 'tml/bottom.php'; ?>