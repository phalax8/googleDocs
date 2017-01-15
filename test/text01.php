<?php

$hashFileTest = "dd77075b72ecacb8f86343c0890d1ade";

/**   root directory */
if (!defined('PROJECT_ROOT')) {
    define('PROJECT_ROOT', dirname(__FILE__) . '/../');
    require(PROJECT_ROOT . 'Autoloader.php');
}

$gd = new googleDocs();
$gd->printFile($hashFileTest);
echo('<br>--------------------------------<br>');
$gd->deleteFile($hashFileTest);
echo('<br>--------------------------------<br>');
$response = $gd->insertFile('Test title', 'Test description', $hashFolderParent, 'text/html', $path_to_file);
echo('<br>--------------------------------<br>');
$gd->addPermission($response->id, array(
    'type' => 'user',
    'role' => 'writer',
    'emailAddress' => 'testuser@gmail.com'
));
echo('<br>--------------------------------<br>');
$gd->retrieveAllFiles();
echo('<br>--------------------------------<br>');
$gd->retrieveAllFiles(array(
    "q" => "name = 'nameDirectory'"
));
echo('<br>--------------------------------<br>');
$gd->getFile($response->id);
echo('<br>--------------------------------<br>');
$response = $gd->insertFile(('Test title', 'Test description', $hashFolderParent, 'application/vnd.google-apps.document', $path_to_file);
$files = $gd->retrieveAllFiles();

$folderRoot = $gd->google_docs_get_folder_root();

foreach ($files as $file) {
    $gd->deleteFile($file->id);
    printf("Found file: %s (%s)<br>", $file->name, $file->id);
}

echo("End");
