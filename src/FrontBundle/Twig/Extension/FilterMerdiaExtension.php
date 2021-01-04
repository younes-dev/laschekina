<?php
namespace FrontBundle\Twig\Extension;
use Doctrine\ORM\EntityManagerInterface;

class FilterMerdiaExtension extends \Twig_Extension
{


    protected $entityManager;
    // Retrieve doctrine from the constructor
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('filterMedia', array(
                $this,
                'filterMedia'
            )),
            new \Twig_SimpleFilter('getTimeStart', array(
                $this,
                'getTimeStart'
            )),
            new \Twig_SimpleFilter('getDateStart', array(
                $this,
                'getDateStart'
            )),
            new \Twig_SimpleFilter('convertirNbrShow', array(
                $this,
                'convertirNbrShow'
            )),
            new \Twig_SimpleFilter('trueTime', array(
                $this,
                'trueTime'
            )),
            new \Twig_SimpleFilter('verifierPersonnage', array(
                $this,
                'verifierPersonnage'
            )),
            new \Twig_SimpleFilter('contactmessaging', array(
                $this,
                'contactmessaging'
            )),
            new \Twig_SimpleFilter('filterCollectionNews', array(
                $this,
                'filterCollectionNews'
            )),
            new \Twig_SimpleFilter('CollectionPhoto', array(
                $this,
                'CollectionPhoto'
            )),
            new \Twig_SimpleFilter('CollectionMedia', array(
                $this,
                'CollectionMedia'
            )),
            new \Twig_SimpleFilter('alertTitle', array(
                $this,
                'alertTitle'
            )),
            new \Twig_SimpleFilter('getImageDefault', array(
                $this,
                'getImageDefault'
            )),
            new \Twig_SimpleFilter('messaging', array(
                $this,
                'messaging'
            )),
            new \Twig_SimpleFilter('getResponseVideoCasting', array(
                $this,
                'getResponseVideoCasting'
            )),
            new \Twig_SimpleFilter('getPathCastingPagePsotuler', array(
                $this,
                'getPathCastingPagePsotuler'
            )),
            new \Twig_SimpleFilter('datediffFilter', array(
                $this,
                'datediffFilter'
            )),
            new \Twig_SimpleFilter('castingExiste', array(
                $this,
                'castingExiste'
            )),
            new \Twig_SimpleFilter('runHtml', array(
                $this,
                'runHtml'
            )),
            new \Twig_SimpleFilter('likeBook', array(
                $this,
                'likeBook'
            )),
            new \Twig_SimpleFilter('runHtmlDescription', array(
                $this,
                'runHtmlDescription'
            )),
            new \Twig_SimpleFilter('getPathCasting', array(
                $this,
                'getPathCasting'
            ))
        );
    }

    function convertirNbrShow($totla)
    {

        $numbre = 0;
        switch ($totla) {
            case ($totla > 999 and $totla < 1000000):
                $numbre = $totla / 1000;
                $numbre = round($numbre) . 'K';
                break;
            case ($totla > 1000000):
                $numbre = $totla / 1000000;

                $numbre = round($numbre) . 'M';
                break;
            case ($totla < 1000):
                $numbre = $totla;
                break;


        }


        return $numbre;
    }


    public function trueTime($id)
    {


        $date = $this->entityManager->getRepository('BackBundle:ListeDates')->find($id);

        $resultat = false;


        if (strtotime($date->getStarttime()) >= time() or strtotime($date->getEndtime()) >= time()  ) {
            $resultat = true;
        }


        return $resultat;
    }



    public function getTimeStart($id)
    {


        $date         = new \DateTime();
        $dateResultat = null;

        $dateResultat = null;


        $listedates = $this->entityManager->getRepository('BackBundle:ListeDates')->findBy(array(
            "media" => $id,
            'startDate' => $date
        ), array(
            'startDate' => "ASC"
        ));



        if ($listedates != null) {
            foreach ($listedates as $value) {
                if ($dateResultat == null and ( strtotime($value->getStarttime()) >= time() or strtotime($value->getEndtime()) >= time() )) {
                    $dateResultat = $value->getStarttime();
                }

            }

        }


        $listedates = $this->entityManager->getRepository('BackBundle:ListeDates')->findBy(array(
            "media" => $id
        ), array(
            'startDate' => "ASC"
        ));



        if ($dateResultat == null) {

            foreach ($listedates as $value) {
                if ($value->getEndDate() > $date and $value->getStartDate() < $date and ( strtotime($value->getStarttime()) >= time() or strtotime($value->getEndtime()) >= time() )) {
                    $dateResultat = $value->getStarttime();
                }

            }

        }





        return $dateResultat;
    }

    public function getDateStart($id)
    {

        $date = new \DateTime();

        $dateResultat = null;


        $listedates = $this->entityManager->getRepository('BackBundle:ListeDates')->findOneBy(array(
            "media" => $id,
            'startDate' => $date
        ), array(
            'startDate' => "ASC"
        ));



        if ($listedates != null) {
            $dateResultat = $listedates->getStartDate();

        }


        $listedates = $this->entityManager->getRepository('BackBundle:ListeDates')->findBy(array(
            "media" => $id
        ), array(
            'startDate' => "ASC"
        ));



        if ($dateResultat == null) {

            foreach ($listedates as $value) {
                if ($dateResultat == null and $value->getEndDate() > $date and $value->getStartDate() < $date) {
                    $dateResultat = $value->getStartDate();
                }

            }

        }


        /*
        if( $dateResultat == null  ){
        $listedates = $this->entityManager->getRepository ( 'BackBundle:ListeDates' )->getDateMediaCourant($id);
        if( $listedates != null  ){

        foreach ($listedates as $value){
        if(   $dateResultat == null    && $value->getEndDate() > $date  ) {
        $dateResultat = $value->getStartDate() ;
        }

        }

        }

        }



        if( $dateResultat == null  ){
        $listedates = $this->entityManager->getRepository ( 'BackBundle:ListeDates' )->getDateMediaFuteur($id);
        if( $listedates != null  ){

        foreach ($listedates as $value){
        if(   $dateResultat == null    && $value->getEndDate() > $date  ) {
        $dateResultat = $value->getStartDate() ;
        }

        }

        }

        }*/

        return $dateResultat;
    }
    function isDateBetweenDates(\DateTime $date, \DateTime $startDate, \DateTime $endDate)
    {
        return $date >= $startDate && $date <= $endDate;
    }

    public function datediffFilter(\DateTime $dateDebut, \DateTime $dateEnd)
    {


        $diff = $dateDebut->diff($dateEnd);

        return $diff->format('%a');
    }


    function getResponseVideoCasting($idCastingReponse)
    {

        $nbr = 0;

        $reponseVideoCasting = $this->entityManager->getRepository('CastingBundle:ReponseVideoCasting')->findBy(array(
            "castingReponse" => $idCastingReponse
        ));


        return $reponseVideoCasting;

    }



    function likeBook($idbook, $idUser)
    {


        $reponseBookVotes = $this->entityManager->getRepository('BackBundle:BookVotes')->findBy(array(
            "book" => $idbook,
            "user" => $idUser
        ));

        if ($reponseBookVotes != null)
            return 1;
        else
            return 0;

    }


    function runHtmlDescription($textHtml)
    {


        echo strip_tags($textHtml);


    }
    function runHtml($textHtml)
    {


        return strip_tags($textHtml);


    }


    function castingExiste($idCasting, $idmember)
    {

        $nbr = 0;

        $reponseVideoCasting = $this->entityManager->getRepository('CastingBundle:CastingReponse')->findOneBy(array(
            "casting" => $idCasting,
            "member" => $idmember
        ));

        if ($reponseVideoCasting == null)
            return "postuler";
        elseif ($reponseVideoCasting->getStatus() == 1)
            return "valide";
        elseif ($reponseVideoCasting->getStatus() == 0)
            return 'non-valide';

    }


    function getPathCasting($idCastingReponse, $videoCasting, $idmember)
    {

        $nbr = 0;

        $reponseVideoCasting = $this->entityManager->getRepository('CastingBundle:ReponseVideoCasting')->findOneBy(array(
            "castingReponse" => $idCastingReponse,
            "videoCasting" => $videoCasting,
            "member" => $idmember
        ));

        if ($reponseVideoCasting != null)
            return $reponseVideoCasting->getPath();
        else
            return null;

    }


    function getPathCastingPagePsotuler($videoCasting, $idmember)
    {

        $nbr = 0;

        $reponseVideoCasting = $this->entityManager->getRepository('CastingBundle:ReponseVideoCasting')->findOneBy(array(
            "videoCasting" => $videoCasting,
            "member" => $idmember
        ), array(
            'id' => 'DESC'
        ));

        if ($reponseVideoCasting != null)
            return $reponseVideoCasting->getPath();
        else
            return null;

    }


    function alertTitle($id)
    {

        $nbr = 0;

        $messaging              = $this->entityManager->getRepository('BackBundle:Messaging')->findBy(array(
            "enable" => 0,
            "deleteenable" => 0,
            "userreceiver" => $id
        ));
        $membreVuCollectionNews = $this->entityManager->getRepository('BackBundle:MembreVuCollection')->MembreVuCollectionAlert($id);

        if ($messaging != null) {
            $nbr = $nbr + count($messaging);
        }

        if ($membreVuCollectionNews != null) {
            $nbr = $nbr + count($membreVuCollectionNews);
        }

        return $nbr;

    }


    function getImageDefault($path)
    {

        $information = $this->entityManager->getRepository('BackBundle:Information')->findOneBy(array());


        return $path . $information->getBackgroundpicture();

    }

    /**
     * @param $iduserCourant
     * @param $iduserEmitteur
     * @return int
     */
    function filterCollectionNews($iduserCourant, $iduserEmitteur)
    {
        $membreVuCollectionNews = $this->entityManager->getRepository('BackBundle:MembreVuCollection')->MembreVuCollectionNews($iduserCourant, $iduserEmitteur);

        return count($membreVuCollectionNews);
    }


    /**
     * @param $iduserCourant
     * @param $iduserEmitteur
     * @return int
     */
    function CollectionPhoto($iduserCourant, $iduserEmitteur)
    {
        $membreVuCollectionPhoto = $this->entityManager->getRepository('BackBundle:MembreVuCollection')->MembreVuCollectionPhoto($iduserCourant, $iduserEmitteur);

        return count($membreVuCollectionPhoto);
    }

    /**
     * @param $iduserCourant
     * @param $iduserEmitteur
     * @return int
     */
    function CollectionMedia($iduserCourant, $iduserEmitteur)
    {
        $membreVuCollectionMedia = $this->entityManager->getRepository('BackBundle:MembreVuCollection')->MembreVuCollectionMedia($iduserCourant, $iduserEmitteur);

        return count($membreVuCollectionMedia);
    }

    /**
     * @param $iduserCourant
     * @param $iduserEmitteur
     * @return array
     */
    function contactmessaging($iduserCourant, $iduserEmitteur)
    {
        $messaging = $this->entityManager->getRepository('BackBundle:Messaging')->findBy(array(
            "enable" => 0,
            "deleteenable" => 0,
            "userreceiver" => $iduserCourant,
            "useremitter" => $iduserEmitteur
        ));
        return $messaging;
    }

    /**
     * @param $id
     * @return array
     */
    function messaging($id)
    {
        $messaging = $this->entityManager->getRepository('BackBundle:Messaging')->findBy(array(
            "enable" => 0,
            "deleteenable" => 0,
            "userreceiver" => $id
        ));
        return $messaging;
    }

    /**
     * @param $type
     * @param $language
     * @return mixed
     */
    function filterMedia($type, $language)
    {
        if ($type == 'programme' or $type == 'spectacle' or $type == 'cinema') {
            $medias = $this->entityManager->getRepository('BackBundle:Medias')->getMediasListdateNbrFixProgramme($type, $language, 12);
        } else {
            $medias = $this->entityManager->getRepository('BackBundle:Medias')->getMediasListdateNbrFix($type, $language, 12);
        }
        return $medias;
    }

    /**
     * @param $idPersonnage
     * @param $idUserCourant
     * @return null|object
     */
    function verifierPersonnage($idPersonnage, $idUserCourant)
    {
        $box = $this->entityManager->getRepository('BackBundle:Box')->findOneBy(array(
            "personnageMedia" => $idPersonnage,
            "membre" => $idUserCourant,
            'typebox' => "crossing-cartoon"
        ));


        return $box;
    }

    public function getName()
    {
        return 'filterMedia_extension';
    }
}