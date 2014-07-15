function uploadManager(dropZone){
    var t = this;
    
    this.pendding = new Array();
    this.current = null;
    this.done = new Array();
    
    this.uploadFinishedEvent = function(){
	
    };
    
    this.currentGotId = function(id){
	var fname = t.current.uploadedFile.name;
	var li = $('<li>').attr('data-id-upload', id).html('<div class="progress"><div class="bar bar-success"></div><div class="bar bar-info" style="width: 0%;"></div><div class="progress-text">'+fname+'</div></div>');
	$('#upload').prepend(li);
	$.ajax({
	    type : 'POST',
	    url : 'Upload/setFinalPath',
	    data : {uploadid:id, destination:$('body').attr('data-path')}
	});
    };
    
    this.currentProgressChanged = function(part, all, id){
	var info = $('#upload').find('li[data-id-upload*="'+id+'"]').find('.bar-info');
	info.css('width', ((part/all)*100)+'%');
    };
    
    this.uploadFinished = function(id){
	var info = $('#upload').find('li[data-id-upload*="'+id+'"]').find('.bar-info');
	info.css('width', '100%');
    };
    
    this.uploadAssembling = function(id, percent){
	var info = $('#upload').find('li[data-id-upload*="'+id+'"]').find('.bar-success');
	if(parseInt(percent) == 100){
	    t.done.push(t.current);
	    t.current = null;
	    t.uploadFinishedEvent();
	    t.treatPendingList();
	}
	info.css('width', percent+'%');
	var info = $('#upload').find('li[data-id-upload*="'+id+'"]').find('.bar-info');
	info.css('width', ((100-percent)+'%'));
    };
    
    this.treatPendingList = function(){
	if(t.current == null && t.pendding.length > 0 ){
	    var elm = t.pendding.pop();
	    $('#notDoneYet').text(t.pendding.length+' fichiers restant');
	    t.current = new Upload(elm);
	    
	    t.current.idSet = function(id){t.currentGotId(id);};
	    t.current.progressUpdateChanged = function(currentpart, all, id){t.currentProgressChanged(currentpart, all, id);};
	    t.current.updateOver=function(id){t.uploadFinished(id);};
	    t.current.assemblageStateChanged = function(id, percent){t.uploadAssembling(id, percent);};
	    t.current.start();
	}
    };
    
    this.handleDragOver = function(evt){
	evt.stopPropagation();
	evt.preventDefault();
	evt.dataTransfer.dropEffect = 'copy';
    };
    
    this.handleFileSelected = function(evt){
	evt.stopPropagation();
	evt.preventDefault();
	if(!$('#dlmanager').is(':visible'))$('#dlmanager').show();
	var files = evt.dataTransfer.files;
	for (var i = 0, f; f = files[i]; i++){
	    t.pendding.push(f);
	}
	t.treatPendingList();
    };
    
    this.bind = function(dropZone){
	var dropzone = document.getElementById(dropZone);
	dropzone.addEventListener('dragover',  function(evt){t.handleDragOver(evt);}, false);
	dropzone.addEventListener('drop', function(evt){t.handleFileSelected(evt);}, false);
    };
}

