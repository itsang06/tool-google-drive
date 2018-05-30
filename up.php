<?php

require_once __DIR__ . '/vendor/autoload.php';
/**
*   Google Drive Class
*/
class GoogleDrive
{
  
  function __construct()
  {
    $this->appName    = 'Upload File To Google Drive';
    $this->credPath   = __DIR__ . 'secrets\drive-php-upload.json';
    $this->secretPath =  __DIR__ . '\secrets\client_secret.json';
    $this->scopes     = implode(' ', array(Google_Service_Drive::DRIVE));    
  }

  /**
   * Returns an authorized API client.
   * @return Google_Client the authorized client object
   */
  public function getClient() {
    $client = new Google_Client();
    $client->setApplicationName($this->appName);
    $client->setScopes($this->scopes);
    $client->setAuthConfig($this->secretPath);
    $client->setAccessType('offline');

    // // Load previously authorized credentials from a file.
    // $credentialsPath = $this->expandHomeDirectory($this->credPath);
    // if (file_exists($credentialsPath)) {
    //   $accessToken = json_decode(file_get_contents($credentialsPath), true);
  
    // } else {
  
    //   // Request authorization from the user.
    //   $authUrl = $client->createAuthUrl();
    //   printf("Open the following link in your browser:\n%s\n", $authUrl);
    //   print 'Enter verification code: ';
    //   $authCode = trim(fgets(STDIN));

    //   // Exchange authorization code for an access token.
    //   $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

    //   // Store the credentials to disk.
    //   if(!file_exists(dirname($credentialsPath))) {
    //     mkdir(dirname($credentialsPath), 0700, true);
    //   }
    //   file_put_contents($credentialsPath, json_encode($accessToken));
    //   printf("Credentials saved to %s\n", $credentialsPath);
    // }
  
    // $client->setAccessToken($accessToken);
    $client->refreshTokenWithAssertion();
    // Refresh the token if it's expired.
    if ($client->isAccessTokenExpired()) {
      $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
      file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
    }
    return $client;
  }

  /**
   * Expands the home directory alias '~' to the full path.
   * @param string $path the path to expand.
   * @return string the expanded path.
   */
  private function expandHomeDirectory($path) {
    $homeDirectory = getenv('HOME');
    if (empty($homeDirectory)) {
      $homeDirectory = getenv('HOMEDRIVE') . getenv('HOMEPATH');
    }
    return str_replace('~', realpath($homeDirectory), $path);
  }

  public function upload($path, $fileName){
    $folderId = '1Wmq1_GR9gIg3yndph98MGt_kDPVdsMJG';
    $client = $this->getClient();
    //$client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php');
    $service = new Google_Service_Drive($client);
    $fileMetadata = new Google_Service_Drive_DriveFile(array('name' => 'upload_'.$fileName, 'parents' => array($folderId)));
    //$content = file_get_contents($path. $fileName);
    $content = file_get_contents($path. $fileName);

    $userPermission = new Google_Service_Drive_Permission(array(
            'type' => 'anyone',
            'role' => 'reader'
        ));

    $file = $service->files->create($fileMetadata, array(
      'data'       => $content,
      'mimeType'   => mime_content_type($path. $fileName), //'image/jpeg',
      'uploadType' => 'multipart',      
      'fields'     => 'id,webContentLink'), $userPermission
    );

    $service->permissions->create($file->id, $userPermission);
    unlink($path. $fileName);
    
    return $file->id;
  }

  // public function getlink($path, $fileName){
  //   //$ids = array();
  //   $drive = new GoogleDrive();
  //   $file2 = $drive->upload($path, $fileName);
  //   unlink($path. $fileName);
  //   $client = $this->getClient();
  //   //$client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/oauth2callback.php');
  //   $service = new Google_Service_Drive($client);

  //   $service->getClient()->setUseBatch(true);
  //   //$driveService->getClient()->setUseBatch(true);

  //   try {
  //       $batch = $service->createBatch();

  //       $userPermission = new Google_Service_Drive_Permission(array(
  //           'type' => 'anyone',
  //           'role' => 'reader'
  //       ));

  //       $request = $service->permissions->create($file2, $userPermission, array('fields' => 'id'));
  //       $batch->add($request, 'anyone');      
  //       $results = $batch->execute();

  //       foreach ($results as $result) {
  //           if ($result instanceof Google_Service_Exception) {
  //               // Handle error
  //               printf($result);
  //           } else {
  //               //printf("Permission ID: %s\n", $result->id);
  //               //array_push($ids, $file2);
  //           }
  //       }
  //   } finally {
  //       //$driveService->getClient()->setUseBatch(false);
  //       $service->getClient()->setUseBatch(false);
  //   }
  //   //return $ids;
  //   return $file2;
  // }
}
?>