<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tour
 *
 * @ORM\Table(name="tour")
 * @ORM\Entity(repositoryClass="App\Repository\TourRepository")
 */
class Tour
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="tours")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    public $user;

    /**
     * @ORM\ManyToOne(targetEntity="Sport", inversedBy="tours")
     * @ORM\JoinColumn(name="sport_id", referencedColumnName="id")
     */
    public $sport;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    public $name;

    /**
     * @ORM\Column(type="decimal", scale=2)
     */
    public $distance;

    /**
     * @ORM\Column(type="integer")
     */
    public $moving_time;

    /**
     * @ORM\Column(type="integer")
     */
    public $elevation_gain;

    /**
     * @ORM\Column(type="decimal", scale=2)
     */
    public $average_speed;

    /**
     * @ORM\Column(type="decimal", scale=2)
     */
    public $max_speed;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="datetimetz")
     */
    public $start_date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="country", type="string")
     */
    public $country;

    /**
     * @ORM\Column(type="text")
     */
    public $map_polyline;
}