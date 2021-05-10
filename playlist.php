<?php

$json = '[';

PlayerjsScanFolder('.');

function PlayerjsScanFolder($folder){
    global $json;
    foreach (scandir($folder) as $file){
        if(is_dir($file)){
            if($file!='..' && $file!='.'){
                $json.='{"title":"'.$file.'","folder":[';
                PlayerjsScanFolder($file);
                $json = chop($json,',');
                $json.=']},';
            }
        }else{
            PlayerjsAddFileToJson($file,$folder);
        }
    }
}

function PlayerjsAddFileToJson($file,$folder){
    global $json;
    if($file){
        $ext = substr($file,strrpos($file,'.'));
        $except = ['.php','.jpg','.txt'];
        if(strpos($file,'.')>0 &&!in_array($ext,$except)){
            $filename = substr($file,0,strpos($file,'.'));
            $poster = '';
            
            $path =  (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}".pathinfo($_SERVER['PHP_SELF'], 1).'/'.($folder!='.'?$folder.'/':'');

            if(file_exists(($folder!='.'?$folder.'/':'').$filename.'.jpg')){
                $poster = ',"poster":"'.$path.($filename.'.jpg').'"';
            }
            $json.='{"title":"'.$filename.'","file":"'.$path.$file.'"'.$poster.'},';
        }
    }
}

$json = chop($json,',').']';

echo($json);

file_put_contents('playlist.txt', $json);

?>
