<?php

/**   root directory */
if (!defined('PROJECT_ROOT')) {
    define('PROJECT_ROOT', dirname(__FILE__) . '/');
    require(PROJECT_ROOT . 'Autoloader.php');
}

class google_docs extends google_basic
{
    private $_folder_root = 'HASH-FOLDER-IN-GOOGLE-DRIVE';
    private $_url_base_share = 'https://docs.google.com/document/d/XXIDXX/edit?usp=sharing';

    public function __construct()
    {
        define('SCOPES', implode(' ', array(
                'https://www.googleapis.com/auth/drive')
        ));
    }

    public function google_docs_get_folder_root()
    {
        return $this->_folder_root;
    }

    public function google_docs_get_url_to_share($file_id)
    {
        return str_replace('XXIDXX', $file_id, $this->_url_base_share);
    }

    public function set_auth(&$client)
    {
        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . dirname(__FILE__) . '/service-account.json');
        $client->useApplicationDefaultCredentials();
    }

    public function set_service_object($client)
    {
        return new Google_Service_Drive($client);
    }

    public function printFile($fileId)
    {
        try {
            $file = $this->get_service()->files->get($fileId);

            print "Title: " . $file->getName();
            print "Description: " . $file->getDescription();
            print "MIME type: " . $file->getMimeType();
        } catch (Exception $e) {
            print "An error occurred: " . $e->getMessage();
        }
    }

    public function insertFile($title, $description, $folderId, $mimeType, $filename)
    {
        $file = new Google_Service_Drive_DriveFile(array(
            'mimeType' => $mimeType
        ));
        $file->setName($title);
        $file->setDescription($description);
        $file->setMimeType($mimeType);

        if ($folderId != null) {
            $file->setParents(array($folderId));
        }

        try {
            $data = file_get_contents($filename);

            $createdFile = $this->get_service()->files->create($file, array(
                'data' => $data,
                'mimeType' => 'application/octet-stream'
            ));

            return $createdFile;

        } catch (Exception $e) {
            print "An error occurred: " . $e->getMessage();
        }

        return null;
    }

    public function moveFile($fileId, $folderId)
    {
        $emptyFileMetadata = new Google_Service_Drive_DriveFile();

        // Retrieve the existing parents to remove
        $file = $this->get_service()->files->get($fileId, array('fields' => 'parents'));

        $previousParents = join(',', $file->parents);

        // Move the file to the new folder
        try {
            $file = $this->get_service()->files->update($fileId, $emptyFileMetadata, array(
                'addParents' => $folderId,
                'removeParents' => $previousParents,
                'fields' => 'id, parents'));

            return $file;

        } catch (Exception $e) {
            throw new Exception("An error occurred: " . $e->getMessage());
        }
    }

    public function addPermission($fileId, $permissions)
    {
        $this->get_service()->getClient()->setUseBatch(true);

        try {

            $batch = $this->get_service()->createBatch();

            $userPermission = new Google_Service_Drive_Permission($permissions);

            $request = $this->get_service()->permissions->create($fileId, $userPermission, array('fields' => 'id'));

            $batch->add($request, 'user');

            $batch->execute();

        } catch (Exception $e) {
            print "An error occurred: " . $e->getMessage();
        }

        $this->get_service()->getClient()->setUseBatch(false);
    }

    public function deleteFile($fileId)
    {
        try {
            $this->get_service()->files->delete($fileId);
        } catch (Exception $e) {
            print "An error occurred: " . $e->getMessage();
        }
    }

    public function retrieveAllFiles($parameters = [])
    {
        $result = array();

        try {
            $result = $this->get_service()->files->listFiles($parameters);

        } catch (Exception $e) {
        }

        return $result;
    }

    public function getFile($fileId, $props = [])
    {
        $content = null;

        try {
            $content = $this->get_service()->files->get($fileId, $props);
        } catch (Exception $e) {
        }

        return $content;
    }

    public function exportFile($fileId, $mimeType)
    {
        $content = null;

        try {

            $result = $this->get_service()->files->export($fileId, $mimeType, array('alt' => 'media' ));

            $content = $result->getBody()->getContents();

        } catch (Exception $e) {
            throw new Exception("An error occurred: " . $e->getMessage());
        }

        return $content;
    }

    public function createFolder($folderName, $folderId)
    {
        try {

            $folder = new Google_Service_Drive_DriveFile(array(
                'name' => $folderName,
                'mimeType' => 'application/vnd.google-apps.folder'
            ));

            if ($folderId != null) {
                $folder->setParents(array($folderId));
            }

            $folder = $this->get_service()->files->create($folder, array(
                'fields' => 'id'
            ));

            return $folder->id;

        } catch (Exception $e) {
            throw new Exception("An error occurred: " . $e->getMessage());
        }
    }
}
