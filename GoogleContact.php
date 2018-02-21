<?php
/**
 *  BARAHIMI MOHAMED AISSA 
 */
  class GoogleContact {
        
    protected $accesstoken;
    protected $customer_key;
    protected $customer_secret; 
    protected $redirect_url;
    protected $auth_code;
    protected $scope;
    protected $initial_url;

    public function __construct() {
        $this->accesstoken     = '';
        $this->customer_key    = '';
        $this->customer_secret = '';
        $this->auth_code = '';
        $this->redirect_url = '';
        $this->scope = '';
        $this->initial_url = '';
    }
      
    
    public function getAccessToken(){
        return $this->accesstoken; 
    }
    public function getCustomerKey(){
        return $this->customer_key;
    }
    public function getCustomerSecret(){
        return $this->customer_secret;
    }
    public function getAuthCode(){
        return $this->auth_code;
    }
    public function getScope(){
        return $this->scope;
    }
    public function getRedirectUrl(){
        return $this->redirect_url;
    }
    public function setAccessToken($accesstoken){
        $this->accesstoken = $accesstoken; 
    }
    public function setCustomerKey($customer_key){
        $this->customer_key = $customer_key;
    }
    public function setCustomerSecret($customer_secret){
        $this->customer_secret = $customer_secret;
    }
    public function setAuthCode($auth_code){
        $this->auth_code = $auth_code;
    }
    public function setScope($scope){
        $this->scope = $scope;
    }
    public function setRedirectUrl($redirect_url){
        $this->redirect_url = $redirect_url;
    }
      
    public function createInitUrl(){
     $this->initial_url =    "https://accounts.google.com/o/oauth2/auth?client_id=".$this->customer_key."&redirect_uri=".$this->redirect_url."&scope=".$this->scope."&response_type=code";
     return $this->initial_url; 
     }
    public function curl_var_init_access(){
        
        $fields = array(
       'code' => urlencode($this->auth_code),
       'client_id' => urlencode($this->customer_key),
       'client_secret' => urlencode($this->customer_secret),
       'redirect_uri' => urlencode($this->redirect_url),
       'grant_type' => urlencode('authorization_code')
       );
       $post = '';
       foreach ($fields as $key => $value) {
       $post .= $key . '=' . $value . '&';
       }
       $post = rtrim($post, '&');
       $curl = curl_init();
       curl_setopt($curl, CURLOPT_URL, 'https://accounts.google.com/o/oauth2/token');
       curl_setopt($curl, CURLOPT_POST, 5);
       curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
       curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
       curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
       curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
       $result = curl_exec($curl);
       
       curl_close($curl);
       
       return json_decode($result);
    }
     
  public function curl_file_get_contents($url) {
    $curl = curl_init($url);
    $userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
    
     //The URL to fetch. This can also be set when initializing a session with curl_init().
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE); //TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5); //The number of seconds to wait while trying to connect.
    
    curl_setopt($curl, CURLOPT_USERAGENT, $userAgent); //The contents of the "User-Agent: " header to be used in a HTTP request.
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE); //To follow any "Location: " header that the server sends as part of the HTTP header.
    curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE); //To automatically set the Referer: field in requests where it follows a Location: redirect.
    curl_setopt($curl, CURLOPT_TIMEOUT, 10); //The maximum number of seconds to allow cURL functions to execute.
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); //To stop cURL from verifying the peer's certificate.
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    
    $contents = curl_exec($curl);
    curl_close($curl);
    return $contents;
    }

  public function getAllContacts(){
    
    $url = $this->scope.'contacts/default/full?alt=json&max-results=' . Max_RESULT . '&oauth_token=' . $this->getAccessToken();
    $response = json_decode(self::curl_file_get_contents($url),true);

    $contacts = array(); 
     foreach($response['feed']['entry'] as $contact){
         if(isset($contact['gd$email'][0]['address'])){
             $contacts[] = array(
                 'name'  => $contact['title']['$t'],
                 'email' => $contact['gd$email'][0]['address']
                 );
         }
             
      }
    return $contacts;
  }
 
}