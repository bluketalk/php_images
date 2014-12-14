<?php
/**
 * 将大图片按照配置切割成一定比例的小图片
 * 并按照一定规则给小图片命名
 *
 * 使用方法：
 *根据guardians/g1目录下的Attack_1.xml配置切割Attack_1.png
 *也可批量切割（我写的是批量切割）
 *
 * 注：需要GD2支持
 */
// echo "{${phpinfo()}}";
header("Cache-Control:no-cache,must-revalidate"); //不使用缓存

for ($i=1; $i < 100; $i++) { 
    $filename="guardians/g".$i."/Attack_1.png";//大图文件
    $tempdir="temp";//小图存放目录
    //判断文件是否存在 不存在就切割完毕
    if(file_exists($filename)){
        if(!file_exists($tempdir)) mkdir($tempdir);
    }
    $xml=simplexml_load_file("guardians/g".$i."/Attack_1.xml");
    echo "guardians/g".$i."/Attack_1.xml<br>";
    $j = 1;
    foreach($xml -> SubTexture as $SubTexture){
        $attri = $SubTexture->attributes();
        $picW=$attri->frameWidth;                                    //切割小图的宽
        $picH=$attri->frameHeight; 
        //为支持大图片增加内存限制
        ini_set( 'memory_limit', '220M' );                                    //切割小图的高
        echo $picW.",".$picH."<br>";
        list($width, $height, $type, $attr) = getimagesize($filename);

        $image = imagecreatefrompng($filename);
        //透明背景
        $im = imagecreatetruecolor((int)$picW, (int)$picH) or die("Cannot Initialize new GD image stream");//创建小图像
        imagealphablending($im, false);
        imagesavealpha($im, true);
        $white = imagecolorallocatealpha($im,255,255,255,127);
        imagefill($im,0,0,$white);

        $picX=$attri->width;//获取截取图片的宽度
        $picY=$attri->height;//获取截取图片的高度
         echo $picX.",".$picY."<br>";
        $frameX = $attri->frameX;
        $frameY = $attri->frameY;
        $x = $attri->x;
        $y = $attri->y;

        echo $frameX.",".$picY."<br>";
        imagecopy ( $im, $image, -(int)$frameX, -(int)$frameY, (int)$x, (int)$y, (int)$picX, (int)$picY );//拷贝大图片的一部分到小图片
        imagepng($im,$tempdir."/g".$i."_Attack_1_".$j.".png",0, 75);//创建小图片到磁盘，输出质量为75（0~100）
        echo $tempdir."/g".$i."_Attack_1_".$j.".png". "<br>";
        $j = $j + 1;
        imagedestroy($im);//释放与 $im 关联的内存
        imagedestroy($image);//释放与 $image 关联的内存

   }
}

echo " complate";
?>