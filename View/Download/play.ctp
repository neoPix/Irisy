<div class="container">
	<h1><?php echo $this->get('file');?></h1>
	<?php 
		switch($this->get('ext'))
		{
			case 'mp3':
			case 'wav':
			case 'ogg':
				echo '<audio style="width:100%" src="'.Rooter::url('/').'stream/'.$this->get('uid').'/'.$this->get('password').'" controls><p>'. __('Can\'t read this media').'</p></audio>';
				break;
			case 'mp4':
			case 'mpg':
			case 'mpeg':
			case 'avi':
			case 'wmv':
			case 'webm':
			case 'ogv':
			case 'm4v':
				echo '<video width="100%" height="660" preload="auto" data-setup="{}" class="video-js vjs-default-skin" src="'.Rooter::url('/').'stream/'.$this->get('uid').'/'.$this->get('password').'" controls><p>'. __('Can\'t read this media').'</p></video>';
				break;
			default:
				echo '<p>'.__('Can\'t read this media').'</p>';
		}
	?>
</div>