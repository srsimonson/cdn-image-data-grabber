<?php
// List of image URLs
$urls = array(
    'https://foo.cdn.bar.com/folder/001.jpg',
    'https://foo.cdn.bar.com/folder/002.jpeg',
    'https://foo.cdn.bar.com/folder/003.png',
    'https://foo.cdn.bar.com/folder/004.jfif'
);

// Open a file for writing the CSV output
$outputFile = 'image_data.csv';
$output = fopen($outputFile, 'w');

// Write CSV headers
fputcsv($output, array('Name', 'File Size (bytes)', 'Width', 'Height', 'Aspect Ratio'));

foreach ($urls as $url) {
    // Get the file name from the URL
    $name = basename($url);

    // Initialize variables
    $fileSize = 'Unknown';
    $width = 'Unknown';
    $height = 'Unknown';
    $aspectRatio = 'Unknown';

    // Get file size using HTTP HEAD request
    $headers = @get_headers($url, 1);
    if ($headers && isset($headers['Content-Length'])) {
        // Content-Length might be an array if multiple headers are returned
        $fileSize = is_array($headers['Content-Length']) ? $headers['Content-Length'][0] : $headers['Content-Length'];
    }

    // Get image dimensions
    $imageSize = @getimagesize($url);
    if ($imageSize) {
        $width = $imageSize[0];
        $height = $imageSize[1];

        // Calculate aspect ratio
        if ($height != 0) {
            $aspectRatio = round($width / $height, 2);
        }
    }

    // Write the data to the CSV file
    fputcsv($output, array($name, $fileSize, $width, $height, $aspectRatio));
}

// Close the CSV file
fclose($output);

echo "Image data has been written to {$outputFile}\n";
?>
