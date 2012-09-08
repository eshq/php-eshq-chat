<?php

class ESHQ
{
  function __construct($options = array()) {
    $this->url    = $options['url']    || $_ENV['ESHQ_URL'];
    $this->key    = $options['key']    || $_ENV['ESHQ_KEY'];
    $this->secret = $options['secret'] || $_ENV['ESHQ_SECRET'];
    if (!($this->url && $this->key && $this->secret)) {
      throw("ESHQ Configuration missing - make sure all environment variables are set");
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
    return http_post_fields(
      $this->url . $path,
      array_merge($params, $this->credentials())
    );
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
    return sha1($this->key . ":" . $this->secret . ":" . $time);
  }
}

?>
