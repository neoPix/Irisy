<div class="container">
    <form class="form-signin">
	<h2 class="form-signin-heading">Veuillez entrer le mot de passe</h2>
	<input type="password" id="password" class="input-block-level" placeholder="Mot de passe">
	<button class="btn btn-large btn-primary" type="submit">Télécharger</button>
    </form>
</div>
<script type="text/javascript">
    var uid = '<?php echo $this->get('uid');?>';
	var action = '<?php echo $this->get('action');?>';
</script>