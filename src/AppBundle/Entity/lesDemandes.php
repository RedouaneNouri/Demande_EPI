<?php
namespace AppBundle\Entity;

use AppBundle\Entity\Techniciens;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * lesDemandes
 */
class lesDemandes
{
    //public const VALIDEE = 1;
    //public const REFUSEE = 2;
    /**
     * @var int
     */
    private $id;

    /**
    *@var Techniciens
    */
    private $technicien;

    /**
    *@var user
    */
    private $demandeur;

    /**
    *
    *@var TextType
    */
    private $commentaire;

    /**
    *@var DateTime
    */

    private $date;

    /**
    *
    *@var ligneD
    */

    private $ligneD;

    /**
    *@var int
    */
    private $valide;

    public function __construct()
    {
        $this->ligneD  = new ArrayCollection();
        $this->date   = new \DateTime();
        $this->valide = 0;
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
     * Get technicien
     *
     * @return Techniciens
     */
    public function getTechnicien()
    {
        return $this->technicien;
    }
    public function setTechnicien($technicien)
    {
        $this->technicien = $technicien;
    }
    public function getDemandeur()
    {
        return $this->demandeur;
    }
    public function setDemandeur($demandeur)
    {
        $this->demandeur = $demandeur;
    }
    public function getCommentaire()
    {
        return $this->commentaire;
    }
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;
    }
    public function getLigneD()
    {
        return $this->ligneD;
    }
    public function setLigneD($ligneD)
    {
        $this->ligneD = $ligneD;
    }

    public function addLigneD($ligneD)
    {
        $ligneD->setLesDemandes($this);
        $this->ligneD[]=$ligneD;
    }
    public function removeLigneD($ligneD)
    {
        $this->ligneD->removeElement($ligneD);
        $ligneD->setLesDemandes(null);
    }

    public function setDate()
    {
        $this->date = new \DateTime();
    }
    public function getDate()
    {
        return  $this->date;
    }

    public function isValide()
    {
        return $this->valide;
    }
    public function setValide($valeur)
    {
        $this->valide = $valeur;
    }
}
