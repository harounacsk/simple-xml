<?php
$file = "input.xml";
$xml;

if (file_exists($file))
	$xml = simplexml_load_file($file);
else
	exit('Failed to open input.xml.');


$groups = $xml->xpath("/products/product/@grp");
$groups = array_map("strval", $groups);
$groups = array_flip(array_flip($groups));
$doc = new DOMDocument('1.0');
// we want a nice output
$doc->formatOutput = true;

$products = $doc->createElement('products');
$doc->appendChild($products);


foreach ($groups as $group) {
	$elements = $xml->xpath("/products/product[@grp = '$group']");
	$product = $doc->createElement('product');
	$products->appendChild($product);

	/**
	 * set attribute "group" to product element
	 */
	$groupAttribute = $doc->createAttribute('group');
	$groupAttribute->value = $group;
	$product->appendChild($groupAttribute);

	foreach ($elements as $e) {
		$elm = $doc->createElement('elm');
		$product->appendChild($elm);

		$nameAttribute = $doc->createAttribute('name');
		$nameAttribute->value = $e["name"];
		$elm->appendChild($nameAttribute);

		$priceAttribute = $doc->createAttribute('price');
		$priceAttribute->value = $e["price"];
		$elm->appendChild($priceAttribute);
	}
}
$fileName = "result.xml";

if ($doc->save($fileName))
	echo "$fileName created!!!";
else
	echo "Error!!";