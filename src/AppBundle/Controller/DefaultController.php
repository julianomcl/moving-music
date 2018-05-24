<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Country;
use AppBundle\Service\LastFm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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

        $country = 'Brazil';

        if ($request->isMethod('post')) {
            $country = $request->get('country', $country);
        }

        $topTracks = $lastFm->getTopTracks($country);

        return $this->render('tracks/index.html.twig', [
            'country' => $country,
            'countries' => $countries,
            'top_tracks' => $topTracks
        ]);
    }
}
