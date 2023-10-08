<?php

namespace Talendor\ElevenLabsClient;

use Talendor\ElevenLabsClient\History\History;
use Talendor\ElevenLabsClient\Interfaces\ElevenLabsClientInterface;
use Talendor\ElevenLabsClient\Models\Models;
use Talendor\ElevenLabsClient\TextToSpeech\TextToSpeech;
use Talendor\ElevenLabsClient\TextToSpeech\TextToSpeechInterface;
use Talendor\ElevenLabsClient\User\User;
use Talendor\ElevenLabsClient\Voice\Voice;
use GuzzleHttp\Client;

class ElevenLabsClient implements ElevenLabsClientInterface
{
    protected $apiKey;

    protected $httpClient;

    const BASE_URL = 'https://api.elevenlabs.io/v1/';

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;

        $this->httpClient = new Client([
            'base_uri' => self::BASE_URL,
            'headers' => [
                'xi-api-key' => $this->apiKey,
                'Accept' => 'application/json',
            ],
        ]);
    }

    public function getHttpClient(): Client
    {
        return $this->httpClient;
    }


    /**
     * Get the Voice Instance.
     *
     * @return Voice The Voice instance.
     */
    public function voices(): Voice
    {
        return new Voice($this);
    }

    /**
     * Get the TextToSpeech Instance.
     *
     * @return TextToSpeech The Voice instance.
     */
    public function textToSpeech(): TextToSpeechInterface
    {
        return new TextToSpeech($this);
    }

    /**
     * Get the History Instance.
     *
     * @return History The History instance.
     */
    public function history(): History
    {
        return new History($this);
    }

    /**
     * Get the Available Models Instance.
     *
     * @return Models The Models instance.
     */
    public function models(): Models
    {
        return new Models($this);
    }

    /**
     * Get the Available User Instance.
     *
     * @return User
     */
    public function user()
    {
        return new User($this);
    }
}
