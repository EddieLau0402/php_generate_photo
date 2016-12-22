<?php
/**
 * 使用GD库生成图片
 */
define('IMG_DIR', __DIR__ . '/asset/img');
define('FONT_DIR', './asset/font');

/// 字体
define('FONT_MSYHBD', FONT_DIR . '/msyh.ttf');
define('FONT_TIMES_ROMAN', FONT_DIR . '/Times-Roman.ttf');

/// 指定背景图
// define('IMG_BG', IMG_DIR . '/bg_org.png');
define('IMG_BG', IMG_DIR . '/bg.png');


/// 指定宽、高 (750 x 1206)
define('PIC_W', 750);
define('PIC_H', 1206);
/// 指定头像大小 (90 x 90)
define('AVATOR_W', 90);
define('AVATOR_H', 90);

define('PRICE_FORMATER', "￥%s/天");


###=============================================================================================###

$orgPicPath = IMG_DIR . '/avator.jpg'; // 头像地址

$orgPic = imagecreatefromstring(file_get_contents($orgPicPath));
// $orgPicW = imagesx($orgPic); // 取得原图像宽度
// $orgPicH = imagesy($orgPic); // 取得原图像高度
list($orgPicW, $orgPicH) = getimagesize($orgPicPath);
// // print_r(['原图像宽度' => $orgPicW, '原图像高度' => $orgPicH]);

/*
 * Part 1 : 缩放
 */
$avator = imageCreateTrueColor(AVATOR_W, AVATOR_H); // 新建一个真彩色图像
imagecopyresampled($avator, $orgPic, 0, 0, 0, 0, AVATOR_W, AVATOR_H, $orgPicW, $orgPicH); // 重采样拷贝部分图像并调整大小

/*
 * Part 2 : 剪裁
 */
$avatorCircle = imageCreateTrueColor(AVATOR_W, AVATOR_H); // 新建一个真彩色图像
imagealphablending($avatorCircle, false); 
$transparent = imagecolorallocatealpha($avatorCircle, 0, 0, 0, 127);
$r = AVATOR_W / 2;
for ($x = 0; $x < AVATOR_W; $x++) { 
    for ($y = 0; $y < AVATOR_H; $y++) { 
        $c = imagecolorat($avator, $x, $y);
        $_x = $x - AVATOR_W / 2;
        $_y = $y - AVATOR_H / 2;

        if ((($_x * $_x) + ($_y * $_y)) < ($r * $r)) {
            imagesetpixel($avatorCircle, $x, $y, $c);
        }
        else {
            imagesetpixel($avatorCircle, $x, $y, $transparent); 
        }
    }
}
imagesavealpha($avatorCircle, true);

// header('Content-type: image/png');
// imagejpeg($avator);
// imagepng($avatorCircle);
// imagepng($avatorCircle, './downloads/test_avator.png');

// /*
//  * 销毁，回收资源
//  */
imagedestroy($orgPic);
imagedestroy($avator); 
// imagedestroy($avatorCircle);
// exit('done');


###=============================================================================================###


/// 创建画布, 指定宽、高
$image = imagecreate(PIC_W, PIC_H);
// $image = imageCreateTrueColor(PIC_W, PIC_H);

/// 指定背景
// $image = imagecreatefromjpeg('XXXXX.jpg'); // 从 JPEG 文件或 URL 地址载入一副图像
// $image = imagecreatefrompng(IMG_BG); // 从 PNG 文件或 URL 地址载入一副图像
$image = imagecreatefromstring(file_get_contents(IMG_BG)); // 从字符串中的图像流新建一副图像


$red = imagecolorallocate($image, 255, 0, 0); // 定义红色
$green = imagecolorallocate($image, 0, 255, 0); // 定义绿色
$blue = imagecolorallocate($image, 0, 0, 255); // 定义蓝色
$white = imagecolorallocate($image, 255, 255, 255); // 定义白色
$black = imagecolorallocate($image, 0, 0, 0); // 定义黑色


/*
 *
 */
// putenv('GDFONTPATH=' . realpath('.'));

imagefttext($image, 24, 0, 136, 528, $black, FONT_MSYHBD, '小沈阳'); // 名称

imagefttext($image, 28, 0, 30, 660, $black, FONT_MSYHBD, '模特 - 车模'); // 类别

$price = 9999;
// $price = 999;
$x = strlen($price) < 4 ? 568 : 560;
imagefttext($image, 22, 0, $x, 640, $white, FONT_MSYHBD, sprintf(PRICE_FORMATER, $price)); // 金额

imagefttext($image, 22, 0, 252, 733, $black, FONT_MSYHBD, '女生'); // 性别

imagefttext($image, 22, 0, 252, 795, $black, FONT_MSYHBD, '99人'); // 人数

imagefttext($image, 22, 0, 252, 853, $black, FONT_MSYHBD, '12月22日'); // 日期

imagefttext($image, 22, 0, 252, 913, $black, FONT_MSYHBD, '广州市天河区正佳广场'); // 地址


/*
 * 头像
 */
$dstX = 34;
$dstY = 490;
// $avatorCircle = imagecreatefromstring(file_get_contents('./downloads/test_avator.png'));
imagecopy($image, $avatorCircle, $dstX, $dstY, 0, 0, imagesx($avatorCircle), imagesy($avatorCircle));


/* 
 * 不加文件名，直接输出到网页
 */
header('Content-type: image/jpeg');
imagejpeg($image);

/*
 * 保存到指定路径
 */
// $savePath = '';
// imagejpeg($image, $savePath);


/*
 * 销毁，回收资源
 */
// imagedestroy($avator);
imagedestroy($image); 
imagedestroy($avatorCircle);

exit;