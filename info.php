<?php

$page_title = 'Наша гімназія';
include 'tml/top.php';

?>

<h1 align=center>Документи</h1>
<div class="document-panel">
    <div class="document-nav">
        <input class="document-search" placeholder="Пошук документів">
        <ul class="document-tree" id="tree">
            <?php 
            
                $groups = R::getAll( 'SELECT * FROM docgroups ORDER BY id ASC' );
                $docs = R::getAll( 'SELECT * FROM documents ORDER BY id ASC' );
                for($i = -1; $i <= max(array_keys($groups)); $i++){
                    if(isset($groups[$i])){
                        echo '<li><span class="caret">'.$groups[$i]['name'].'</span><ul class="nested">';
                        for($d = -1; $d <= max(array_keys($docs)); $d++){
                            if(isset($docs[$d]) && $docs[$d]['groupid'] == $groups[$i]['id']){
                                echo '<li onclick=\'initPDFViewer("'.$docs[$d]['path'].'");\'>'.$docs[$d]['name'].'</li>';
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
                    <p style="margin: 5px; margin-right: auto"><?php if(isset($document_name)) echo $document_name; ?></p>
                </div></center>
                <div id="page-mode" style="display:none">
                </div>
            </div>
            <div id="viewport-container"><div role="main" id="viewport"><iframe class=canvas src="http://lesya.org/nasha_gimnazia/metodychna_robota/P_F_index.html"></iframe></div></div>
        </div>
    </div>
</div>

<?php include 'tml/bottom.php'; ?>