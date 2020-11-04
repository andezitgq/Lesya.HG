<?php

$page_title = 'Наша гімназія';
include 'tml/top.php';

?>

<h1 align=center>Документи</h1>
<div class="document-panel">
    <div class="document-nav">
        <input class="document-search" placeholder="Пошук документів">
        <ul class="document-tree" id="tree">
            <li>
                <span class="caret">Beverages</span>
                <ul class="nested">
                    <li>
                        <span class="caret">Tea</span>
                        <ul class="nested">
                            <li>
                                <span class="caret">Green Tea</span>
                                <ul class="nested">
                                    <li>Sencha</li>
                                    <li>Gyokuro</li>
                                    <li>Matcha</li>
                                    <li>Pi Lo Chun</li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <?php 
            
                $groups = R::getAll( 'SELECT * FROM docgroups ORDER BY id ASC' );
                $docs = R::getAll( 'SELECT * FROM documents ORDER BY id ASC' );
                for($i = -1; $i <= max(array_keys($groups)); $i++){
                    if(isset($groups[$i])){
                        echo '<li><span class="caret">'.$groups[$i]['name'].'</span><ul class="nested">';
                        for($d = -1; $d <= max(array_keys($docs)); $d++){
                            if(isset($docs[$d]) && $docs[$d]['groupid'] == $groups[$i]['id']){
                                echo '<li>'.$docs[$d]['name'].'</li>';
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
<button onclick='initPDFViewer("img/pdf-test.pdf");'>123</button>

<?php include 'tml/bottom.php'; ?>