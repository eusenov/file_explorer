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
    mkdir($newDir, 0777, true);
    $arr = getList_files($dir); 
} 

function remove_dir($dir){
    if($objs = glob($dir . '/*')){
        foreach($objs as $obj){
            if(is_dir($obj)){
                remove_dir($obj); 
            } else {
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
    

<br>Желательно возвращаться каждый раз к главной директории
<br>если нужно перейти к соседним друг другу папкам.
<br>К примеру, если вы были в папке main\test1\test1_1, и вы хотите 
<br>перейти к папке main\test2. То вам понадобиться путем нажатия кнопки 
<br>"назад" в браузере вернуться к обзору главной директории, где вы сможете видеть папку "main". 
<br>И только после этого проделать путь к test2.
<br>
<br>В левом верхнем углу описан актуальный путь (помощь в ориентривании из-за
<br>описанного выше неудобства). Кнопка удаления срабатывает после второго нажатия.
<br>
<br>Проект не сделан до конца из-за нехватки времени. Сдаю сейчас с такой запиской по эксплуатации,
<br>чтобы просто не нарушать срок сдачи.
<br>
<br>Панирую довести это задание до ума в ближайшее время.

<div class="explorer">
    <?php ceateExplorerBlock($arr); ?>

</div>

<?php 

?>

</body>
</html>