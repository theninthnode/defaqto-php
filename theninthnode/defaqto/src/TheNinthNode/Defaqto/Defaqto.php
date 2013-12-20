<?php namespace TheNinthNode\Defaqto;

class Defaqto
{

    /**
     * The Defaqto API URL
     *
     * @var string
     */
    private $defaqto_url = 'http://api.defaqto.dev/v1/';

    /**
     * The Defaqto API key
     *
     * @var string
     */
    private $app_id = 2;

    /**
     * The Defaqto secret
     *
     * @var string
     */
    private $access_token = '3b873a67ca0be246a83c5d09c09a2fef';

    /**
     * Is a bulk request?
     *
     * @var boolean
     */
    private $bulk = null;

    /**
     * Is a POST http method
     *
     * @var boolean
     */
    private $is_post = false;

    /**
     * The exclude array
     *
     * @var array
     */
    private $exclude = array();

    /**
     * The search criteria array
     *
     * @var array
     */
    private $criteria = array();

    /**
     * The entity (pages, blocks, variables, blog/posts, blog/categories, blog/tags, blog/authors)
     *
     * @var array
     */
    private $entity;

    /**
     * Create a new instance of Defaqto class
     */
    // public function __construct($key, $access_token)
    // {
    //     $this->app_id = $key;
    //     $this->access_token = $access_token;
    // }

    public function __construct()
    {

    }

    public static function world() {
        return 'Hello World';
    }

    /**
     * Static method
     * 
     * @param string $entity
     * @param array $criteria
     * @param array $exclude
     * @return array response from API
     */
    public function get($entity = '', $criteria = array(), $exclude = array()) {

        $this->entity = (string)$entity;
        $this->criteria = (array)$criteria;
        $this->exclude = (array)$exclude;

        return $this->getContent();

    }
 
    /**
     * Static method
     * 
     * @param string $entity
     * @param array $criteria
     * @param array $exclude
     * @return array response from API
     */
    public static function bulk($array = array()) {
        
        if($bulk !== null) {   
            $this->is_post = true;     
            $this->bulk = (array)$bulk;
        }

        return $this->getContent();

    }

    /**
     * Builds URL and does cURL request to consume API
     * @return array json_decoded API result
     */
    private function getContent() {

        $url = $this->buildUrl();
        $header = array('X-Defaqto-Token:'.$this->access_token);
        $data_string = '';

        if($this->bulk !== null) {
            $body_data = array('data'=>json_encode($this->bulk));
            $data_string = http_build_query($body_data);
            $header[] = "Content-Type: application/x-www-form-urlencoded";
            $header[] = "Content-Length: " . strlen($data_string);
        }

        $curl_opts = array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_USERAGENT => 'Defaqto Laravel',
            CURLOPT_HTTPHEADER => $header,
        );

        if($this->is_post === true) {
            $curl_opts[CURLOPT_POST] = TRUE;
            $curl_opts[CURLOPT_POSTFIELDS] = $data_string;
        }

        // Get cURL resource
        $curl = curl_init();
        curl_setopt_array($curl, $curl_opts);
        $resp = curl_exec($curl);
        $return = json_decode($resp);
        curl_close($curl);

        return $return;
    }

    /**
     * Builds API URL
     *
     * @return string
     */
    private function buildUrl() {
        $params = http_build_query($this->criteria);
        $exclude = $this->buildExclude();

        $url = $this->defaqto_url . $this->app_id . '/' . $this->entity . '?' . $params . '&' . $exclude;

        return $url;
    }

    /**
     * Builds the exclude part of the parameter from $this->exclude
     *
     * @return string
     */
    private function buildExclude() {
        $string = 'exclude=';

        $string .= implode('|', $this->exclude);

        return $string;
    }

    /**
     * Sets $exclude array or adds to array if string
     * 
     * @param array|string $exclude
     */
    public function exclude($exclude) {

        if(is_array($exclude)) {
            $this->exclude = $exclude;
        } else if(is_string($exclude)){
            $this->exclude[] = $exclude;
        }
    }
}
