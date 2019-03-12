<?php

namespace AppBundle\Entity;

/**
 * ligneD
 */
class ligneD
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var bool
     */
    private $selection;

    /**
     * @var string
     */
    private $taille;

    /**
     * @var string
     */
    private $quantite;

    /**
    *@var epi
    */
    private $epi;


    /**
    *@var lesDemandes
    */
    private $lesDemandes;



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
     * Set selection
     *
     * @param boolean $selection
     *
     * @return ligneD
     */
    public function setSelection($selection)
    {
        $this->selection = $selection;

        return $this;
    }

    /**
     * Get selection
     *
     * @return bool
     */
    public function getSelection()
    {
        return $this->selection;
    }

    /**
     * Set taille
     *
     * @param string $taille
     *
     * @return ligneD
     */
    public function setTaille($taille)
    {
        $this->taille = $taille;

        return $this;
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
     * Set quantite
     *
     * @param string $quantite
     *
     * @return ligneD
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get epi
     *
     * @return string
     */
    public function getQuantite()
    {
        return $this->quantite;
    }
    public function setEpi($epi)
    {
        $this->epi = $epi;

        return $this;
    }

    public function getLesDemandes()
    {
        return $this->lesDemandes;
    }

    public function setLesDemandes($lesDemandes)
    {
        $this->lesDemandes = $lesDemandes;

        return $this;
    }
    /**
     * Get epi
     *
     * @return epi
     */
    public function getEpi()
    {
        return $this->epi;
    }
}
