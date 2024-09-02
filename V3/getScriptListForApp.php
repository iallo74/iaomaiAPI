<?php
//include_once("_include/_testata.php");





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
$actVer = file_get_contents("../../app/__actVer.txt");
$default_dir = "../../app/".$actVer."/";
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
$tree = $tree[".."][".."]["app"][$actVer];
die(base64_encode(json_encode($tree)));
if(!empty($tree))die(base64_encode(json_encode($tree)));
else die('404');

?>