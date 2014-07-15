function mediaPlayer(regionId){
    this._playlistId = $('#'+regionId);
    this.initialize();
}

mediaPlayer.prototype = {
    _playlist : [],
    _playlistId : null,
    _audio:null,
    _video:null,
    _current:0,
    
    add:function(url, name){
	var pos = $.inArray(url, this._playlist);
	this._playlistId.fadeIn();
	var self = this;
	if(pos<0){
	    this._playlist.push(url);
	    $('<li>').attr('attr-pos', this._playlist.length - 1).text(name).appendTo(this._playlistId.children('#list')).click(function(){
		self.play($(this).attr('attr-pos'), true);
	    });
	    this.play();
	}
	else
	    this.play(pos, true);
    },
    
    play:function(elm, force){
	if(!this.isPlaying() || force){
	    var url=null;
	    if(!elm)elm=0;
	    this._current=elm;
	    url = this._playlist[elm];
	    
	    //todo repasser ici
	    this._playlistId.find('#list li.playing').removeClass('playing');
	    this._playlistId.find('#list li[attr-pos='+elm+']').addClass('playing');
	    
	    if(url){
		var ext = unescape(url).split('.').pop();
		if(this.isVideo(ext)){
		    this._video.attr('src', url).show();
		    this._audio.hide();
		    this._video[0].load();
		    this._video[0].play();
		    this._audio[0].pause();
		    this._audio.removeAttr('src', '');
		}
		else if(this.isAudio(ext)){
		    this._audio.attr('src', url).show();
		    this._video.hide();
		    this._audio[0].load();
		    this._audio[0].play();
		    this._video[0].pause();
		    this._video.removeAttr('src', '');
		}
	    }
	}
    },
	    
    isVideo:function(ext){
	var playable = ['mp4', 'mpg', 'mpeg', 'avi', 'webm', 'ogv', 'm4v'];
	return $.inArray(ext, playable)>-1;
    },
	    
    isAudio:function(ext){
	var playable = ['mp3', 'ogg', 'wav'];
	return $.inArray(ext, playable)>-1;
    },
	    
    isPlaying:function(){
	return (this._audio[0].duration > 0 && !this._audio[0].paused) || (this._video[0].duration > 0 && !this._video[0].paused);
    },
    
    next:function(){
	this._current++;
	this.play(this._current, true);
    },
	    
    initialize:function(){
	var self = this;
	this._audio = $('<audio>').appendTo(this._playlistId).attr('controls', true).hide();
	this._video = $('<video>').appendTo(this._playlistId).attr('controls', true).hide();
	
	this._audio[0].addEventListener('ended', function(){self.next();});
	this._video[0].addEventListener('ended', function(){self.next();});
    }
};