<?php

/**
 * Description of Club
 *
 * @author Tissot David
 */


//autoloader
/**/
spl_autoload_extensions(".php");
spl_autoload_register();

//Classe Model récupérant les données de la BD
class Model {

    private $_dbConnexion;

    public function __construct() {
        $this->_dbConnexion = new Database('localhost', 'root', '', 'FFBSQ');
    }

    public function getClubList() {
        $listClubs = array();
        $clubs = $this->_dbConnexion->requete("SELECT * FROM Club");
        $licences = $this->_dbConnexion->requete("SELECT * FROM Licence");
        foreach ($clubs as $club) {
            $tempClub = new Club($club->id, $club->nom, $club->adresse, $club->email);
            foreach ($licences as $licence) {
                if ($licence->idClub == $tempClub->getId()) {
                    $tempClub->ajouterLicence(new Licence($licence->numero, $licence->nomResp, $licence->prenomResp, $licence->idClub));
                }
            }
            array_push($listClubs, $tempClub);
        }
        return $listClubs;
    }

    public function getClub($id) {
        $listClubs = $this->getClubList();
        foreach ($listClubs as $club) {
            if ($club->getId() == $id) {
                return $club;
            }
        }
    }
        
    public function rechercherClub($nom) {
        $listClubs = $this->getClubList();
        $listClubRecherche = array();
        foreach ($listClubs as $club) {
            // Doc : http://php.net/manual/fr/function.stristr.php
            if ( stristr($club->getNom(),$nom)!=false) {
                $listClubRecherche[] = $club;
            }
        }
        return $listClubRecherche;
    }

}

?>