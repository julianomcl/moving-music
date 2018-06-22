<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Access
 *
 * @ORM\Table(name="access")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AccessRepository")
 */
class Access
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=255)
     */
    private $ip;

    /**
     * @var string|null
     *
     * @ORM\Column(name="spotify_access_token", type="text", nullable=true)
     */
    private $spotifyAccessToken;

    /**
     * @var string|null
     *
     * @ORM\Column(name="spotify_refresh_token", type="text", nullable=true)
     */
    private $spotifyRefreshToken;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set ip.
     *
     * @param string $ip
     *
     * @return Access
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip.
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set spotifyAccessToken.
     *
     * @param string|null $spotifyAccessToken
     *
     * @return Access
     */
    public function setSpotifyAccessToken($spotifyAccessToken = null)
    {
        $this->spotifyAccessToken = $spotifyAccessToken;

        return $this;
    }

    /**
     * Get spotifyAccessToken.
     *
     * @return string|null
     */
    public function getSpotifyAccessToken()
    {
        return $this->spotifyAccessToken;
    }

    /**
     * Set spotifyRefreshToken.
     *
     * @param string|null $spotifyRefreshToken
     *
     * @return Access
     */
    public function setSpotifyRefreshToken($spotifyRefreshToken = null)
    {
        $this->spotifyRefreshToken = $spotifyRefreshToken;

        return $this;
    }

    /**
     * Get spotifyRefreshToken.
     *
     * @return string|null
     */
    public function getSpotifyRefreshToken()
    {
        return $this->spotifyRefreshToken;
    }
}