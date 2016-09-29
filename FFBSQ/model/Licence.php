<?php

/**
 * Description of Club
 *
 * @author Tissot David
 */
class Licence {

    private $numero;
    private $nom;
    private $prenom;
    private $idClub;

    public function __construct($p_numero, $p_nom, $p_prenom, $p_idClub) {
        $this->numero = $p_numero;
        $this->nom = $p_nom;
        $this->prenom = $p_prenom;
        $this->idClub = $p_idClub;
    }

    public function getDescription() {
        return $this->numero . " " . $this->nom . " " . $this->prenom;
    }

    public function getIdClub() {
        return $this->idClub;
    }

}
