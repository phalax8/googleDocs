<?php

include_once dirname(__FILE__) . "/google-api-php-client-2.0.1-PHP5_4/vendor/autoload.php";

class google_basic
{
    var $service = null;

    public function get_service(){
        if ($this->service == null) {

            $client = new Google_Client();
            $client->setScopes(SCOPES);
            $this->setApplicationName($client);
            $this->set_auth($client);

            $this->service = $this->set_service_object($client);
        }

        return $this->service;
    }

    public function setApplicationName($client){}
}
