<?php

namespace AppBundle\Service;

use LastFmApi\Api\AuthApi;
use LastFmApi\Api\ArtistApi;
use LastFmApi\Api\GeoApi;
use LastFmApi\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LastFm
{
    private $apiKey;
    private $artistApi;
    private $geoApi;

    public function __construct(ContainerInterface $container)
    {
        $this->apiKey = $container->getParameter('last_fm_api_key');;
        try {
            $auth = new AuthApi('setsession', array(
                'apiKey' => $this->apiKey,
            ));
            $this->artistApi = new ArtistApi($auth);
            $this->geoApi = new GeoApi($auth);
        } catch (InvalidArgumentException $e) {
        }
    }

    /**
     * @param $artist
     * @return mixed
     */
    public function getBio($artist)
    {
        $artistInfo = $this->artistApi->getInfo(array("artist" => $artist));

        return $artistInfo['bio'];
    }

    /**
     * @param $country
     * @param null $limit
     * @param null $page
     * @return mixed
     */
    public function getTopArtists($country, $limit = null, $page = null)
    {
        try {
            $topTracksInfo = $this->geoApi->getTopArtists(array(
                'country' => $country,
                'limit' => $limit,
                'page' => $page
            ));

            return $topTracksInfo;
        } catch (InvalidArgumentException $e) {
        }
    }

    /**
     * @param $country
     * @param null $location
     * @param null $limit
     * @param null $page
     * @return mixed
     */
    public function getTopTracks($country, $location = null, $limit = null, $page = null)
    {
        try {
            $topTracksInfo = $this->geoApi->getTopTracks(array(
                'country' => $country,
                'location' => $location,
                'limit' => $limit,
                'page' => $page
            ));

            return $topTracksInfo;
        } catch (InvalidArgumentException $e) {
        }
    }
}
