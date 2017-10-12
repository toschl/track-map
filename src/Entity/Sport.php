<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sport
 *
 * @ORM\Table(name="sport")
 * @ORM\Entity(repositoryClass="App\Repository\SportRepository")
 */
class Sport
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
     * @ORM\OneToMany(targetEntity="Tour", mappedBy="sport")
     */
    public $tours;

    public function getId()
    {
        return $this->id;
    }
}
