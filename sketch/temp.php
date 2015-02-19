<html>
<head>
</head>

<body>  
    <?php
        $dir = 'img/clipart/Love/';
        $files = array();
        foreach (new DirectoryIterator($dir) as $fileInfo) {
            if($fileInfo->isDot() || !$fileInfo->isFile()) continue;
            $files[] = $fileInfo->getFilename();
        }

        echo json_encode($files);        
    ?>
</body>

</html>
