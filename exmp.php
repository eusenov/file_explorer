<?php 
if(!empty($_POST['open']) && !empty($_POST['id']) && $_COOKIE['path']){
    $path = $_COOKIE['path']; 
    $subpath = $_POST['id'];

    $path .= "\\" . $subpath; 
    setcookie('path', $path);
    $arr = createArrOfFiles($path);
} else if (!empty($_POST['delete']) && !empty($_POST['id']) && $_COOKIE['path']){
    $path = $_COOKIE['path']; 
    $path2 = $_COOKIE['path'];
    $subpath = $_POST['id']; 

    $path2 .= "\\" . $subpath; 
    remove_path($path2); 

    $arr = createArrOfFiles($path);
} else if (!empty($_POST['create']) && $_COOKIE['path']){
    $path = $_COOKIE['path']; 
    $newpath = $path . '\\' . $_POST['create-name']; 
    mkdir($newpath, 0777, True);
    $arr = createArrOfFiles($path); 
} else if (!empty($_POST['copy']) && !empty($_POST['create-copy']) && !empty($_POST['id']) && $_COOKIE['path']){
    $path = $_COOKIE['path']; 
    $subpath = $_POST['id']; 

    $drc = $_POST['create-copy']; 
    $src = $path . '\\' . $subpath; 

    copy_dir($src, $drc); 

    setcookie('path', $path);
    $arr = createArrOfFiles($path); 
} else if (!empty($_POST['back']) && $_COOKIE['path']){
    $path = removeLastDirectory($_COOKIE['path']); 

    setcookie('path', $path);
    $arr = createArrOfFiles($path); 
}else {
    $path = __DIR__; 
    setcookie('path', $path);
    $arr = createArrOfFiles($path);
}

function getprint($el){
    echo '<pre>';
    print_r($el); 
    echo '</pre>';
}

function copy_dir($src, $drc){
    $dir = opendir($src);
    if (!is_dir($drc)) {
        mkdir($drc, 0777, true);
    }
    while (false !== ($file = readdir($dir))) {
        if ($file != '.' && $file != '..') {
            if (is_dir($src . '/' . $file)) {
                copy_dir($src . '/' . $file, $drc . '/' . $file);
            } else {
                copy($src . '/' . $file, $drc . '/' . $file);
            }
        }
    }
    closedir($dir);
}
function remove_path($path){
    if($objs = glob($path . '/*')){
        foreach($objs as $obj){
            if(is_dir($obj)){
                remove_path($obj); 
            } else {
                unlink($obj); 
            }
        }
    } 
    if(is_dir($path)){
        rmdir($path); 
    }
}
function removeLastDirectory($path) {
    $path = rtrim($path, '\\');
    $pos = strrpos($path, '\\');
    if ($pos === false) {
        return $path;
    }
    return substr($path, 0, $pos);
}

function deleteDots($arrOfFiles){
    foreach($arrOfFiles as $ind=>$file){
        if ($file == "." || $file == ".."){
            unset($arrOfFiles[$ind]);   
        }
    }

    return $arrOfFiles; 
}
function createArrOfFiles($path){
    $files = array();
    getprint($path); 
    $dh = opendir($path);
    while (false !== ($file = readdir($dh))) {
        if ($file != '.' && $file != '..' && $file[0] != '.') {
            $files[] = $file;
        }
    }
    closedir($dh);
    return deleteDots($files);
}

function ceateExplorerBlock($arr){
    echo '<div class="explorer__block">'; 
    echo 
    "<form class='button' action='exmp.php' method='POST'>
        <input type='submit' value='Назад' name='back'>
    </form>"; 
    for ($i=0; $i < count($arr); $i++) { 
        $arr_i = $arr[$i];
        if (strpos($arr_i, '.') == false) {
            echo 
            "<div class='file__block'>
                <div class='span'>$arr[$i]</div>
                <div class='file__buttons'>
                    <form action='exmp.php' method='POST'>
                        <input type='submit' name='open' id='' value='Открыть'>
                        <input type='text' placeholder='Укажите полный путь для копии' name='create-copy' class='create-copy'>
                        <input type='submit' value='Копировать' name='copy'>
                        <input type='submit' name='delete' id='' value='Удалить'>
                        <input type='hidden' name='id' value='$arr[$i]'>
                    </form>
                </div>
            </div>"; 
        } else {
            echo 
            "<div class='file__block'>
                <div class='span'>$arr[$i]</div>
                <div class='file__buttons'>
                    <form action='exmp.php' method='POST'>
                    <input type='hidden' name='id' value='$arr[$i]'>
                    </form>
                </div>
            </div>"; 
        }
    } 
    echo 
    "<form class='button' action='exmp.php' method='POST'>
        <input type='text' placeholder='Введите имя новой папки' name='create-name'>
        <input type='submit' value='Создать' name='create'>
    </form>"; 
    echo '</div>'; 
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Explorer</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="explorer">
    <?php ceateExplorerBlock($arr); ?>
</div>

<?php 

?>

</body>
</html>