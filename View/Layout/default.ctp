<!DOCTYPE html>
<html>
    <head>
	<title>Irisy</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php echo $this->Html->style('bootstrap.min.css');?>
	<?php echo $this->Html->style('MyCloud.css');?>
	<style type="text/css">
	body {
	  padding-top: 60px;
	  padding-bottom: 40px;
	}
	.sidebar-nav {
	  padding: 9px 0;
	}

	@media (max-width: 980px) {
	  .navbar-text.pull-right {
	    float: none;
	    padding-left: 5px;
	    padding-right: 5px;
	  }
	}
      </style>
    </head>
    <body>
	<div class="navbar navbar-inverse navbar-fixed-top">
	    <div class="navbar-inner">
		    <div class="container-fluid">
		    <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		    </button>
		    <a class="brand" href="#">Irisy</a>
		    <div class="nav-collapse collapse">
			<p class="navbar-text pull-right">
			    Connecté en tant que <?php echo Session::read('User.user.name');?> <a href="<?php echo Rooter::url('/');?>User/logout">Se déconnecter</a>
			</p>
		    </div>
		</div>
	    </div>
	</div>
	<div class="container-fluid">
	    <div class="row-fluid">
		<div class="span3">
		    <div class="well" id="tree_file_list">
		    </div>
		</div>
		<div class="span9">
		    <div class="well" id="directory_content">
			<div class="btn-group">
			    <a class="btn btn-inverse" id="go_parent"><i class="icon-arrow-left icon-white"></i> Dossier parent</a>
			    <a class="btn btn-info" id="add_directory"><i class="icon-plus icon-white"></i> Ajouter un dossier</a>
			    <?php echo $this->get('content_for_layout');?>
			</div>
		    </div>
		</div>
	    </div>
	</div>
	<div class="well" id="dlmanager">
	    <ul id="upload"></ul>
	    <span id="notDoneYet"></span>
	</div>
	<div id="btn-dlmanager">
	    <a href='#'>Envois de fichier</a>
	</div>
	<div class="well" id="playlist">
	    <ul id="list"></ul>
	    <span id="notDoneYet"></span>
	</div>
	<div id="btn-playlist">
	    <a href='#'>PlayList</a>
	</div>
	<?php echo $this->Html->script('jQuery.js');?>
	<?php echo $this->Html->script('bootstrap.min.js');?>
	<?php echo $this->Html->script('MyCloud.Icons.js');?>
	<?php echo $this->Html->script('MyCloud.js');?>
	<?php echo $this->Html->script('Uploader.js');?>
	<?php echo $this->Html->script('Upload.js');?>
	<?php echo $this->Html->script('mediaPlayer.js');?>
	<?php echo $this->Html->script('page.js');?>
	<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	    <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">Modal header</h3>
	    </div>
	    <div class="modal-body" id="modal-body">
		<p>One fine body…</p>
	    </div>
	    <div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">Annuler</button>
		<button class="btn btn-primary" id="modal-btn-valider">Valider</button>
	    </div>
	</div>
    </body>
</html>