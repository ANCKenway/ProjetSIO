<?php

/**
 * Extension pour extraire et modifier les données d'une base de données MySQL
 * @remark utilise le langage SQL pour soumettre les requêtes et des tableaux pour récupérer les données
 * @author Philippe Cosson <philippe.cosson@ac-grenoble.fr>
 * @since version extension mysqli depuis 20/10/2015
 * @copyright ©PCo2007-2015
 */
class BDMySQLi {

    static private $_cnn = null;
    
    /**
     * @var boolean affiche ou non des informations sur l'erreur rencontrée
     */
    static public $debug = true;

    static private function _getTrace() {
        $traces = debug_backtrace();
        foreach ($traces as $trace) {
            if (isset($trace['file'])) {
                if ($trace['file'] != __FILE__) {
                    return "{$trace['file']} [{$trace['line']}]";
                }
            }
        }
    }

    /**
     * affiche une alerte d'erreur et arrête l'exécution !
     * @param string $uneFxOrigine la méthode à l'origine du problème
     * @param string $desParams les paramètres erronés
     * @param string $unMsgErr le message d'erreur
     */
    static private function _alerter($uneFxOrigine, $desParams, $unMsgErr) {
        if (!headers_sent()) {
            header('Content-Type: text/html; charset=utf-8');
        }
        $classe = __CLASS__;
        $fichier = self::_getTrace();
        echo <<<HTML
        
    <div id="bdmysqli-erreur">
        <style>
            #bdmysqli-erreur .titre {color: white;background-color: #400;padding: 10px;}
            #bdmysqli-erreur .alerte {color: red; border: solid red 1px; padding: 5px;}
            #bdmysqli-erreur .ref {margin-bottom:-15px;}
            #bdmysqli-erreur .cercle{color:#fff; background:#f40; margin:0.3em;
                display: inline-block; width:1em; height:1em; line-height: 1em;
                text-align:center; border-radius:50%;
            }
            #bdmysqli-erreur .danger {color: black; background-color: white;}
            #bdmysqli-erreur .aide {background:green;border-radius:25%;}
            #bdmysqli-erreur .exit {background:red;margin-right:0px;}
        </style>
        <p class="ref"><span class="cercle">⤷</span><code>$fichier</code></p>
        <p class="titre">
        <span class="cercle danger">↯</span>{$classe}::{$uneFxOrigine}("<var>$desParams</var>")</p>           
        <p class="alerte"><span class="cercle aide">?</span>{$unMsgErr}</p>
        <h1><span class='cercle exit'>E</span>xit !</h1>
   </div>
HTML;
        exit(-1);
    }

    /**
     * gère les erreurs de connexion à la BD
     */
    static private function _gererErreurCnn($unServeur, $unUtil, $unMDP, $uneBase) {
        $errNo = self::$_cnn->connect_errno;
        $errMySQL = '<a href="http://dev.mysql.com/doc/refman/5.6/en/error-messages-server.html">['
                . $errNo . ']</a> ' . utf8_encode(self::$_cnn->connect_error);
        if (self::$debug) {
            self::_alerter('connecter', "'$unServeur', '$unUtil', '$unMDP', '$uneBase'", $errMySQL);
        } else {
            throw new Exception($errMySQL, $errNo);
        }
    }

    /**
     * gère les erreurs SQL éventuelles
     * @param $unSQL
     * @return Exception
     */
    static private function _gererErreur($uneFxOrigine, $desParams) {
        $errNo = self::$_cnn->errno;
        $errMySQL = '<a href="http://dev.mysql.com/doc/refman/5.6/en/error-messages-server.html">['
                . $errNo . ']</a> ' . utf8_encode(self::$_cnn->error);
        if ($errNo) {
            if (self::$debug) {
                self::_alerter($uneFxOrigine, $desParams, $errMySQL);
            } else {
                throw new Exception($errMySQL, $errNo);
            }
        }
    }

    /**
     * vérifie l'existance d'une connexion
     * @return object connexion
     */
    static private function _getCnn() {
        if (self::$_cnn != null) {
            return self::$_cnn;
        }
        self::_alerter('connecter', '?,?,?,?', 'Connection obligatoire oubliée ?!');
    }

    /**
     * définit les paramètres de connexion
     * @param string $unServeur le serveur MySQL
     * @param string $unUtil l'utilisateur
     * @param string $unMDP le mot de passe
     * @param string $uneBase la base de données
     * @example BDMySQLi::connecter('localhost','root', 'secret', 'mrbs');
     */
    static public function connecter($unServeur, $unUtil, $unMDP, $uneBase) {
        @self::$_cnn = new mysqli($unServeur, $unUtil, $unMDP, $uneBase);
        if (self::$_cnn->connect_errno) {
            self::_gererErreurCnn($unServeur, $unUtil, $unMDP, $uneBase);
        } else {
            if (!@self::$_cnn->set_charset("utf8")) {
                self::_alerter('set_charset("utf8")', "Erreur lors du chargement du jeu de caractères utf8", self::$_cnn->error);
            }
        }
    }

    /**
     * protège une chaîne SQL
     * @param string $uneChaine la chaîne à protéger
     * @return string la chaîne protégée
     */
    static public function proteger($uneChaine) {
        return self::_getCnn()->real_escape_string($uneChaine);
    }

    /**
     * extrait des lignes d'enregistrement (N lignes par N colonnes)
     * @param string $unSQLSelect requête SQL avec SELECT à exécuter
     * @param boolean $estVisible visualisation du résultat (par défaut FALSE)
     * @remark la visualisation permet de vérifier le résultat dans une phase de débogage seulement ...
     * @return array[][] tableau associatif [noLigne]['nomColonne'] représentant les lignes extraites
     */
    static public function extraireNxN($unSQLSelect, $estVisible = false) {
        $je = self::_getCnn()->query($unSQLSelect);
        self::_gererErreur(__FUNCTION__, $unSQLSelect);
        $result = $je->fetch_all(MYSQLI_ASSOC);
        $je->free();
        if ($estVisible) {
            VueTab::afficherNxN($unSQLSelect, $result);
        }
        return $result;
    }

    /**
     * extrait une ligne d'enregistrement (1 ligne par N colonnes)
     * @param string $unSQLSelect requête SQL avec SELECT à exécuter
     * @param boolean $estVisible visualisation du résultat (par défaut FALSE)
     * @remark la visualisation permet de vérifier le résultat dans une phase de débogage seulement ...
     * @return array[] tableau associatif ['nomColonne'] représentant la ligne extraite
     */
    static public function extraire1xN($unSQLSelect, $estVisible = false) {
        $je = self::_getCnn()->query($unSQLSelect);
        self::_gererErreur(__FUNCTION__, $unSQLSelect);
        $result = $je->fetch_assoc();
        if (!$result) {
            $result = array();
        }
        $je->free();
        if ($estVisible) {
            VueTab::afficher1xN($unSQLSelect, $result);
        }
        return $result;
    }

    /**
     * extrait un champ unique
     * @param string $unSQLSelect requête SQL avec SELECT à exécuter
     * @param boolean $estVisible visualisation du résultat (par défaut FALSE)
     * @remark la visualisation permet de vérifier le résultat dans une phase de débogage seulement ...
     * @return string variable représentant un champ d'un enregistrement ou null si aucun !
     */
    static public function extraire1($unSQLSelect, $estVisible = false) {
        $je = self::_getCnn()->query($unSQLSelect);
        self::_gererErreur(__FUNCTION__, $unSQLSelect);
        $result = $je->fetch_array();
        $je->free();
        if ($result) {
            $result = $result[0];
        }
        if ($estVisible) {
            VueTab::afficher1($unSQLSelect, $result);
        }
        return $result;
    }

    /**
     * exécute une requête 'action' (INSERT, UPDATE, DELETE)
     * @param $unSQLAction requête SQL à exécuter
     * @return boolean true si OK, sinon false en cas de problème ...
     */
    static public function executerAction($unSQLAction) {
        $result = self::_getCnn()->query($unSQLAction);
        self::_gererErreur(__FUNCTION__, $unSQLAction);
        return $result;
    }

}

/**
 * @ignore
 */
class VueTab {

    private static $_event = 'onmouseover="info(this)" onmouseout="info(this)"';
    private static $_setCssJs = false;

    static private function _initCssJs() {
        if (!self::$_setCssJs) {
            self::$_setCssJs = true;
            if (!headers_sent()) {
                header('Content-Type: text/html; charset=utf-8');
            }
            echo <<<HTML
    <style>
        table.debug { border-collapse: collapse; font-family: Verdana, Tahoma, Helvetica, Arial; }
        table.debug th { border: none; padding: 5px; color: #ccc; font-weight: lighter; font-family: monospace; }
        table.debug th.index { text-align: right; }
        table.debug td { border: dotted #aaa 1px; padding: 5px; background-color: greenyellow; }
        table.debug td.inter { border: none; padding: 2px; background-color: transparent; }
        table.debug td.nodata { background-color: Khaki; border-radius:75%;text-decoration: line-through;}
        pre { color: #00F; padding: 10px; background: #EEE none repeat scroll 0% 0%;
              border: 1px solid #CCC; border-radius: 5px; word-wrap: normal; overflow: auto; }
        var {color: orange;}
        .cercle{color:#fff; background:#f40; margin:0.3em;
                display: inline-block; width:1em; height:1em; line-height: 1em;
                text-align:center; border-radius:50%;
            }
    </style>
    <script type="text/javascript">
        function info(unElt) {
            var tempo = unElt.innerHTML;
            unElt.innerHTML = unElt.title;
            unElt.title = tempo;
            if (unElt.style.backgroundColor == "") {
               unElt.style.backgroundColor= "gold";         
            }
            else {
               unElt.style.backgroundColor= "";
            }
        }
    </script>    

HTML;
        }
    }

    static private function _testNoData($unResultat) {
        if (count($unResultat) == 0 or $unResultat == null) {
            return "<tr><td class=\"inter nodata\"><span class=\"cercle\">!</span>"
                    . "no data ! </tr></td>\n";
        }
    }

    /**
     * affiche les lignes d'un tableau à 2 dimensions
     * @param string $unSQL la réquête SQL
     * @param array[][] $unResultat les données à afficher
     */
    static public function afficherNxN($unSQL, $unResultat) {
        self::_initCssJs();
        echo <<<HTML
<pre>\$array = BDMySQLi::extraireNxN("<var>$unSQL</var>");
foreach (\$array as \$ligne) {
    foreach (\$ligne as \$colonne) {                
        echo "{\$colonne} | ";
    }
    echo "&lt;br/&gt;\\n";
}</pre>

HTML;
        $event = self::$_event;
        $entete = true;
        $i = 0;
        echo '<table class="debug">';
        foreach ($unResultat as $uneLigne) {
            $noms = "";
            $valeurs = "";
            foreach ($uneLigne as $nom => $valeur) {
                if ($entete) {
                    $noms .= "<th>{$nom}</th>\n";
                }
                $valeur = htmlentities($valeur, ENT_COMPAT, "utf-8");
                $valeurs .= "<td title=\"\$resultat[{$i}]['{$nom}']\" {$event}>{$valeur}</td>\n";
            }
            if ($entete) {
                $entete = false;
                $nbCols = count($uneLigne);
                echo "<tr><th class='index'>↱</th>{$noms}</tr>\n";
            }
            echo "  <tr><th class='index'>{$i}</th>{$valeurs}</tr>";
            echo "<tr><td class='inter' colspan='{$nbCols}'></td></tr>\n";
            $i++;
        }
        echo self::_testNoData($unResultat);
        echo '</table>';
    }

    /**
     * affiche les colonnes d'un tableau à 1 dimension
     * @param string $unSQL la réquête SQL
     * @param array[] $unResultat les données à afficher
     */
    static public function afficher1xN($unSQL, $unResultat) {
        self::_initCssJs();
        echo <<<HTML
<pre>\$array = BDMySQLi::extraire1xN("<var>$unSQL</var>");
foreach (\$array as \$colonne) {               
        echo "{\$colonne}&lt;br/&gt;\\n";
}</pre>

HTML;
        $event = self::$_event;
        echo '<table class="debug">';
        foreach ($unResultat as $nom => $valeur) {
            $valeur = htmlentities($valeur, ENT_COMPAT, "utf-8");
            echo "<tr><th class='index'>$nom</th>\n";
            echo "<td title=\"\$resultat['$nom']\" {$event}>{$valeur}</td></tr>\n";
        }
        echo self::_testNoData($unResultat);
        echo '</table>';
    }

    /**
     * affiche le contenu d'une variable
     * @param string $unSQL la réquête SQL
     * @param string $unResultat la donnée à afficher
     */
    static public function afficher1($unSQL, $unResultat) {
        self::_initCssJs();
        echo <<<HTML
<pre>\$var = BDMySQLi::extraire1("<var>$unSQL</var>");
echo "{\$var}&lt;br/&gt;\\n";
</pre>

HTML;
        $event = self::$_event;
        $valeur = htmlentities($unResultat, ENT_COMPAT, "utf-8");
        echo '<table class="debug">';
        if ($unResultat) {
            echo "<tr><td class=\"scalaire\" title=\"\$var\" {$event}>$valeur</td></tr>\n";
        }
        echo self::_testNoData($unResultat);
        echo '</table>';
    }

}

//test
/*
BDMySQLi::connecter('xlocalhost', 'root', '', 'mrbs');
BDMySQLi::connecter('localhost', 'xroot', '', 'mrbs');
BDMySQLi::connecter('localhost', 'root', 'x', 'mrbs');
BDMySQLi::connecter('localhost', 'root', '', 'xmrbs');

BDMySQLi::connecter('localhost', 'xroot', '', 'mrbs');
BDMySQLi::$debug = FALSE;
$sqlOK = "select area_name, id from xmrbs_area";
$sqlKO = "select area_name, id from mrbs_area where id>999";
 */

