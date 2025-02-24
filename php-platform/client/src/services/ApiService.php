class ApiService {
    private $apiUrl;

    public function __construct($apiUrl) {
        $this->apiUrl = $apiUrl;
    }

    public function fetchData($endpoint) {
        $url = $this->apiUrl . '/' . $endpoint;
        $response = file_get_contents($url);
        return json_decode($response, true);
    }

    public function sendData($endpoint, $data) {
        $url = $this->apiUrl . '/' . $endpoint;
        $options = [
            'http' => [
                'header'  => "Content-type: application/json\r\n",
                'method'  => 'POST',
                'content' => json_encode($data),
            ],
        ];
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return json_decode($result, true);
    }
}