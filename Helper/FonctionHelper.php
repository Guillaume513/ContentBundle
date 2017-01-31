<?php

namespace ContentBundle\Helper;
use Doctrine\ORM\EntityManager;

/**
 * Created by PhpStorm.
 * User: younivers
 * Date: 01/09/16
 * Time: 10:03
 */
class FonctionHelper
{
    private $entityManager;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function clean_url($name) {
        // on supprime les espaces de fin, on remplace les autres espaces par des tiret et on remplace les ' par des tirets
        $nom = str_replace("'", '_', str_replace(" ", '_', rtrim($name)));

        // on remplace les virgules par tirets
        $nom = str_replace(",", '_', $nom);

        // on remplace les - par _
        $nom = str_replace("-", '_', $nom);

        // on remplace les ” par rien n'est pas (") ne pas elever
        $nom = str_replace('”', '', $nom);

        // on remplace les “ par rien signe différent du dessous (n'est pas ") ne pas enlever
        $nom = str_replace('“', '', $nom);

        // on remplace les “ par rien
        $nom = str_replace('»', '', $nom);

        // on remplace les “ par rien
        $nom = str_replace('«', '', $nom);

        // on remplace les . par rien
        $nom = str_replace(".", '', $nom);

        // On remplace les caractère spéciaux par des tirets
        $nom = strtr($nom,' "!@#$%?&*()+*','______________');

        // On supprime les accents
        // $nom = strtr($nom,"àáâãäçìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ", "aaaaaciiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY");
        $nom = $this->str_to_noaccent($nom);

        // On met tous en minsucule
        $nom = strtolower($nom);

        return ucfirst($nom);

        // Do what you need, $this->entityManager holds a reference to your entity manager
    }

    private function str_to_noaccent($str)
    {
        $url = $str;
        $url = preg_replace('#Ç#', 'C', $url);
        $url = preg_replace('#ç#', 'c', $url);
        $url = preg_replace('#è|é|ê|ë#', 'e', $url);
        $url = preg_replace('#È|É|Ê|Ë#', 'E', $url);
        $url = preg_replace('#à|á|â|ã|ä|å#', 'a', $url);
        $url = preg_replace('#@|À|Á|Â|Ã|Ä|Å#', 'A', $url);
        $url = preg_replace('#ì|í|î|ï#', 'i', $url);
        $url = preg_replace('#Ì|Í|Î|Ï#', 'I', $url);
        $url = preg_replace('#ð|ò|ó|ô|õ|ö#', 'o', $url);
        $url = preg_replace('#Ò|Ó|Ô|Õ|Ö#', 'O', $url);
        $url = preg_replace('#ù|ú|û|ü#', 'u', $url);
        $url = preg_replace('#Ù|Ú|Û|Ü#', 'U', $url);
        $url = preg_replace('#ý|ÿ#', 'y', $url);
        $url = preg_replace('#Ý#', 'Y', $url);

        return $url;
    }


    public function geocoder($adresse, $cp, $ville) {
        $gps = null;
        $address = $adresse. ' ' .$cp.' '.$ville;

        // On prépare l'URL du géocodeur
        $geocoder = "http://maps.googleapis.com/maps/api/geocode/json?address=%s&sensor=false";

        // Pour cette exemple, je vais considérer que ma chaîne n'est pas
        // en UTF8, le géocoder ne fonctionnant qu'avec du texte en UTF8
        $url_address = utf8_encode($address);

        // Penser a encoder votre adresse
        $url_address = urlencode($url_address);

        // On prépare notre requête
        $query = sprintf($geocoder,$url_address);

        // On interroge le serveur
        $results = file_get_contents($query);

        // On affiche le résultat
        $reponse_array = json_decode($results);

        if (!empty($reponse_array->results)) {
            if($reponse_array->results[0])
            {
                $lat = $reponse_array->results[0]->geometry->location->lat;
                $lng = $reponse_array->results[0]->geometry->location->lng;

                $gps = $lat.','.$lng;
            }
        }


        return $gps;
    }

    function intitule_mois($mois){
        $nom = "";

        if ($mois == 1) {
            $nom = "Janvier";
        } else if ($mois == 2) {
            $nom = "Février";
        } else if ($mois == 3) {
            $nom = "Mars";
        } else if ($mois == 4) {
            $nom = "Avril";
        } else if ($mois == 5) {
            $nom = "Mai";
        } else if ($mois == 6) {
            $nom = "Juin";
        } else if ($mois == 7) {
            $nom = "Juillet";
        } else if ($mois == 8) {
            $nom = "Aoùt";
        } else if ($mois == 9) {
            $nom = "Septembre";
        } else if ($mois == 10) {
            $nom = "Octobre";
        } else if ($mois == 11) {
            $nom = "Novembre";
        } else if ($mois == 12) {
            $nom = "Décembre";
        }

        return $nom;
    }

    public function getResponse ($id) {
        if ($id == 0) {
            return 'Pas de réponse';
        } else if ($id == 1) {
            return 'Congé refusé';
        } else {
            return 'Congé accepté';
        }
    }
}