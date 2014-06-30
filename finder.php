<form method="get">
<label for="s">What</label>
<input type="text" name="s" id="s" value="<?php echo htmlspecialchars($_REQUEST['s'])?>"><br>
<label for="mask">Mask</label>
<input type="text" name="mask" id="mask" value="<?php echo isset($_REQUEST['mask'])?htmlspecialchars($_REQUEST['mask']):'.php$'?>"><br>
<input type="submit" name="send" value="Send">
</form>
<?php
if( isset($_REQUEST['s']) and !empty($_REQUEST['s']) ){
	function read_Dir( $dir,$start ){
		if( is_dir($dir) ){
			$dir = realpath($dir).'/';
			$inq = opendir($dir);
			while( $file = readdir($inq) ){
				if( $file!='.' and $file!='..'){
					if( is_dir($dir.$file) )
						read_Dir($dir.$file,$start);
					else{
						if( !empty($_REQUEST['mask']) and !preg_match('#'.$_REQUEST['mask'].'#ui',$file)  )
							continue;
						$data = file_get_contents($dir.$file);
						if( preg_match_all('#'.$_REQUEST['s'].'#uis',$data,$res,PREG_OFFSET_CAPTURE) ){
							echo str_replace($start,'<b>'.$start.'</b>',$dir.$file),'<br>';
							echo '--------------------------------------------------------------------------------------------------------------------------------------------------<br>';
							foreach($res[0] as $_res){
								echo '<pre>'.preg_replace('#('.$_REQUEST['s'].')#uis','<b>$1</b>',htmlspecialchars(mb_substr($data,$_res[1]-100,mb_strlen($_res[0])+100,'utf-8'))),'</pre>';
							}
							echo '-------------------------------------------------------------------<br>';
						}
					}
				}
			}
		}
	}
	read_Dir(dirname(__FILE__),dirname(__FILE__));
}
