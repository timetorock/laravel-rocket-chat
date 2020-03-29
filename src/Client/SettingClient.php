<?php

namespace Timetorock\LaravelRocketChat\Client;

use Exception;
use Httpful\Exception\ConnectionErrorException;
use Timetorock\LaravelRocketChat\Models\Setting;
use Timetorock\LaravelRocketChat\Exceptions\SettingActionException;

class SettingClient extends Client
{
    const API_PATH_SETTINGS = 'settings/%s';

    /**
     * Gets the setting for the provided _id.
     *
     * @param $settingID
     *
     * @return Setting
     * @throws ConnectionErrorException
     * @throws SettingActionException
     * @throws Exception
     */
    public function get($settingID)
    {
        if (!$settingID) {
            throw new SettingActionException('setting ID not specified');
        }

        $response = $this->request()->get($this->apiUrl(sprintf(self::API_PATH_SETTINGS, $settingID)))->send();

        $settingData = $this->handleResponse($response, new SettingActionException());

        return new Setting([
            'id' => $settingData->_id,
            'value' => $settingData->value,
        ]);
    }

    /**
     * Updates the setting for the provided _id.
     *
     * The _id of a setting is the first argument of the RocketChat.settings.add method used in
     * Rocket.Chat/packages/rocketchat-lib/server/startup/settings.js (among other files).
     * For example, the following code in that settings.js file
     *
     * @param Setting $setting
     *
     * @return $this
     * @throws ConnectionErrorException
     * @throws SettingActionException
     * @throws Exception
     */
    public function update(Setting $setting)
    {
        if (!$setting->getId()) {
            throw new SettingActionException('setting ID is not specified');
        }

        $response = $this->request()->post($this->apiUrl(sprintf(self::API_PATH_SETTINGS, $setting->getId())))
            ->body(['value' => $setting->getValue()])
            ->send();

        return $this->handleResponse($response, new SettingActionException());
    }
}