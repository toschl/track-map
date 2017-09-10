<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
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
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    public $name;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Tour", mappedBy="user")
     */
    public $tours;

    /**
     * @var string
     *
     * @ORM\Column(type="integer")
     */
    public $strava_id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    public $strava_access_token;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    public $strava_username;
}