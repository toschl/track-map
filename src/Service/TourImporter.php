<?php

namespace App\Service;

use App\Entity\Sport;
use App\Entity\User;
use App\Entity\Tour;
use Doctrine\ORM\EntityManager;
use Strava\API\Client;
use Strava\API\Service\REST;
use Symfony\Component\Validator\Constraints\DateTime;

class TourImporter
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function importForUser(User $user)
    {
        try {
            $token = $user->getStravaAccessToken();
            $adapter = new \Pest('https://www.strava.com/api/v3');
            $service = new REST($token, $adapter);
            $client = new Client($service);

            $page = 1;
            $per_page = 100;
            $next_page = true;
            while ($next_page == true) {
                $activities = $client->getAthleteActivities(
                  null,
                  null,
                  $page,
                  $per_page
                );
                $this->saveActivities($user, $activities);

                if (count($activities) !== $per_page) {
                    $next_page = false;
                } else {
                    $page++;
                }
            }
        } catch (Exception $e) {
            // Failed
        }
    }

    protected function saveActivities(User $user, array $activities)
    {
        $tourRepository = $this->em->getRepository(Tour::class);
        $sportRepository = $this->em->getRepository(Sport::class);
        foreach ($activities as $activity) {
            $tour = $tourRepository->findOneBy([
                'user' => $user->getId(),
                'remote_id' => $activity['id']
            ]);
            if (!$tour) {
                $tour = new Tour();
                $tour->user = $user;
                $tour->remote_id = $activity['id'];
            }
            $sport = $sportRepository->findOneBy([
              'name' => $activity['type'],
            ]);
            if (!$sport) {
                $sport = new Sport();
                $sport->name = $activity['type'];
                $this->em->persist($sport);
                $this->em->flush();
            }
            $tour->average_speed = $activity['average_speed'];
            $tour->sport = $sport;
            $tour->name = $activity['name'];
            $tour->public = $activity['private'] ? false : true;
            $tour->distance = intval($activity['distance']);
            $tour->moving_time = intval($activity['moving_time']);
            $tour->elevation_gain = intval($activity['total_elevation_gain']);
            $tour->average_speed = floatval($activity['average_speed']);
            $tour->max_speed = floatval($activity['max_speed']);
            $tour->start_date = new \DateTime($activity['start_date_local']);
            $tour->map_polyline = $activity['map']['summary_polyline'];

            $this->em->persist($tour);
        }
        $this->em->flush();
    }
}
