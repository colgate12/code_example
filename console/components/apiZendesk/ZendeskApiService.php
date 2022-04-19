<?php

namespace marketplace\common\components\apiZendesk;

use marketplace\common\helpers\RequestHelper;

/**
 * Class ZendeskService
 *
 * @package marketplace\common\components\apiZendesk
 */
class ZendeskApiService
{
    const FUNCTION_SEARCH = 'search.json';
    const FUNCTION_TICKET = 'tickets';
    const DEFAULT_TAG = 'sellers_conversation';

    /**
     * @param array $data
     * @return mixed
     * @throws ZendeskException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    private function search(array $data)
    {
        $request = new ZendeskApiAdapter(self::FUNCTION_SEARCH);
        $request->setData($data);
        $response = $request->sendRequest();
        $responseData = $response->getData();

        if (empty($responseData)) {
            throw new ZendeskException(['message' => 'API: Not found data']);
        }

        return $responseData;
    }

    /**
     * @param int $ticketId
     * @return mixed
     * @throws ZendeskException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function comments(int $ticketId)
    {
        $request = new ZendeskApiAdapter(sprintf(self::FUNCTION_TICKET . '/%s/comments', $ticketId));
        $response = $request->sendRequest();
        $responseData = $response->getData();

        if (empty($responseData)) {
            throw new ZendeskException(['message' => 'API: Not found data']);
        }

        return $responseData;
    }

    /**
     * @param string|null $query
     * @return mixed
     * @throws ZendeskException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function searchTicket(?string $query = null)
    {
        $data = [
            'query' => 'type:ticket tags:' . self::DEFAULT_TAG . ' ' . $query
        ];

        return $this->search($data);
    }

    /**
     * @param int $ticketId
     * @param string $status
     * @return mixed
     * @throws ZendeskException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function changeTicketStatus(int $ticketId, string $status)
    {
        $data = [
            'ticket' => [
                'status' => $status
            ]
        ];

        $request = new ZendeskApiAdapter(self::FUNCTION_TICKET . '/' . $ticketId, ZendeskApiAdapter::PUT);
        $request->setData($data);
        $response = $request->sendRequest();
        $responseData = $response->getData();

        if (empty($responseData)) {
            throw new ZendeskException(['message' => 'API: Not found data']);
        }

        return $responseData;
    }

    /**
     * @param string $url
     * @return mixed
     * @throws ZendeskException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function nextPage(string $url)
    {
        $data = RequestHelper::getParamsFromUrl($url);
        return $this->search($data);
    }
}