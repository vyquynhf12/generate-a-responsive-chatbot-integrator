<?php

// Configuration
$integrator_name = 'DFY9 Responsive Chatbot Integrator';
$bot_api_key = 'YOUR_API_KEY';
$bot_api_secret = 'YOUR_API_SECRET';
$default_intent = 'default_fallback';

// Initialize chatbot instance
class Chatbot {
  private $api_key;
  private $api_secret;
  private $intent;

  function __construct($api_key, $api_secret, $intent) {
    $this->api_key = $api_key;
    $this->api_secret = $api_secret;
    $this->intent = $intent;
  }

  function processMessage($message) {
    $url = 'https://api.chatbot.com/v1/messages';
    $headers = array(
      'Authorization: Bearer ' . $this->api_key,
      'Content-Type: application/json'
    );
    $data = array('message' => $message, 'intent' => $this->intent);
    $response = json_decode($this->sendRequest($url, 'POST', $headers, json_encode($data)), true);
    return $response['response'];
  }

  private function sendRequest($url, $method, $headers, $data) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    if ($method == 'POST') {
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
  }
}

// Initialize integrator instance
$chatbot = new Chatbot($bot_api_key, $bot_api_secret, $default_intent);

// Handle user input
if (isset($_POST['message'])) {
  $message = $_POST['message'];
  $response = $chatbot->processMessage($message);
  echo '<p>' . $response . '</p>';
}

// HTML template for chat interface
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $integrator_name; ?></title>
  <style>
    body {
      font-family: Arial, sans-serif;
    }
    .chat-interface {
      max-width: 400px;
      margin: 40px auto;
      padding: 20px;
      border: 1px solid #ddd;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
  </style>
</head>
<body>
  <div class="chat-interface">
    <h2><?php echo $integrator_name; ?></h2>
    <form>
      <input type="text" id="message" name="message" placeholder="Type a message...">
      <button type="submit">Send</button>
    </form>
    <div id="chat-log">
      <?php if (isset($response)) { echo '<p>' . $response . '</p>'; } ?>
    </div>
  </div>
</body>
</html>