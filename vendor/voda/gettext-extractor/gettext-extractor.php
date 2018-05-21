#!/usr/bin/php
<?php
/**
 * GettextExtractor
 *
 * This source file is subject to the New BSD License.
 *
 * @copyright Copyright (c) 2010 Ondřej Vodáček
 * @license New BSD License
 * @package Nette Extras
 */

foreach (array('vendor', '../..') as $path) {
	if (file_exists($file = __DIR__ . "/$path/autoload.php")) {
		require $file;
	}
}

$output = 'php://stdout';
$log = 'php://stderr';
$keywords = null;
$meta = null;

$options = getopt('o::l::f:hk:m:');


if (isset($options['h'])) {
	echo <<<EOF
Usage {$argv[0]} [options]

Options:
  -h            display this help and exit
  -oFILE        output file, default output is stdout
  -lFILE        log file, default is stderr
  -fFILE        file to extract, can be specified several times
  -kFUNCTION    add FUNCTION to filters, format is:
                FILTER:FUNCTION_NAME:SINGULAR,PLURAL,CONTEXT
                default FILTERs are PHP and NetteLatte
                for SINGULAR, PLURAL and CONTEXT '0' means not set
                can be specified several times
  -mKEY:VALUE   set meta header

EOF;
	exit;
}
if (isset($options['l'])) {
	if (is_array($options['l'])) {
		echo "Specify only one log file.\n";
		exit;
	}
	$log = $options['l'];
}
if (isset($options['o'])) {
	if (is_array($options['o'])) {
		echo "Specify only one output file.\n";
		exit;
	}
	$output = $options['o'];
}
if (!isset($options['f'])) {
	echo "No input files given.\n";
	echo "Try '{$argv[0]} -h' for more informations.\n";
	exit;
}
if (isset($options['k'])) {
	$keywords = array();
	if (is_string($options['k'])) {
		$options['k'] = array($options['k']);
	}
	foreach ($options['k'] as $value) {
		$filter = $function = $params = null;
		list ($filter, $function, $params) = explode(':', $value);
		$params = explode(',', $params);
		foreach ($params as &$param) {
			$param = (int)$param;
			if ($param === 0) {
				$param = null;
			}
		}
		$keywords[] = array(
			'filter' => $filter,
			'function' => $function,
			'singular' => isset($params[0]) ? $params[0] : null,
			'plural' => isset($params[1]) ? $params[1] : null,
			'context' => isset($params[2]) ? $params[2] : null
		);
	}
}
if (isset($options['m'])) {
	if (is_string($options['m'])) {
		$options['m'] = array($options['m']);
	}
	$key = $value = null;
	foreach ($options['m'] as $m) {
		list($key, $value) = explode(':', $m, 2);
		$meta[$key] = $value;
	}
}

$extractor = new GettextExtractor_NetteExtractor($log);
$extractor->setupForms()->setupDataGrid();
if ($keywords !== null) {
	foreach ($keywords as $value) {
		$extractor->getFilter($value['filter'])
				->addFunction($value['function'], $value['singular'], $value['plural'], $value['context']);
	}
}
if ($meta) {
	foreach ($meta as $key => $value) {
		$extractor->setMeta($key, $value);
	}
}
$extractor->scan($options['f']);
$extractor->save($output);
