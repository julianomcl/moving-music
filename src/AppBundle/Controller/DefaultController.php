<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Access;
use AppBundle\Entity\Country;
use AppBundle\Service\LastFm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @param LastFm $lastFm
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function topTracksAction(Request $request, LastFm $lastFm)
    {
        $em = $this->getDoctrine()->getManager();
        $countries = $em->getRepository(Country::class)->findBy(['active' => 1]);

        $country = $request->get('country', 'Brazil');
        $request->getSession()->set('country', $country);
        $request->getSession()->save();
        $topTracks = $lastFm->getTopTracks($country);

        $this->getAccess($request->getClientIp());

        return $this->render('tracks/index.html.twig', [
            'country' => $country,
            'countries' => $countries,
            'top_tracks' => $topTracks,
        ]);
    }

    /**
     * @Route("/spotify", name="spotify_playlist")
     * @param Request $request
     * @param LastFm $lastFm
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function spotifyAction(Request $request, LastFm $lastFm)
    {
        $em = $this->getDoctrine()->getManager();

        $country = $request->getSession()->get('country', "Brazil");
        $topTracks = $lastFm->getTopTracks($country);

        $access = $this->getAccess($request->getClientIp());

        $session = new Session(
            $this->getParameter('spotify_client_id'),
            $this->getParameter('spotify_client_secret'),
            $this->getParameter('spotify_redirect')
        );
        $api = new SpotifyWebAPI();
        $code = $request->get('code');
        if (!empty($code)) {
            $session->requestAccessToken($code);
            $accessToken = $session->getAccessToken();
            $refreshToken = $session->getRefreshToken();
            $access
                ->setSpotifyAccessToken($accessToken)
                ->setSpotifyRefreshToken($refreshToken);

        } else {
            $options = [
                'scope' => [
                    'user-library-read',
                    'user-library-modify',
                    'playlist-read-private',
                    'playlist-modify-public',
                    'playlist-modify-private',
                    'playlist-read-collaborative',
                    'user-read-recently-played',
                    'user-top-read',
                    'user-read-private',
                    'user-read-email',
                    'user-read-birthdate',
                    'streaming',
                    'user-modify-playback-state',
                    'user-read-currently-playing',
                    'user-read-playback-state',
                    'user-follow-modify',
                    'user-follow-read',
                    'user-read-email',
                ],
            ];

            header('Location: ' . $session->getAuthorizeUrl($options));
            die();
        }

        if ($access->getSpotifyAccessToken() != null) {
            $api->setAccessToken($access->getSpotifyAccessToken());

            $spotifyTracks = [];
            foreach ($topTracks as $track) {
                $spotifyTrack = $api->search(($track['name']." - ".$track['artist']['name']), 'track');
                if (isset($spotifyTrack->tracks->items[0])) {
                    $spotifyTracks[] = $spotifyTrack->tracks->items[0]->id;
                }
            }

            $playlist = $api->createUserPlaylist($api->me()->id, [
                'name' =>  ('Top 50 - ' . $country . ' (' . (new \DateTime())->format('Y-m-d') . ')')
            ]);
            $api->addUserPlaylistTracks($api->me()->id, $playlist->id, $spotifyTracks);

            $this->addFlash(
                'notice',
                'Your playlist has been created, please check your Spotify!'
            );
        }

        $em->flush();

        return $this->redirectToRoute('homepage');
    }

    /**
     * @param $ip
     * @return Access
     */
    private function getAccess($ip)
    {
        $em = $this->getDoctrine()->getManager();
        $access = $em->getRepository(Access::class)->findOneBy(['ip' => $ip]);
        if ($access == null) {
            $access = new Access();
            $access->setIp($ip);
            $em->persist($access);
        }
        $access->setUpdatedAt(new \DateTime());
        $em->flush();

        return $access;
    }
}
