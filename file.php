<html>
<head>
    <title>文件管理</title>
    <meta charset='utf-8'/>
</head>
<body>
<?php
//定义要查看的目录
$dir = "/";
//if (isset($dir)) {
//    $dir = $_GET('dir');
//}

//先判断$_GET['a']是否已经传值 防止NOTICE错误
if (isset($_GET['a'])) {
    //选择判断要执行的操作
    switch ($_GET['a']) {
        case 'creat':
            //新建文件
            $filename = $_POST["filename"];
            $filename = rtrim($dir, "/") . "/" . $filename;
            //写入文件 写入一个空字符串
            file_put_contents($filename, "");
            break;
        case 'del':
            //删除文件
            unlink($_GET['filename']);
            break;
        case 'update':
            //修改文件
            file_put_contents($_POST['filename'], $_POST['content']);
            echo "修改成功";
            header("refresh:1;url=index.php");
            break;
    }
}

?>
<center>
    <h1>文件管理</h1>
    <form action='index.php?a=creat' method='post'>
        文件：<input type='text' name='filename'/>
        <input type='submit' value='新建'/>
    </form>
    <table border='1' width='900' cellpadding='5' cellspacing='0'>
        <tr>
            <th>文件名</th>
            <th>类型</th>
            <th>大小</th>
            <th>创建时间</th>
            <th>操作</th>
        </tr>
        <?php
        //遍历目录
        $dd = opendir($dir);
        while (false !== ($f = readdir($dd))) {
            //过滤点
            if ($f == "." || $f == "..") {
                continue;
            }
            //拼路径
            $file = rtrim($dir, "/") . "/" . $f;
            //防止中文乱码
            $f2 = iconv("gb2312", "utf-8", $f);
            echo "<tr>";
            echo "<td><a href='file.php?dir=123'>{$f2}</a> </td>";
            echo "<td>" . filetype($file) . "</td>";
            echo "<td>" . filesize($file) . "</td>";
            echo "<td>" . filectime($file) . "</td>";
            echo "<td align='center'>
                                    <a href='index.php?a=edit&filename={$file}'>修改</a>|
                                    <a href='index.php?a=del&filename={$file}'>删除</a>
                                  </td>";
            echo "</tr>";

        }
        ?>
    </table>
    <?php
    if (isset($_GET['a']) && $_GET['a'] == 'edit') {
        echo "<hr/>";
        echo "<form action='index.php?a=update' method='post'>";
        echo "文件名：<input type='text' name='filename' readonly value='{$_GET['filename']}'  />";
        echo "<br/><br/>";
        echo "<textarea name='content' rows='5' cols='30'>" . file_get_contents($_GET['filename']) . "</textarea>";
        echo "<br/><br/>";
        echo "<input type='submit' value='保存修改' />";
        echo "</form>";
    }

    ?>
</center>
</body>
</html>
