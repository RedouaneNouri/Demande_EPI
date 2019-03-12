<?php

namespace AppBundle\Entity;

/**
 * epi
 */
class epi
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $refMaximo;

    /**
     * @var string
     */
    private $description;

    /**
     * @var text
     */
    private $refLyreco;

    /**
     * @var string
     */
    private $taille;

    /**
     * @var float
     */
    private $puht;

    /**
    *@var int
    */
    private $actif;

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
     * Set refMaximo
     *
     */
    public function setRefMaximo($refMaximo)
    {
        $this->refMaximo = $refMaximo;
    }

    /**
     * Get refMaximo
     *
     * @return int
     */
    public function getRefMaximo()
    {
        return $this->refMaximo;
    }

    /**
     * Set description
     *
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set refLyreco
     *
     */
    public function setRefLyreco($refLyreco)
    {
        $this->refLyreco = $refLyreco;
    }

    /**
     * Get refLyreco
     *
     * @return string
     */
    public function getRefLyreco()
    {
        return $this->refLyreco;
    }

    /**
     * Set taille
     *
     */
    public function setTaille($taille)
    {
        $this->taille = $taille;
    }

    /**
     * Get taille
     *
     * @return string
     */
    public function getTaille()
    {
        return $this->taille;
    }

    /**
     * Set puht
     *
     */
    public function setPuht($puht)
    {
        $this->puht = $puht;
    }

    /**
     * Get puht
     *
     * @return float
     */
    public function getPuht()
    {
        return $this->puht;
    }

    public function __toString()
    {
        return (string)$this->refMaximo;
    }

    public function setInactif()
    {
        $this->actif = 0;
    }

    public function setActif()
    {
        $this->actif = 1;
    }

    public function isActif()
    {
        return $this->actif ;
    }
}
