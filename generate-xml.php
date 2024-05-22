<?php
// Ensure SimpleXMLElement is available
if (!class_exists('SimpleXMLElement')) {
    die('SimpleXMLElement class not available. Ensure the XML extension is enabled in PHP.');
}

// Function to strip HTML tags and return plain text
function strip_html_tags($text) {
    return strip_tags($text);
}

// Open the CSV file
$csvFile = 'path_to_your_csv_file.csv';
if (!file_exists($csvFile)) {
    die('CSV file not found.');
}

$file = fopen($csvFile, 'r');
if (!$file) {
    die('Error opening CSV file.');
}

// Read the header line
$header = fgetcsv($file);
if ($header === FALSE) {
    die('Error reading CSV header.');
}

// Create a new XML document
$xml = new SimpleXMLElement('<products/>');

// Process each line in the CSV
while (($line = fgetcsv($file)) !== FALSE) {
    // Combine the header and line to create an associative array
    $data = array_combine($header, $line);

    // Create a new product element
    $product = $xml->addChild('product');
    $product->addAttribute('available', 'true');
    $product->addAttribute('id', '513410207'); // Fixed ID as per the example

    // Add the elements
    $product->addChild('url', htmlspecialchars($data['handle']));
    $product->addChild('name', htmlspecialchars($data['Title']));
    $product->addChild('price', htmlspecialchars($data['price']));
    $product->addChild('currencyId', 'GBP');
    $product->addChild('categoryId', '20985933');

    // Handle the description
    $description = empty($data['Description']) ? $data['Title'] : strip_html_tags($data['Description']);
    $product->addChild('description', htmlspecialchars($description));

    // Add a fixed picture URL as per the example
    $product->addChild('picture', 'https://static.insales-cdn.com/images/products/1/5944/537261880/003415_40_70_1_.jpg');
}

// Close the CSV file
fclose($file);

// Save the XML to a file
$xml->asXML('output.xml');

// Output to browser
header('Content-type: text/xml');
echo $xml->asXML()

