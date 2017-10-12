<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements AdvancedUserInterface
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
    public $username;

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

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getRoles()
    {
        return array('ROLE_USER');
    }

    public function getPassword()
    {
    }

    public function getSalt()
    {
    }

    public function eraseCredentials()
    {
    }

    public function getStravaAccessToken()
    {
        return $this->strava_access_token;
    }

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isEnabled()
    {
        return true;
    }
}
