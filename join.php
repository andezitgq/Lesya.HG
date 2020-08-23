<?php

    $page_title = 'Вступ';
    include 'tml/top.php';

?>

<!--<object data="img/Вступ.pdf" type="application/pdf">
        <embed src="img/Вступ.pdf" type="application/pdf" />
</object>-->
<div id="my_pdf_viewer">
    <div id="zoom_controls">  
        <button id="zoom_in">+</button>
        <button id="zoom_out">-</button>
    </div>
    <div id="canvas_container">
        <canvas id="pdf_renderer"></canvas>
    </div>
    <div id="navigation_controls">
        <button id="go_previous">Previous</button>
        <input id="current_page" value=1 min=1 type="number"/>
        <button id="go_next">Next</button>
    </div>
</div>

<?php include 'tml/bottom.php'; ?>