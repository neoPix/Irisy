<!DOCTYPE html>
<html>
    <head>
	<title>Irisy</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php echo $this->Html->style('bootstrap.min.css');?>
	<?php echo $this->Html->style('MyCloud.css');?>
	<style type="text/css">
	body {
	  padding-top: 40px;
	  padding-bottom: 40px;
	  background-color: #f5f5f5;
	}

	.form-signin {
	  max-width: 50%;
	  padding: 19px 29px 29px;
	  margin: 0 auto 20px;
	  background-color: #fff;
	  border: 1px solid #e5e5e5;
	  -webkit-border-radius: 5px;
	     -moz-border-radius: 5px;
		  border-radius: 5px;
	  -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
	     -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
		  box-shadow: 0 1px 2px rgba(0,0,0,.05);
	}
	.form-signin .form-signin-heading,
	.form-signin .checkbox {
	  margin-bottom: 10px;
	}
	.form-signin input[type="text"],
	.form-signin input[type="password"] {
	  font-size: 16px;
	  height: auto;
	  margin-bottom: 15px;
	  padding: 7px 9px;
	}
      </style>
    </head>
    <body>
	<?php echo $this->get('content_for_layout');?>
    	<?php echo $this->Html->script('jQuery.js');?>
	<?php echo $this->Html->script('bootstrap.min.js');?>
	<script type="text/javascript">
	    $(document).ready(function(){
		$('button.btn-primary').click(function(){
		    var frm = $('<form>').appendTo($('body')).attr('action', action+uid+'/'+$('#password').val()).attr('method', 'GET');
		    frm.submit();
		    return false;
		});
	    });
	</script>
    </body>
</html>