<?php namespace TheNinthNode\Defaqto;

use Guzzle\Http\Client;
use Exception;

class Defaqto
{
    /**
     * The Defaqto API URL
     *
     * @var string
     */
    private $defaqto_url = 'http://api.defaqto.io/v1/';

    /**
     * The Defaqto API key
     *
     * @var string
     */
    private $app_id = null;

    /**
     * The Defaqto secret
     *
     * @var string
     */
    private $access_token = '';

    /**
     * Is bulk request?
     *
     * @var bool
     */
    private $is_bulk = false;

    /**
     * Used when bulk request
     *
     * @var array
     */
    private $bulk_data = array();

    /**
     * The exclude array
     *
     * @var array
     */
    private $exclude = array();

    /**
     * The search params array
     *
     * @var array
     */
    private $params = array();

    /**
     * The entity (pages, blocks, variables, blog/posts, blog/categories, blog/tags, blog/authors)
     *
     * @var string
     */
    private $entity = '';

    /**
     * constructor takes app id and access token
     * @param int $app_id
     * @param string $access_token
     */
    public function __construct($app_id = null, $access_token = '') {      
        $this->app_id = (int)$app_id;
        $this->access_token = $access_token;
        return;
    }

    /**
     * Create a new instance of Defaqto class
     *
     * @param string $entity
     * @param array $criteria
     * @param array $exclude
     * @param array|null $bulk
     */
    public static function setup($app_id = null, $access_token = '') {
        $defaqto_instance = new Defaqto($app_id, $access_token);
        return $defaqto_instance;
    }

    /**
     * public method for getting a single entity
     * 
     * @param string $entity
     * @param array $params
     * @param array $exclude
     * @return array response from API
     */
    public function get($entity, $params = array(), $exclude = array()) {
        $this->exclude = (array)$exclude;
        $this->params = (array)$params;
        $this->entity = (string)$entity;

        return $this->getContent();
    }
 
    /**
     * public method for getting multiple entities in one go
     * 
     * @param array $array
     * @return array response from API
     */
    public function bulk($array = array()) {
        $this->entity = 'bulk';
        $this->is_bulk = true;
        $this->bulk_data = (array)$array;
        return $this->getContent();
    }

    /**
     * Builds URL and uses Guzzle to consume API
     * @return array json_decoded API result
     */
    private function getContent() {
        if(is_numeric($this->app_id) === FALSE || $this->access_token == '')
            throw new Exception("invalid APP ID or Access Token", 1);
            
        // create new guzzle client w/ defaqto api url
        $client = new Client($this->defaqto_url);

        $uri = $this->buildUri();
        $headers = array('X-Defaqto-Token'=>$this->access_token); // mandatory access token

        if($this->is_bulk === TRUE) {
            // bulk api request via POST
            $body_data = array('data'=>json_encode($this->bulk_data));
            $data_string = http_build_query($body_data);
            $headers["Content-Type"] = "application/x-www-form-urlencoded";
            $headers["Content-Length"] = strlen($data_string);

            $request = $client->post($uri, $headers, $data_string);
        } else {
            $request = $client->get($uri, $headers);
        }

        $response = $request->send();
        return $response->json();
    }

    /**
     * Builds URL to endpoint
     *
     * @return string
     */
    private function buildUri() {
        $params = http_build_query($this->params);
        $exclude = $this->buildExclude();
        $uri = $this->app_id . '/' . $this->entity . '?' . $params . $exclude;
        return $uri;
    }

    /**
     * Builds the exclude part of the parameter from $this->exclude
     * Pipe delimited string of entities to exclude
     *
     * @return string
     */
    private function buildExclude() {
        $string = '';
        if(is_array($this->exclude) && count($this->exclude) > 0) {
            $string .= '&exclude=';
            $string .= implode('|', $this->exclude);
        }
        return $string;
    }
}
