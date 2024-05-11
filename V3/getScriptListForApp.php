<?php
//include_once("_include/_testata.php");



/*function getPath(){
	
}
$list = [];
$default_dir = "../../app/v_BETA/";
$dp=opendir($default_dir);
while ($file=readdir($dp)){
	if ($file[0]!='_' && $file[0]!='.' && $file[0]!='-'){
		if(is_dir($default_dir.$file)) $srv[]=$file;
	}
}*/







/*$default_dir = "../../app/v_BETA/";
function recurly_list($path){
	$arrobiect = scandir($path);
	$arrname[0]=1;
	
	foreach($arrobiect as $name){
		if(($name != '.')  ||  ($name != '..')){
			if(is_dir($path.'\\'.$name)){
				$arr = recurly_list($path.'\\'.$name);
				unset($arr[0]);
				$arrname = array_merge($arrname, $arr);
			}else{
				array_push($arrname, $path.'\\'.$name);
			}
			$i++;
		}
	}
	return $arrname;
}

$val = recurly_list($default_dir);
print_r($val);
die("-");*/




 /*
 
 
 FUNZIONANTE IN HTML
 
 
 *//*   function ListFolder($path){
        //using the opendir function
        $dir_handle = @opendir($path) or die("Unable to open $path");

        //Leave only the lastest folder name
        $explode = explode("/", $path);
        $dirname = end($explode);

        //display the target folder.
        echo ("<li>$dirname\n");
        echo "<ul>\n";
        while (false !== ($file = readdir($dir_handle))) {
            if($file!="." && $file!=".."){
                if (is_dir($path."/".$file)){
                    //Display a list of sub folders.
                    ListFolder($path."/".$file);
                }else{
                    //Display a list of files.
                    echo "<li>$file</li>";
                }
            }
        }
        echo "</ul>\n";
        echo "</li>\n";

        //closing the directory
        closedir($dir_handle);
    }

$path = '../../app/v_BETA/';
ListFolder($path);
die("");*/



/**
 * Return the complete tree structure representation of the iterator
 * @param RecursiveIteratorIterator $iterator The recursive iterator directory on the path
 */
function buildFullTreeFromPaths(RecursiveIteratorIterator $iterator): array{
    //Filter dot files
    $iteratorFiltered = new CallbackFilterIterator($iterator, function ($current, $key, $iterator) {
        return !$iterator->isDot();
    });

    $trees = array();
    foreach ($iteratorFiltered as $file) {
        $trees[] = buildTreeFromPath($file->getPathname());
    }
    return array_merge_recursive(...$trees);
}

/**
 * Return the tree for the given path
 * @param string $path 
 * @param array $tree 
 * @return string|array
 */
/*$list = array();
$list_ok = array();*/
$default_dir = '../../app/v_BETA/';
function buildTreeFromPath(string $path, array $tree = array()){
    $pos = strpos($path, '/');

    if (false === $pos)
        return $path;

	$pF = explode(".",$path);
	$ext = $pF[count($pF)-1];
    if($ext == 'js' || $ext=='css')$key = substr($path, 0, $pos);
    $tree[$key] = buildTreeFromPath(substr($path, $pos + 1), $tree[$key] = array());

    return $tree;
}

$recursiveIterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($default_dir), 
    RecursiveIteratorIterator::LEAVES_ONLY
);

//Build the full tree
$tree = buildFullTreeFromPaths($recursiveIterator);

$tree = json_decode(json_encode($tree),true);
$tree = $tree[".."][".."]["app"]["v_BETA"];
die(base64_encode(json_encode($tree)));
if(!empty($tree))die(base64_encode(json_encode($tree)));
else die('404');

?>