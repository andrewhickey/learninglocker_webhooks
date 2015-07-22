<?php namespace app\locker\listeners;

class NewStatementsEventHandler {
  CONST EVENT   = 'statements.new';
  
  protected $client;
  
  public function __construct( \GuzzleHttp\Client $client ) {
    $this->client = $client ?: new \GuzzleHttp\Client();
  }

  public function handle($data) {
    // $data is an array of new statements
    $endpoints = \Config::get('webhooks.statements');
    
    foreach ($endpoints as $endpoint) {
      try {
        $response = $this->client->post($endpoint, [
          'json' => $data
        ]);
      } catch (\Exception $e) {
        \Log::error("Couldn't push to endpoint", [
          'exception' => $e,
          'endpoint' => $endpoint
        ]);
      }
    }

  }

}