<?php

/**
 * Description of Club
 *
 * @author Tissot David
 */
class Club {

    private $id;
    private $nom;
    private $adresse;
    private $email;
    private $lesLicences;

    public function __construct($p_id, $p_nom, $p_adresse, $p_email) {
        $this->id = $p_id;
        $this->nom = $p_nom;
        $this->adresse = $p_adresse;
        $this->email = $p_email;
    }

    public function ajouterLicence($p_laLicence) {
        $this->lesLicences[] = $p_laLicence;
    }

    public function getLicences() {
        return $this->lesLicences;
    }

    public function getNom() {
        return $this->nom;
    }

    public function getAdresse() {
        return $this->adresse;
    }

    public function getId() {
        return $this->id;
    }

}
