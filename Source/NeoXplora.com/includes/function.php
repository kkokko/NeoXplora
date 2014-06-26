<?php

function GenerateThumb($thumb) {
    // get the image and size
    $thumb_array = explode('/', $thumb);
    array_shift($thumb_array);
    $size = array_shift($thumb_array);
    $image = ROOT . implode('/', $thumb_array);
    list($width, $height) = explode('x', $size);

    // ensure the image file exists
    if (!file_exists($image)) {
        error('no source image');
    }

    // generate the thumbnail
    require(ROOT . 'phpthumb/phpthumb.class.php');
    $phpThumb = new phpThumb();
    $phpThumb->setSourceFilename($image);
    $phpThumb->setParameter('w', $width);
    $phpThumb->setParameter('h', $height);
    $phpThumb->setParameter('f', substr($thumb, -3, 3)); // set the output format
    $phpThumb->setParameter('zc', 'T');
    //$phpThumb->setParameter('far','C'); // scale outside
    //$phpThumb->setParameter('bg','FFFFFF'); // scale outside
    if (!$phpThumb->GenerateThumbnail()) {
        error('cannot generate thumbnail');
    }

    // make the directory to put the image
    if (!mkpath(dirname($thumb), true)) {
        error('cannot create directory');
    }

// write the file
    if (!$phpThumb->RenderToFile($thumb)) {
        error('cannot save thumbnail');
    }
}

// basic error handling
function error($error) {
    echo '<h1>Not Found</h1>';
    echo '<p>The image you requested could not be found.</p>';
    echo "<p>An error was triggered: <b>$error</b></p>";
    exit();
}

//recursive dir function
function mkpath($path, $mode) {
    is_dir(dirname($path)) || mkpath(dirname($path), $mode);
    return is_dir($path) || @mkdir($path, 0777, $mode);
}

?>