function MyCloud(mtreeId, mdirDetail){
    var t = this;
    var treeId = mtreeId;
    var tree = $('#'+treeId);
    var detailId = mdirDetail;
    var detail = $('#'+detailId);
    var current = '';
    
    this.error = function(message){
	console.log(message);
    };
    
    this.remove = function(cur, type){
	var modal = $('#myModal');
	var fname = (cur.split('/')).pop();
	switch(type){
	    case 'DIRECTORY':
		$('#myModalLabel').text('Supprimer un dossier');
		$('#modal-body').html('La supression du dossier <b>'+fname+'</b> entrainnera la supression de tous les sous répertoires et fichier. êtes vous sûre de vouloir réaliser cette action?');
	    case 'FILE':
		$('#myModalLabel').text('Supprimer un fichier');
		$('#modal-body').html('Vous êtes sur le point de supprimer le fichier <b>'+fname+'</b>.Une fois supprimé il ne pouras pas ètre retrouvé. Etes vous sûre de vouloir réaliser cette action?');
	}
	
	$('#modal-btn-valider').off('click').click(function(){
	    $.ajax({
		type: "POST",
		url: (type=='DIRECTORY')?'File/removeDir.json':'File/removeFile.json',
		data: {current:cur},
		dataType: 'json'
	    }).done(function(json){
		switch(json.tag){
		    case 'directory.error':
			t.error(json.message);
		    break;
		    case 'directory.removed':
			t.getSub(current);
		    break;
		}
		modal.modal('hide');
	    }).fail(t.getSubFail);
	});
	modal.modal('show');
    };
    
    this.addTreeNode = function(node, clean){
	var exists = tree.find('ul[data-name="'+node.path+'"]').length > 0;
	if(!exists)var ul = $('<ul>').attr('data-name', node.path).appendTo(tree);
	var ul = tree.find('ul[data-name="'+node.path+'"]');
	if(clean)ul.find('li').remove();
	var li = $('<li>').addClass('directory').appendTo(ul);
	var img = $('<img>').attr('src', t.getImageForExtention(null)).css('height', '20px');
	$('<a>').attr('data-path', node.path+node.name).text(' ' + node.name).click(function(){t.getSub($(this).attr('data-path'));}).appendTo(li).prepend(img);
	$('<ul>').attr('data-name', node.path+node.name+'/').appendTo(li);
    };
    
    this.download = function(path){
	var frm = $('<form>').hide().attr('method', 'POST').attr('action', 'Download/getFile').appendTo($('body'));
	$('<input type="hidden" name="path">').val(path).appendTo(frm);
	frm.submit();
    };
    
    this.share = function(path){
	var modal = $('#myModal');
	$.ajax({
	    type: "POST",
	    url: 'Sharing/getSharingInformations.json',
	    data: {current:path},
	    dataType: 'json'
	}).done(function(json){
	    switch(json.tag){
		case 'sharing.notShared':
		    $('#myModalLabel').text('Partage de fichier');
		    $('#modal-body').attr('data-mode', 'createShare').html('');
		    $('<form>').appendTo($('#modal-body')).html('<fieldset><legend>Ce fichier n\'est pas partagé</legend><label>Mot de passe</label><input id="password" type="password" placeholder="mot de passe (optionnel)"></fieldset>');
		break;
		case 'sharing.infos':
		    $('#myModalLabel').text('Partage de fichier');
		    $('#modal-body').attr('data-mode', 'editMode').html('');
		    var frm = $('<form>').appendTo($('#modal-body')).html('<fieldset><legend>Ce fichier est partagé</legend><label>Lien</label><input readonly id="myurl" type="text" style="width:90%" value="'+json.url+'">'+((json.purl!=false)?'<label>Player</label><input readonly id="purl" type="text" style="width:90%" value="'+json.purl+'">':'')+'<label>Telechargé</label><input readonly type="text" value="'+json.infos.nbDownload+'"><label>Mot de passe</label><input id="password" type="password" value="'+json.infos.password+'" placeholder="mot de passe (optionnel)"></fieldset>');
		    $('<a class="btn btn-primary">').appendTo(frm).text('Supprimer le partage').click(function(){
			$.ajax({
			    type: "POST",
			    url: 'Sharing/removeShared.json',
			    data: {uid:json.infos.uid},
			    dataType: 'json'
			});
			modal.modal('hide');
		    });
		    $("#myurl,#purl").click(function(){this.select();});
		break;
	    }
	    $('#modal-btn-valider').off('click').click(function(){
		switch($('#modal-body').attr('data-mode')){
		    case 'createShare':
			$.ajax({
			    type: "POST",
			    url: 'Sharing/createShared.json',
			    data: {current:path, password:$('#password').val()},
			    dataType: 'json'
			}).done(function(json){
			    switch(json.tag){
				case 'sharing.createdOk':
				    t.share(path);
				    break;
			    }
			});
			break;
		    case 'editMode':
			$.ajax({
			    type: "POST",
			    url: 'Sharing/editShared.json',
			    data: {uid:json.infos.uid, password:$('#password').val()},
			    dataType: 'json'
			});
			modal.modal('hide');
			break;
		}
	    });
	    modal.modal('show');
	}).fail(t.getSubFail);
    };
	
	this.getImageForExtention = function(ext){
		if(ext=='' || ext == null)ext='none';
		ext = ext.toLowerCase();
		if(iconSet[ext] != null)
			return iconSet[ext];
		if(iconSet['default'] != null)
			return iconSet['default'];
		return null;
	}
    
    this.listAllFilesAndDir = function(node){
	detail.find('table').remove();
	var table = $('<table>').addClass('table').addClass('table-striped').appendTo(detail);
	var tr = $('<tr>').appendTo(table);
	$('<th>').text('Nom').appendTo(tr).attr('colspan', 2);
	$('<th>').text('Taille').appendTo(tr);
	$('<th>').text('Modifié le').appendTo(tr);
	$('<th>').text('Actions').appendTo(tr);
	
	var once = true;
	for(var elm in node.content){
	    elm = node.content[elm];
	    if(elm.type == 'DIRECTORY'){
		t.addTreeNode(elm, once);
		once=false;
	    }
	    var tr = $('<tr>').attr('data-type', elm.type).appendTo(table);
	    var lnk = $('<a>').text(elm.name).attr('data-path', elm.path+elm.name);
	    switch(elm.type){
		case 'DIRECTORY':
		    lnk.click(function(){t.getSub($(this).attr('data-path'));});
		    break;
		case 'FILE':
		    lnk.click(function(){t.download($(this).attr('data-path'));});
		    break;
	    }
	    var self = this;
	    var icon = $('<img>').attr('src', t.getImageForExtention(elm.ext)).css('height', '20px');
	    $('<td>').appendTo(tr).append(icon);
	    $('<td>').appendTo(tr).append(lnk);
	    $('<td>').text((elm.type == 'DIRECTORY')?'':elm.readableSize).appendTo(tr);
	    $('<td>').text(elm.lastedit).appendTo(tr);
	    var action = $('<div>').addClass('btn-group').appendTo($('<td>').appendTo(tr));
	    $('<a>').attr('data-path', elm.path+elm.name).attr('data-type', elm.type).click(function(){t.remove($(this).attr('data-path'), $(this).attr('data-type'));}).html('<i class="icon-trash icon-white"></i> Supprimer').addClass('btn').addClass('btn-danger').addClass('btn-mini').appendTo(action);
	    if(elm.type == 'FILE')$('<a>').attr('data-path', elm.path+elm.name).click(function(){t.share($(this).attr('data-path'));}).html('<i class="icon-share icon-white"></i> Partager').addClass('btn').addClass('btn-info').addClass('btn-mini').appendTo(action);
	    if(elm.type == 'FILE' && this.playable(elm.ext))$('<a>').attr('data-name', elm.name).attr('data-path', elm.path+elm.name).html('<i class="icon-play icon-white"></i> Ajouter à la playlist').addClass('btn').addClass('btn-primary').addClass('btn-mini').appendTo(action).click(function(){self.addPlayList('streamp/'+escape($(this).attr('data-path').replace('/', '@_@')), $(this).attr('data-name'));});
	
	}
    };
    
    this.addPlayList=function(url, name){
	console.log(url);
    };
    
    this.playable = function(ext){
	var playable = ['mp3', 'ogg', 'wav', 'mp4', 'mpg', 'mpeg', 'avi', 'webm', 'ogv', 'm4v']
	return $.inArray(ext, playable)>-1;
    };
    
    this.getSubDone = function(json){
	switch(json.tag){
	    case 'directory.error':
		t.error(json.message);
	    break;
	    case 'directory.content':
		t.listAllFilesAndDir(json);
	    break;
	}
    };
    
    this.getSubFail = function(xhr, message){
	t.error(message);
    };
    
    this.getSub = function(mbase){
	current = mbase;
	$('body').attr('data-path', current);
	$.ajax({
	    type: "POST",
	    url: 'File/ls.json',
	    data: {current:mbase},
	    dataType: 'json'
	}).done(t.getSubDone).fail(t.getSubFail);
    };
    
    this.createDirectory = function(){
	var modal = $('#myModal');
	$('#myModalLabel').text('Créer un nouveau dossier');
	$('#modal-body').html('<div class="form-horizontal"><div class="control-group"><label class="control-label" for="inputFileName">Nom du dossier</label><div class="controls"><input type="text" id="inputFileName" placeholder="nouveau dossier"></div></div></div>');
	$('#modal-btn-valider').off('click').click(function(){
	    $.ajax({
		type: "POST",
		url: 'File/createDir.json',
		data: {current:current, name:$('#inputFileName').val()},
		dataType: 'json'
	    }).done(function(json){
		switch(json.tag){
		    case 'directory.error':
			t.error(json.message);
		    break;
		    case 'directory.created':
			t.getSub(current);
		    break;
		}
		modal.modal('hide');
	    }).fail(t.getSubFail);
	});
	modal.modal('show');
    };
    
    this.goParent = function(){
	if(current!=''){
	    var parts = current.split('/');
	    parts.pop();
	    t.getSub(parts.join('/'));
	}
    };
    
    this.init = function(){
	$('#add_directory').click(function(){t.createDirectory();});
	$('#go_parent').click(function(){t.goParent();});
	var root = $('<ul>').addClass('root').appendTo(tree);
	var li = $('<li>').appendTo(root);
	$('<a>').text('/').appendTo(li).click(function(){t.getSub();});
	$('<ul>').appendTo(li).attr('data-name', '');
	t.getSub();
    };
    
    this.init();
}