<?php
const ALLOW_RAW_OUTPUT = false;
// 是否开启 ?raw 选项，可能会消耗服务器较多流量

function has_query($query)
{
    return isset($_GET[$query]);
}
// 初始化imgs_array
$imgs_array = array();
$id_param = has_query('id') ? $_GET['id'] : ""; // 获取id参数

// 根据id参数读取相应的CSV文件
 
// 构建文件路径
$file_path = __DIR__ . '/../data/' . $id_param . '.csv';

// 检查文件是否存在
if (file_exists($file_path)) {
    $imgs_array = file($file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
} else {
    // 如果文件不存在，默认返回 img1.csv 的内容
    $imgs_array = file(__DIR__ . '/../data/img1.csv', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
}
if (count($imgs_array) === 0) {
    $imgs_array = array('https://http.cat/503');
}

$id = has_query('id') ? $_GET['id'] : "";
if (strlen($id) > 0 && is_numeric($id)) {
    settype($id, 'int');
    $len = count($imgs_array);
    if ($id >= $len || $id < 0) {
        $id = array_rand($imgs_array);
    } else {
        header('Cache-Control: public, max-age=86400');
    }
} else {
    header('Cache-Control: no-cache');
    $id = array_rand($imgs_array);
}

// 处理请求的输出
if (has_query('json')) {
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    echo json_encode(array('id' => $id, 'url' => $imgs_array[$id]));
} else if (has_query('raw')) {
    if (!ALLOW_RAW_OUTPUT) {
        header('HTTP/1.1 403 Forbidden');
        exit();
    }
    header('Content-Type: image/png');
    echo file_get_contents($imgs_array[$id]);
} else {
    header('Referrer-Policy: no-referrer');
    header('Location: ' . $imgs_array[$id]);
}

exit();
