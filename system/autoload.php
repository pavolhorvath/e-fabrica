<?php

/**
 * load system files
 */
foreach (scandir(__DIR__) as $fileName) {
	$filePath = __DIR__ . '/' . $fileName;
	if ($filePath != __FILE__ && is_file($filePath)) {
		require_once $filePath;
	}
}

/**
 * load vendor
 */
$vendorAutoloadPath = __DIR__ . '/../vendor/autoload.php';
if (is_file($vendorAutoloadPath)) {
	require_once $vendorAutoloadPath;
}

/**
 * load app folder
 */
$appFolder = __DIR__ . '/../app';
$subFolders = [];
foreach (scandir($appFolder) as $appFolderItem) {
	if (!in_array($appFolderItem, ['.', '..']) && is_dir($appFolder . '/' . $appFolderItem)) {
		$subFolders[] = $appFolder . '/' . $appFolderItem;
	}
}
foreach ($subFolders as $subFolder) {
	foreach (scandir($subFolder) as $fileName) {
		$filePath = $subFolder . '/' . $fileName;
		if (is_file($filePath)) {
			require_once $filePath;
		}
	}
}