/**
 * Aderesse des web services appelés
**/
var uploadVars = {
    openUpload : 'Upload/openUpload.json', // Ouverture d'une session d'upload
    uploadManager : 'Upload/uploadChunk.json', // Envoi d'un bout de fichier
    assembleParts : 'Upload/assemble.json', // Assembler les parties envoyés
    assembleState : 'Upload/assembleState.json' // Etat de l'assemblage
}
/**
 * Récupère un objet XMLHttp
**/
function getXmlHttp()
{
	if (window.XMLHttpRequest)
		return new XMLHttpRequest();
	else
		return new ActiveXObject("Microsoft.XMLHTTP");
}
/**
 * Permet de réaliser un post en AJAX
**/
function post(url, params, callbackdone, callbackfail)
{
	var xmlhttp = getXmlHttp();
	xmlhttp.onreadystatechange=function(){
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
			callbackdone(xmlhttp);
		else if(xmlhttp.readyState==4 && xmlhttp.status!=200)
			callbackfail(xmlhttp);
	};
	xmlhttp.open("POST",url,true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send(params);
}
/**
 * Réaliser un post avec des datas
**/
function postdatas(url, params, callbackdone, callbackfail, callbackprogress)
{
    var xmlhttp = getXmlHttp();
    if(callbackprogress!=null)xmlhttp.addEventListener("progress", callbackprogress, false);
    xmlhttp.onreadystatechange=function(){
	if (xmlhttp.readyState==4 && xmlhttp.status==200)
	    callbackdone(xmlhttp);
	else if(xmlhttp.readyState==4 && xmlhttp.status!=200)
	    callbackfail(xmlhttp);
    };
    xmlhttp.open("POST",url,true);
    xmlhttp.send(params);
}

/**
 * Classe d'upload
**/
function Upload(file){
	var uploadedFile, aborted, started, currentpart, reader, that, jsonStart, assembleInterval, miniprogress;
	/**
	 * Démarage de l'upload
	**/
	this.start = function()
	{
		this.started = true;
		post(uploadVars.openUpload,
			'&filesize='+this.uploadedFile.size+'&filename='+this.uploadedFile.name,
			function(x){
				var json = JSON.parse(x.responseText);
				that.startDone(json);
			},
			function(x){
				that.startFailed(x);
			}
		);
	};
	/**
	 * Echec de l'ouverture de la session d'upload
	**/
	this.startFailed = function(xmlhttp)
	{
	};
	/**
	 * Démarage de l'upload
	**/
	this.startDone = function(json)
	{
		this.jsonStart = json;
		this.idSet(this.jsonStart.id);
		this.uploadPart(1);
	};
	/**
	 * Upload d'une partie du fichier
	**/
	this.uploadPart = function(numpart)
	{
		this.currentpart = numpart;
		var begin = (numpart-1)*this.jsonStart.partsize;
		var end = begin + this.jsonStart.partsize;
		if(end > this.uploadedFile.size) end = this.uploadedFile.size;
		var blob = this.uploadedFile.slice(begin, end);
		
		var formPost = new FormData();
		formPost.append("uploadid", this.jsonStart.id);
		formPost.append("part", numpart);
		formPost.append("blob", blob);
		
		that.progressUpdateChanged(this.currentpart + that.miniprogress,this.jsonStart.parts,that.jsonStart.id);
		postdatas(uploadVars.uploadManager,
			formPost,
			function(x){
				that.miniprogress = 0;
				if(numpart < that.jsonStart.parts)
					that.uploadPart(numpart+1);
				else
				{
					//the process is over assemble the file
					post(uploadVars.assembleParts,
						'&uploadid='+that.jsonStart.id,
						function(x){
							that.uploadOver(that.jsonStart.id);
						},
						function(x){
						
						}
					);
					that.getAssemblageState();
				}
			},
			function(x){
				that.miniprogress = 0;
				that.uploadPart(numpart);
			},
			function(evt){
				that.miniprogress = evt.loaded / evt.total;
				that.progressUpdateChanged(that.currentpart + that.miniprogress,that.jsonStart.parts,that.jsonStart.id);
			}
		);
		
		this.reader.readAsBinaryString(blob);
	};
	/**
	 * Récupérer l'état de l'assemblage final
	**/
	this.getAssemblageState = function()
	{
		post(uploadVars.assembleState,
			'&uploadid='+that.jsonStart.id,
			function(x){
				var percent = parseInt(x.responseText);
				that.assemblageStateChanged(that.jsonStart.id, percent);
				if(that.assembleInterval!=null)window.clearInterval(that.assembleInterval);
				if(percent < 100)
					that.assembleInterval = setInterval(function(){that.getAssemblageState();},250);
			},
			function(x){
			
			}
		);
	};
	/**
	 * L'état de l'upload a changé
	**/
	this.progressUpdateChanged=function(currentpart, all, id)
	{
		console.log(this.currentpart/this.jsonStart.parts);
	};
	/**
	 * Event de fin d'upload
	**/
	this.uploadOver=function(id)
	{
	};
	/**
	 * Event de récupération de l'id de session d'upload
	**/
	this.idSet = function(id)
	{
	};
	/**
	 * Event de modification de l'état d'assemblage
	**/
	this.assemblageStateChanged = function(id, percent)
	{
	};
	
	that = this;
	this.uploadedFile = file;
	this.started = false;
	this.aborted = false;
	this.state = 0;
	this.reader = new FileReader();
	this.assembleInterval=null;
	this.miniprogress = 0;
}