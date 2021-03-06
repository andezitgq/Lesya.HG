<?php

$page_title = 'Наша гімназія';
include 'tml/top.php';

?>

<h1 align=center>Документи</h1>
<div class="document-panel">
    <div class="document-nav">
        <ul class="document-tree" id="tree">
            <?php 
            
                $groups = R::getAll( 'SELECT * FROM docgroups ORDER BY id ASC' );
                $docs = R::getAll( 'SELECT * FROM documents ORDER BY id ASC' );
                for($i = -1; $i <= max(array_keys($groups)); $i++){
                    if(isset($groups[$i])){
                        echo '<li><span class="caret">'.$groups[$i]['name'].'</span><ul class="nested">';
                        for($d = -1; $d <= max(array_keys($docs)); $d++){
                            if(isset($docs[$d]) && $docs[$d]['groupid'] == $groups[$i]['id']){
                                echo '<li onclick=\'infoSetIFrame("'.$docs[$d]['path'].'", "'.$docs[$d]['name'].'");\'>'.$docs[$d]['name'].'</li>';
                            }
                        } 
                        echo '</ul></li>';
                    }
                }
            ?>
        </ul> 
        <div class="lds-ellipsis" style="display: none"><div></div><div></div><div></div><div></div></div>
    </div>
    <div class="document-view">
        <div class="lesya-pdf" style="width: 100%">
            <div role="toolbar" id="toolbar">
                <center><div id="pager">
                    <p style="margin: 5px; margin-right: auto" id=dname></p>
                </div></center>
                <div id="page-mode" style="display:none">
                </div>
            </div>
            <div id="viewport-container">
                <div role="main" id="viewport">
                    <iframe class=canvas id=canvas src="https://drive.google.com/file/d/1wWS2SV9O_6WjcnthrWnfuejteVf8DJqN/preview"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 

$add_bottom = '<script>'.
                'function infoSetIFrame(path, name){
                    document.getElementById("canvas").src = path;
                    document.getElementById("dname").innerHTML = name;
                }'.
              '</script>';
include 'tml/bottom.php';

?>