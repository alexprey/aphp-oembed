<?php
/**
 * @author Alexey Prey Mulyukin
 * Date: 09.09.2015
 * Time: 0:23
 */

/**
 * Class represents a media content provider.
 */
class OEmbedProvider {
    private $contentProviderEndpoint;

    /**
     * Create a new instance of the embed provider.
     * @param $providerConfig
     */
    public function __construct($providerConfig) {
        $this->contentProviderEndpoint = $providerConfig['endpoint'];
    }

    /**
     * Parse response from media content provider represents as json object.
     * @param $response - json object
     * @return OEmbedData
     */
    protected function ParseJsonResponse($response) {
        $embedData = new OEmbedData();

        $embedData->type = $response['type'];
        $embedData->url = $response['url'];
        $embedData->author_name = $response['author_name'];
        $embedData->author_url = $response['author_url'];
        $embedData->cache_age = $response['cache_age'];
        $embedData->height = $response['height'];
        $embedData->width = $response['width'];
        $embedData->provider_name = $response['provider_name'];
        $embedData->provider_url = $response['provider_url'];
        $embedData->thumbnail_height = $response['thumbnail_height'];
        $embedData->thumbnail_width = $response['thumbnail_width'];
        $embedData->title = $response['title'];
        $embedData->version = $response['version'];

        if ($embedData->type == OEmbedDataType::Photo) {
            $embedData->html =
                '<a href="' . $response['url'] . '" title="by ' . $response['author_name'] . '">' .
                    '<img src="' . $response['thumbnail_url'] . '" />' .
                '</a>';
            ;
        } else {
            $embedData->html = $response['html'];
        }

        return $embedData;
    }

    /**
     * Get embed data from this media content provider.
     * @param $url
     * @return OEmbedData|null
     */
    public function GetEmbedData($url) {
        $targetUrl = $this->contentProviderEndpoint . '?format=json&url=' . $url;

        // Request a oEmbed content
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $targetUrl);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $result = curl_exec($ch);

        // Verify response
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($status_code != 200) {
            return null;
        }

        // Parse json response
        $response = json_decode($result, true);

        if ($response == null) {
            return null;
        }

        $embedData = $this->ParseJsonResponse($response);

        return $embedData;
    }
}