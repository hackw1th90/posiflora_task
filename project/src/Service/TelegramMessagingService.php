<?php
declare(strict_types=1);

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use RuntimeException;
use Throwable;

/**
 * Class TelegramMessagingService
 *
 * @package App\Service
 */
class TelegramMessagingService
{
    /**
     * @param string $token
     * @param string $chatId
     * @param string $message
     * @return null
     * @throws GuzzleException|RuntimeException
     */
    public function send(string $token, string $chatId, string $message): null
    {
        $client = new Client(['base_uri' => 'https://api.telegram.org/bot' . $token . '/']);
        $response = $client->post('sendMessage', array('query' => array('chat_id' => $chatId, 'text' => $message)));
        if ($response->getStatusCode() !== 200) {

            throw new RuntimeException($response->getBody()->getContents());
        }

        return null;
    }
}
