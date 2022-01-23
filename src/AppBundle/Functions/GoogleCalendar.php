<?php 

namespace AppBundle\Functions;

use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;

class GoogleCalendar
{

	private $ROOT_DIR;
    private $APPLICATION_NAME = 'Shissab';
    private $CREDENTIALS_PATH;
    private $CLIENT_SECRET_PATH;
    private $REFRESH_TOKEN_PATH;
    private $SCOPES;
    private $kernel;
    private $AUTH_CODE = '4/0AX4XfWh1ZXNDBqzuO75PzRSRIE3K3eH54TUYPyFLFl5SZmBATit_VgGq29QnJ3rrB53UeA';
	
	function __construct()
	{
	 	global $kernel;
        $this->kernel = $kernel;
        $this->ROOT_DIR = $this->kernel->getRootDir();
        $this->CREDENTIALS_PATH = $this->ROOT_DIR . '/credentials/calendar_token.json';
        $this->CLIENT_SECRET_PATH = $this->ROOT_DIR . '/credentials/code_secret_client.json';
        $this->REFRESH_TOKEN_PATH = $this->ROOT_DIR . '/credentials/calendar_refresh_token.json';

        $this->SCOPES = implode(' ', array(
                Google_Service_Calendar::CALENDAR)
        );
	}

    /**
     * @throws \Google_Exception
     */
    public function getClient()
    {
        $client = new Google_Client();
        $client->setApplicationName($this->APPLICATION_NAME);
        $client->setScopes($this->SCOPES);
        $client->setAuthConfig($this->CLIENT_SECRET_PATH);
        $client->setAccessType('offline');
        $client->setApprovalPrompt('force');


        // Load previously authorized credentials from a file.
        if (file_exists($this->CREDENTIALS_PATH)) {
            $accessToken = json_decode(file_get_contents($this->CREDENTIALS_PATH), true);
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            

            /*header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
            if (isset($_GET['code'])) {
                $this->AUTH_CODE = $_GET['code'];
            }

            $authCode = trim(file_get_contents($authUrl));*/

            // Exchange authorization code for an access token.
            $accessToken = $client->fetchAccessTokenWithAuthCode($this->AUTH_CODE);

            // var_dump($authUrl);
            // var_dump($accessToken);
            // die();

            // Store the credentials to disk.
            if (!file_exists(dirname($this->CREDENTIALS_PATH))) {
                mkdir(dirname($this->CREDENTIALS_PATH), 0700, true);
            }
            file_put_contents($this->CREDENTIALS_PATH, json_encode($accessToken));
        }

        
        $client->setAccessToken($accessToken);
        if (!file_exists(dirname($this->REFRESH_TOKEN_PATH))) {
            mkdir(dirname($this->REFRESH_TOKEN_PATH), 0700, true);
        }
        if ($client->getRefreshToken()) {
            file_put_contents($this->REFRESH_TOKEN_PATH, $client->getRefreshToken());
        }

        // Refresh the token if it's expired.
        if ($client->isAccessTokenExpired()) {
            $refreshToken = $client->getRefreshToken();
            if (!$refreshToken || trim($refreshToken) == "") {
                $refreshToken = file_get_contents($this->REFRESH_TOKEN_PATH);
            }
            $client->fetchAccessTokenWithRefreshToken($refreshToken);
            file_put_contents($this->CREDENTIALS_PATH, json_encode($client->getAccessToken()));
        }
        return $client;
    }

    /**
     * @throws \Google_Exception
     * @throws \Exception
     */
    public function getCalendar()
    {
        // if (!$this->config) {
        //     throw new \Exception("Calendar Config not set.");
        // }
        // $now = new \DateTime();
        // if (!$this->timeMin) {
        //     $this->timeMin = new \DateTime($now->format('Y-m-01'));
        //     $this->timeMin->setTime(0, 0);
        // }
        // if (!$this->timeMax) {
        //     $this->timeMax = new \DateTime($this->timeMin->format('Y-m-01'));
        //     $this->timeMax->setTime(0, 0);
        //     $this->timeMax->add(new \DateInterval('P1M'));
        //     $this->timeMax->sub(new \DateInterval('P1D'));
        //     $this->timeMax->setTime(23, 59);
        // }

        // Get the API client and construct the service object.
        $client = $this->getClient();
        $service = new Google_Service_Calendar($client);

        $results = $service->calendarList->listCalendarList();


        var_dump($results);die();



        $optParams = array(
            'maxResults' => 100000,
            'orderBy' => 'startTime',
            'singleEvents' => TRUE,
            'timeMin' => $this->timeMin->format('c'),
            'timeMax' => $this->timeMax->format('c'),
        );
        $results = $service->events->listEvents($this->config->getIdentifiant(), $optParams);


        var_dump($results);die();

        $listes = [];

        /** @var Google_Service_Calendar_Event $event */
        foreach ($results->getItems() as $event) {
            if (strtolower(substr($event->getSummary(), 0, 2)) != 's-') {
                $start = $event->start->dateTime;
                if (empty($start)) {
                    $start = $event->start->date;
                }
                $start = new \DateTime($start);

                $listes[] = [
                    'id' => $event->getId(),
                    'title' => trim($event->getSummary()),
                    'start' => $start->format('Y-m-d'),
                    'color' => $this->config->getColor(),
                    'textColor' => $this->config->getTextColor(),
                    'type' => 'gcal',
                ];
            }
        }

        return $listes;

    }
}