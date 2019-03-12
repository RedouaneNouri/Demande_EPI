<?php

namespace AppBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;


 /**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TechniciensRepository")
 */

/**
 * Techniciens
 */
class Techniciens
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $bT;

    /**
     * @var string
     */
    private $prenom;

    /**
     * @var string
     */
    private $nom;


    /**
     * @var lesDemandes
     */
    private $lesDemandes;

    /**
    *@var int
    */
    private $actif = 1;


    public function __construct() {
      $this->lesDemandes = new ArrayCollection();
    }
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set bT
     *
     */
    public function setBT($bT)
    {
        $this->bT = $bT;

    }

    /**
     * Get bT
     *
     * @return int
     */
    public function getBT()
    {
        return $this->bT;
    }

    /**
     * Set prenom
     *
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }
    /**
    *__toString
    *
    *@return string
    */

    public function __toString() {
        return (string)$this->id;
    }

    public function setInactif(){
      $this->actif = 0;
    }
    public function setActif(){
      $this->actif = 1;
    }

    public function isActif(){
      return $this->actif;
    }
}
