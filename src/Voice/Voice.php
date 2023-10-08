<?php

declare(strict_types=1);

namespace Talendor\ElevenLabsClient\Voice;

use Exception;
use Talendor\ElevenLabsClient\Responses\ErrorResponse;
use Talendor\ElevenLabsClient\Interfaces\ElevenLabsClientInterface;
use Talendor\ElevenLabsClient\Responses\SuccessResponse;
use Talendor\ElevenLabsClient\Traits\ExceptionHandlerTrait;

class Voice implements VoiceInterface
{
    use ExceptionHandlerTrait;
    protected $client;

    public function __construct(ElevenLabsClientInterface $client)
    {
        $this->client = $client->getHttpClient();
    }

    /**
     * Retrieve all the available voices
     *
     * @return array The list of voices.
     * 
     * See: https://docs.elevenlabs.io/api-reference/voices
     */
    public function getAll(): array
    {
        try {
            $response    = $this->client->get('voices');
            $data        = $response->getBody()->getContents();
            $decodedData = json_decode($data, true);

            return $decodedData['voices'] ?? [];
        } catch (Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Returns metadata about a specific voice.
     *
     * @return array metadata of voice
     * 
     * See: https://docs.elevenlabs.io/api-reference/voices-get
     */
    public function getVoice(string $voice_id): array
    {
        try {
            $response = $this->client->get('voices/' . $voice_id);
            $data        = $response->getBody()->getContents();
            $decodedData = json_decode($data, true);

            return $decodedData;
        } catch (Exception $e) {
            return $this->handleException($e);
        }
    }



    /**
     * Gets the default settings for voices. "similarity_boost" corresponds to"Clarity + Similarity Enhancement" 
     * in the web app and "stability" corresponds to "Stability" slider in the web ap
     *
     * @return array The list of voices.
     * 
     * See: https://docs.elevenlabs.io/api-reference/voices-settings-default
     */
    public function defaultSettings()
    {
        try {
            $response    = $this->client->get('voices/settings/default');
            $data        = $response->getBody()->getContents();
            $decodedData = json_decode($data, true);

            return $decodedData;

            return $data;
        } catch (Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Gets the default settings for voices. "similarity_boost" corresponds to"Clarity + Similarity Enhancement" 
     * in the web app and "stability" corresponds to "Stability" slider in the web ap
     *
     * @return array The list of voices.
     * 
     * See: https://docs.elevenlabs.io/api-reference/voices-settings
     */
    public function voiceSettings(string $voice_id)
    {

        if (empty($voice_id)) {
            return (new ErrorResponse(400, "voice_id is missing"))->getResponse();
        }

        try {
            $response = $this->client->get('voices/' . $voice_id . '/settings');
            $data        = $response->getBody()->getContents();
            $decodedData = json_decode($data, true);

            return $decodedData;
        } catch (Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Add a new voice to your collection of voices in VoiceLab.
     *
     * @return array status,message
     * 
     * See: https://docs.elevenlabs.io/api-reference/voices-add
     */
    public function addVoice(string $name, ?string $description, string $files, ?string $labels = "American")
    {
        try {

            $requestData = [
                [
                    'name' => 'name',
                    'contents' => $name,
                ],
                [
                    'name' => 'files',
                    'contents' => fopen($files, 'r'),
                ],
                [
                    'name' => 'description',
                    'contents' => $description,
                ],
                [
                    'name' => 'labels',
                    'contents' => '{"accent":"' . $labels . '"}',
                ],
            ];

            $response = $this->client->post('voices/add', [
                'multipart' => $requestData,
            ]);

            $status = $response->getStatusCode();

            if ($status === 200) {
                return (new SuccessResponse($status, "Your Custom Voice Succesfully Created"))->getResponse();
            }
        } catch (Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Edit voice to your collection of voices in VoiceLab.
     *
     * @return array status,message
     * 
     * See: https://docs.elevenlabs.io/api-reference/voices-edit
     */
    public function editVoice(string $voice_id, string $name, ?string $description, ?string $files, ?string $labels = "American")
    {
        try {

            $requestData = [
                [
                    'name' => 'name',
                    'contents' => $name,
                ],
                [
                    'name' => 'files',
                    'contents' => fopen($files, 'r'),
                ],
                [
                    'name' => 'description',
                    'contents' => $description,
                ],
                [
                    'name' => 'labels',
                    'contents' => '{"accent":"' . $labels . '"}',
                ],
            ];

            $response = $this->client->post('voices/' . $voice_id . '/edit', [
                'multipart' => $requestData,
            ]);

            $status = $response->getStatusCode();

            if ($status === 200) {
                return (new SuccessResponse($status, "Your Custom Voice Succesfully Created"))->getResponse();
            }
        } catch (Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Delete specific voice 
     *
     * @return array status,message
     * 
     * See: https://docs.elevenlabs.io/api-reference/voices-delete
     */
    public function deleteVoice(string $voice_id): array
    {
        try {
            $response = $this->client->delete('voices/' . $voice_id);
            $status   = $response->getStatusCode();
            return (new SuccessResponse($status, "Voice Succesfully Deleted"))->getResponse();
        } catch (Exception $e) {
            return $this->handleException($e);
        }
    }
}
