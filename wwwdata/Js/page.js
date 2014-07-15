$(document).ready(function(){
    var medias = new mediaPlayer('playlist');
    
    var cloud = new MyCloud('tree_file_list', 'directory_content');
    cloud.addPlayList = function(url, name){
	medias.add(url, name);
    };
    
    var upload = new uploadManager();
    upload.bind('directory_content');
    upload.bind('dlmanager');
    upload.uploadFinishedEvent = function(){
	cloud.getSub($('body').attr('data-path'));
    };
    
    $('#btn-dlmanager').click(function(){
	if($('#dlmanager').is(':visible')){
	    $('#dlmanager').hide();
	}
	else{
	    $('#dlmanager').show();
	}
    });
    
    $('#btn-playlist').click(function(){
	if($('#playlist').is(':visible')){
	    $('#playlist').hide();
	}
	else{
	    $('#playlist').show();
	}
    });
});