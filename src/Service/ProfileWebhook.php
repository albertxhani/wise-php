<?php

namespace TransferWise\Service;

class ProfileWebhookService extends Service
{

    private $_version = "2.0.0";

    /**
     * Create a profile webhook
     *
     * @param Array $params Params needed to create webhook
     * @param Int   $id     Profile id, if not given then the default id will be used
     *
     * @return Response
     */
    public function create($params, $id = false)
    {
        $profile_id = $this->mustHaveProfileId($id);

        return $this->client->request(
            "POST",
            "v3/profiles/{$profile_id}/subscriptions",
            [
                "name" => $params["name"],
                "trigger_on" => $params["event"],
                "delivery" => [
                    "version" => $this->_version,
                    "url" => $params["url"]
                ]
            ]
        );
    }

    /**
     * Delete profile webhook
     *
     * @param Int $id         Webhook identifier
     * @param Int $profile_id Profile id
     *
     * @return Response
     */
    public function delete($id, $profile_id = false)
    {
        $profile_id = $this->mustHaveProfileId($profile_id);

        return $this->client->request(
            "DELETE",
            "v3/profiles/{$profile_id}/subscriptions/{$id}"
        );
    }

    /**
     * Retrieve profile webhook
     *
     * @param Int $id         Webhook identifier
     * @param Int $profile_id Profile Id
     *
     * @return Response
     */
    public function retrieve($id, $profile_id = false)
    {
        $profile_id = $this->mustHaveProfileId($profile_id);

        return $this->client->require(
            "GET",
            "v3/profiles/{$profile_id}/subscriptions/{$id}"
        );
    }

    /**
     * Get the list of webhooks for the given profile id
     *
     * @param Int $profile_id Profile id
     *
     * @return Response
     */
    public function list($profile_id = false)
    {
        $profile_id = $this->mustHaveProfileId($profile_id);

        return $this->client->require(
            "GET",
            "v3/profiles/{$profile_id}/subscriptions"
        );
    }
}
