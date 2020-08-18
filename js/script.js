$('document').ready(function(){

    $('.nav-mob').click(function(){
        $('.full-nav').toggle('slow'); 
    });
    $('.full-nav').click(function(){
        $('.full-nav').toggle('slow'); 
    });
    
    $('.soc-nav-button').click(function(){
        $('.social-nav').toggle('slow');    
    });
    
    $('.edit-btn').on('click', function() {
        var cursorPos = $('.post-editor').prop('selectionStart');
        var v = $('.post-editor').val();
        var textBefore = v.substring(0,  cursorPos);
        var textAfter  = v.substring(cursorPos, v.length);
    
        $('.post-editor').val(textBefore + $(this).val() + textAfter);
    });
    
    function setSel(sep1, sep2){
        var txtarea = document.getElementById("post-editor");
        
        var start = txtarea.selectionStart;
        var finish = txtarea.selectionEnd;
        var allText = txtarea.value;
    
        var sel = allText.substring(start, finish);
        var newText=allText.substring(0, start)+sep1+sel+sep2+allText.substring(finish, allText.length);
    
        txtarea.value=newText;    
    }
    
    function setList(){
        console.log('test');
        var txtarea = document.getElementById("post-editor");
        
        var start = txtarea.selectionStart;
        var finish = txtarea.selectionEnd;
        var allText = txtarea.value;
    
        var sel = allText.substring(start, finish).split('\n').join('\n* ');
        var newText=allText.substring(0, start)+"* "+sel+"\n"+allText.substring(finish, allText.length);
    
        txtarea.value=newText;    
    }
    
    $('#bold').on('click', function(){
        setSel("**", "**");
    });
    
    $('#italic').on('click', function(){
        setSel("_", "_");
    });
    
    $('#underlined').on('click', function(){
        setSel("<ins>", "</ins>");
    });
    
    $('#intersected').on('click', function(){
        setSel("~~", "~~");
    });
    
    $('#list-btn').on('click', function(){
        setList();
    });
    
    $('.icon-cancel-circled').on('click', function(){
        document.getElementById("post-editor").value = null;
    });
    
    function removeLocationHash(){
        var noHashURL = window.location.href.replace(/#.*$/, '');
        window.history.replaceState('', document.title, noHashURL); 
    }
    window.addEventListener("popstate", function(event){
        removeLocationHash();
    });
    window.addEventListener("hashchange", function(event){
        event.preventDefault();
        removeLocationHash();
    });
    window.addEventListener("load", function(){
        removeLocationHash();
    });
        
});