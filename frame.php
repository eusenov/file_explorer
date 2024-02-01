<!-- v1 -->
<!-- # не работает при попытке вывести вложенные в массив директории (getArrOfFiles($filesArr[3][3])) -->

function deleteDots($arrOfFiles){
    foreach($arrOfFiles as $ind=>$file){
        if ($file == "." || $file == ".."){
            unset($arrOfFiles[$ind]);   
        }
    }

    return $arrOfFiles; 
}

function getArrOfFiles($dir){
    $ArrOfFiles = scandir($dir); 

    return $ArrOfFiles; 
}

$filesArr = getArrOfFiles(__DIR__);

echo '<pre>'; 
print_r($filesArr); 
echo '</pre>'; 

<!-- v2 -->
<!-- добавлена функция распознания директорий -->
function getIndsToDirs($dir){
    for ($i=0; $i < count($dir); $i++) {  
        if (is_dir($dir[$i])) {
            var_dump($dir[$i]);
            echo '<br>'; 
        }
    }
}
function deleteDots($arrOfFiles){
    foreach($arrOfFiles as $ind=>$file){
        if ($file == "." || $file == ".."){
            unset($arrOfFiles[$ind]);   
        }
    }

    return $arrOfFiles; 
}
function list_files($path){
    if ($path[mb_strlen($path) - 1] != '/') {
        $path .= '/'; 
    }
    $files = array();
    $dh = opendir($path);
    while (false !== ($file = readdir($dh))) {
        if ($file != '.' && $file != '..' && $file[0] != '.') {
            $files[] = $file;
        }
    }
    closedir($dh);
    return $files;
}
function getArrOfFiles($dir){
    $ArrOfFiles = list_files($dir); 

    return $ArrOfFiles; 
}

$filesArr = getArrOfFiles(__DIR__);

echo '<pre>'; 
echo getIndsToDirs($filesArr);  
echo '</pre>'; 

<!-- v3 -->
if ($_COOKIE['main_dir']) {
    $mainDir = $_COOKIE['main_dir']; 
} else {
    setcookie('main_dir', getMainDir(__DIR__)); 
}
<!-- // // // -->
function getMainDir($path){
    if ($path[mb_strlen($path) - 1] != '/') {
        $path .= '/'; 
    }
    $files = array();
    $dh = opendir($path);
    while (false !== ($file = readdir($dh))) {
        if ($file != '.' && $file != '..' && $file[0] != '.' && is_dir($file)) {
            return $file;
        }
    }
    closedir($dh);
}
<!-- // // // -->
if ($path[mb_strlen($path) - 1] != '/') {
        $path .= '/'; 
    }

<!-- v4 -->
function getButtonsForDir($el){
    if(is_dir($el)){
        return  
        "<input type='submit' name='open' id='' value='Открыть'>
        <input type='submit' name='delete' id='' value='Удалить'>"; 
    }
}

<!-- v5 -->
<?php 
if (!empty($_POST['id']) && $_COOKIE['dir']){
    if(!empty($_POST['open'])){
        $arr = showDir($_COOKIE['dir'], $_POST['id']); 
    } 
    if(!empty($_POST['delete'])){
        $dir = $_COOKIE['dir']; 
        $subdir = $_POST['id']; 
        if(dirname($subdir) == '.'){
            $dir .= "\\" . $subdir; 
            remove_dir($dir); 
        }
        $arr = showDir($_COOKIE['dir'], $_POST['id']); 
    }  
    if (!empty($_POST['create'])) {
        $dir = $_COOKIE['dir']; 
        $newDir = $dir . '\\' . $_POST['create-name']; 
        mkdir($newDir, 0777, True);
        $arr = showDir($_COOKIE['dir'], $_POST['id']); 
    }  
} else {
    $dir = __DIR__; 
    setcookie('dir', $dir);
    $arr = getList_files($dir);
}



function remove_dir($dir){
    if($objs = glob($dir . '/*')){
        foreach($objs as $obj){
            if(is_dir($obj)){
                remove_dir($obj); 
            } else {
                getprint('not dir'); 
                unlink($obj); 
            }
        }
    } 
    if(is_dir($dir)){
        rmdir($dir); 
    }
}
function getprint($el){
    echo '<pre>';
    print_r($el); 
    echo '</pre>';
}

function showDir($cookie, $id){
    $dir = $cookie; 
    $subdir = $id; 
    if(dirname($subdir) == '.'){
        $dir .= "\\" . $subdir; 
        setcookie('dir', $dir);
        $arr = getList_files($dir);
    }
    return $arr; 
}

function getList_files($path){
    function deleteDots($arrOfFiles){
        foreach($arrOfFiles as $ind=>$file){
            if ($file == "." || $file == ".."){
                unset($arrOfFiles[$ind]);   
            }
        }
    
        return $arrOfFiles; 
    }
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
    for ($i=0; $i < count($arr); $i++) { 
        $arr_i = $arr[$i];
        if (strpos($arr_i, '.') == false) {
            echo 
            "<div class='file__block'>
                <div class='span'>$arr[$i]</div>
                <div class='file__buttons'>
                    <form action='exmp.php' method='POST'>
                        <input type='submit' name='open' id='' value='Открыть'>
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
    "<form class='create-button' action='exmp.php' method='POST'>
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

<!-- v6 -->
// .. c функцией копирования
<?php 
if(!empty($_POST['open']) && !empty($_POST['id']) && $_COOKIE['dir']){
    $dir = $_COOKIE['dir']; 
    $subdir = $_POST['id']; 
    if(dirname($subdir) == '.'){
        $dir .= "\\" . $subdir; 
        setcookie('dir', $dir);
        $arr = getList_files($dir);
    }
} else {
    $dir = __DIR__; 
    setcookie('dir', $dir);
    $arr = getList_files($dir);
}

if (!empty($_POST['delete']) && !empty($_POST['id']) && $_COOKIE['dir']){
    $dir = $_COOKIE['dir']; 
    $dir2 = $_COOKIE['dir'];
    $subdir = $_POST['id']; 
    if(dirname($subdir) == '.'){
        $dir .= "\\" . $subdir; 
        remove_dir($dir); 
    }
    $arr = getList_files($dir2);
}  

if (!empty($_POST['create']) && $_COOKIE['dir']){
    $dir = $_COOKIE['dir']; 
    $newDir = $dir . '\\' . $_POST['create-name']; 
    mkdir($newDir, 0777, True);
    $arr = getList_files($dir); 
}  

if (!empty($_POST['copy']) && !empty($_POST['id']) && $_COOKIE['dir']){
    $dir = $_COOKIE['dir']; 
    $subdir = $_POST['id']; 
   
    $newDir = $dir . '\\' . $subdir; 
    mkdir($newDir, 0777, True);
    $arr = getList_files($dir); 
} 

function remove_dir($dir){
    if($objs = glob($dir . '/*')){
        foreach($objs as $obj){
            if(is_dir($obj)){
                remove_dir($obj); 
            } else {
                getprint('not dir'); 
                unlink($obj); 
            }
        }
    } 
    if(is_dir($dir)){
        rmdir($dir); 
    }
}
function getprint($el){
    echo '<pre>';
    print_r($el); 
    echo '</pre>';
}

function deleteDots($arrOfFiles){
    foreach($arrOfFiles as $ind=>$file){
        if ($file == "." || $file == ".."){
            unset($arrOfFiles[$ind]);   
        }
    }

    return $arrOfFiles; 
}
function getList_files($path){
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
    for ($i=0; $i < count($arr); $i++) { 
        $arr_i = $arr[$i];
        if (strpos($arr_i, '.') == false) {
            echo 
            "<div class='file__block'>
                <div class='span'>$arr[$i]</div>
                <div class='file__buttons'>
                    <form action='exmp.php' method='POST'>
                        <input type='submit' name='open' id='' value='Открыть'>
                        <input type='submit' name='delete' id='' value='Удалить'>
                        <input type='submit' name='copy' id='' value='Копировать'>
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
    "<form class='create-button' action='exmp.php' method='POST'>
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

<!-- v7 (функции запускаются с доп. инфой)-->
if(!empty($_POST['open']) && !empty($_POST['id']) && $_COOKIE['path']){
    $path = $_COOKIE['path']; 
    $subpath = $_POST['id'];
    getprint('path: ' . $path);  
    getprint('subpath: ' . $subpath); 

    $path .= "\\" . $subpath; 
    getprint('path + subpath: ' . $path); 
    setcookie('path', $path);
    $arr = createArrOfFiles($path);
    getprint($arr);    
} else if (!empty($_POST['delete']) && !empty($_POST['id']) && $_COOKIE['path']){
    $path = $_COOKIE['path']; 
    $path2 = $_COOKIE['path'];
    $subpath = $_POST['id']; 
    getprint('path while clicking on delete button: ' . $path); 
    getprint('subpath while clicking on delete button: ' . $subpath); 

    $path2 .= "\\" . $subpath; 
    getprint('path to delete: ' . $path2); 
    remove_path($path2); 

    $arr = createArrOfFiles($path);
} else if (!empty($_POST['create']) && $_COOKIE['path']){
    $path = $_COOKIE['path']; 
    getprint('$path dure create button activate: ' . $path); 
    $newpath = $path . '\\' . $_POST['create-name']; 
    getprint('path to create: ' . $newpath);
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
    getprint('path before go back: ' . $_COOKIE['path']); 
    $path = removeLastDirectory($_COOKIE['path']); 

    setcookie('path', $path);
    $arr = createArrOfFiles($path); 
}else {
    $path = __DIR__; 
    setcookie('path', $path);
    $arr = createArrOfFiles($path);
}
<!-- // // // -->

<!-- // // // -->

<!-- // // // -->