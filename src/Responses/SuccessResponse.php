<?php

declare(strict_types=1);

namespace Talendor\ElevenLabsClient\Responses;

class SuccessResponse
{

    protected $response;

    protected $message;


    public function __construct($response, string $message)
    {
        $this->response  = $response;
        $this->message = $message;
    }

    public function getMessage()
    {
        return $this->message;
    }


    public function getResponse(): array
    {
        return [
            'status' => 200,
            'response_body' => $this->response->getBody()->getContents(),
            'message' => $this->message,
        ];
    }
}
