<?php

namespace Timetorock\LaravelRocketChat\Client;

use Exception;
use Httpful\Exception\ConnectionErrorException;
use Timetorock\LaravelRocketChat\Models\Integration;
use Timetorock\LaravelRocketChat\Exceptions\ChatActionException;
use Timetorock\LaravelRocketChat\Exceptions\IntegrationActionException;

class IntegrationClient extends Client
{
    const API_PATH_INTEGRATION_CREATE = 'integrations.create';
    const API_PATH_INTEGRATION_REMOVE = 'integrations.remove';
    const API_PATH_INTEGRATION_LIST   = 'integrations.list';


    /**
     * Creates an integration, if the callee has the permission.
     *
     * @param Integration $integration
     *
     * @return mixed
     * @throws ChatActionException
     * @throws ConnectionErrorException
     * @throws Exception
     */
    public function create(Integration $integration)
    {
        $postData = $integration->getFillableData();

        if (!in_array(array_keys($postData), ['type', 'name', 'enabled', 'username', 'urls', 'scriptEnabled'])) {
            throw new ChatActionException("Missing required parameter.");
        }

        $response = $this->request()->post($this->apiUrl(self::API_PATH_INTEGRATION_CREATE))
            ->body($postData)
            ->send();

        $integrationData = $this->handleResponse($response, new IntegrationActionException(), ['integration']);

        return new Integration([
            'id'            => $integrationData->_id,
            'type'          => $integrationData->type,
            'name'          => $integrationData->name,
            'enabled'       => $integrationData->enabled,
            'username'      => $integrationData->username,
            'event'         => $integrationData->event,
            'urls'          => $integrationData->urls,
            'scriptEnabled' => $integrationData->scriptEnabled,
            'userId'        => $integrationData->userId,
            'channel'       => $integrationData->channel,
        ]);
    }

    /**
     * Removes an integration from the server.
     * Requires manage-incoming-integrations or manage-own-incoming-integrations permissions
     * to be able to remove incoming integrations and manage-outgoing-integrations or
     * manage-own-outgoing-integrations to be able to remove outgoing integrations.
     *
     * @param Integration $integration
     *
     * @return mixed
     * @throws ConnectionErrorException
     * @throws Exception
     */
    public function remove(Integration $integration)
    {
        $response = $this->request()->post($this->apiUrl(self::API_PATH_INTEGRATION_REMOVE))
            ->body([
                "integrationId" => $integration->getId(),
                "type"          => $integration->getType(),
            ])->send();

        $integrationData = $this->handleResponse($response, new IntegrationActionException(), ['integration']);

        return new Integration([
            'id'            => $integrationData->_id,
            'type'          => $integrationData->type,
            'name'          => $integrationData->name,
            'enabled'       => $integrationData->enabled,
            'username'      => $integrationData->username,
            'event'         => $integrationData->event,
            'urls'          => $integrationData->urls,
            'scriptEnabled' => $integrationData->scriptEnabled,
            'userId'        => $integrationData->userId,
            'channel'       => $integrationData->channel,
        ]);
    }

    /**
     * Lists all of the integrations on the server. Requires at least one integration permission:
     * manage-incoming-integrations, manage-own-incoming-integrations, manage-outgoing-integrations or
     * manage-own-outgoing-integrations.
     *
     * It will return the integrations based on the user permission.
     * It supports the Offset, Count, and Sort Query Parameters along with Query and Fields Query Parameters.
     *
     * @return mixed
     * @throws ConnectionErrorException
     * @throws Exception
     */
    public function list()
    {
        $response = $this->request()->get($this->apiUrl(self::API_PATH_INTEGRATION_LIST))->send();
        return $this->handleResponse($response, new IntegrationActionException());
    }
}