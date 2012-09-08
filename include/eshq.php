<?php

class ESHQ
{
  function __construct($options = array()) {
    $this->url    = $options['url']    ? $options['url']    : $_ENV['ESHQ_URL'];
    $this->key    = $options['key']    ? $options['key']    : $_ENV['ESHQ_KEY'];
    $this->secret = $options['secret'] ? $options['secret'] : $_ENV['ESHQ_SECRET'];
    if (!($this->url && $this->key && $this->secret)) {
      throw new Exception("ESHQ Configuration missing - make sure all environment variables are set");
    }
  }

  /**
   * Get the token for opening a connection to a new channel.
   *
   * @param $options array("channel" => "your-channel")
   * @return A token for opening an EventSource connection to the channel.
   */
  public function open($options) {
    $response = $this->post("/socket", array("channel" => $options['channel']));
    if ($response) {
      $json = json_decode($response);
      return $json["socket"];
    } else {
      return false;
    }
  }

  /**
   * Send a message to a channel
   *
   * @param $options array("channel" => "your-channel", "data" => "data-to-send")
   */
  public function send($options) {
    $response = $this->post("/event", array(
      "channel" => $options['channel'],
      "data"    => $options['data']
    ));
    return $response ? true : false;
  }


  private function post($path, $params) {
    $url    = $this->url . $path;
    $fields = array_merge($params, $this->credentials());

    $defaults = array(
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_URL => $url,
        CURLOPT_FRESH_CONNECT => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FORBID_REUSE => 1,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_POSTFIELDS => http_build_query($fields)
    );

    $ch = curl_init();
    curl_setopt_array($ch, $defaults);
    if( ! $result = curl_exec($ch))
    {
      throw new Exception("Request to ESHQ failed");
    }
    curl_close($ch); 
    return $result; 
  }

  private function credentials() {
    $time = time();
    return array(
      'timestamp' => $time,
      'token'     => $this->token($time),
      'key'       => $this->key
    );
  }

  private function token($time) {
    error_log("Generating token '{$this->key}:{$this->secret}:{$time}'");
    return sha1($this->key . ":" . $this->secret . ":" . $time);
  }
}

?>
