<?php
	/************************************************************************************
	 * Balan David on Edely. All Rights Reserved. 										*
	 * Balan David (http://www.balandavid.com) ImgHelper.                   			*
	/***********************************************************************************/
	class ImgHelper{
		public static $quality = 90;

		/**
		 * Redimentionne une image, crop si les dimentions de sont pas normés
		**/
		function resize($img,$dest,$largeur=0,$hauteur=0){
			if($this->gd_loaded())
			{
			    $dimension=getimagesize($img);
			    $ratio = $dimension[0] / $dimension[1];
			    // Création des miniatures
			   	if($largeur==0 && $hauteur==0){ $largeur = $dimension[0]; $hauteur = $dimension[1]; }
			    else if($hauteur==0){ $hauteur = round($largeur / $ratio); }
			    else if($largeur==0){ $largeur = round($hauteur * $ratio); }
			 
			    if($dimension[0]>($largeur/$hauteur)*$dimension[1] ){ $dimY=$hauteur; $dimX=round($hauteur*$dimension[0]/$dimension[1]); $decalX=($dimX-$largeur)/2; $decalY=0;}
			    if($dimension[0]<($largeur/$hauteur)*$dimension[1]){ $dimX=$largeur; $dimY=round($largeur*$dimension[1]/$dimension[0]); $decalY=($dimY-$hauteur)/2; $decalX=0;}
			   	if($dimension[0]==($largeur/$hauteur)*$dimension[1]){ $dimX=$largeur; $dimY=$hauteur; $decalX=0; $decalY=0;}
			    
			    $miniature =imagecreatetruecolor ($largeur,$hauteur);
				$ext = @end(explode('.',$img)); 
			    if(in_array($ext,array('jpeg','jpg','JPG','JPEG'))){$image = imagecreatefromjpeg($img); }
			    elseif(in_array($ext,array('png','PNG'))){$image = imagecreatefrompng($img); }
			    elseif(in_array($ext,array('gif','GIF'))){$image = imagecreatefromgif($img); }
			    else{ return false; }
			    imagecopyresampled($miniature,$image,-$decalX,-$decalY,0,0,$dimX,$dimY,$dimension[0],$dimension[1]);
			    imagejpeg($miniature,$dest,self::$quality);
			    return true;
			}
			return false;
		}

		/**
		 * Renviois la disponnibilitée ou non de GD
		**/
		function gd_loaded()
		{
			return (extension_loaded('gd') && function_exists('gd_info'));
		}
	}