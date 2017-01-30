<?php
class film {

	private $sql;

	function __construct(){
		$this->sql= new SQLpdo();
	}

    // récupérer un film à partir de son identifiant
    // @param $id integer Code du film
    function getById($id) {

        // récupèreration dans la base de données = on obtient un Array
        
		$data = $this->sql->fetch("SELECT * FROM `ltp_film` WHERE id=:id", array(':id' =>$id));
         $data['lieux'] = $this->getLieux($id);
         $realisateur = $this->getRealisateur($id);
         $data['realisateur'] = $realisateur["realisateur"];
         $data['arrondissement'] = $this->getArrondissement($id);
         $date['commentaires'] = $this->getCommentaireByFilm($id);
         if (!empty($data['titre'])){
            $data['poster'] = $this->getPoster($data['titre']);   
            $data['overview'] = $this->getOverview($data['titre']);
         }
         
        return $data;
    }

    // récupèrer les lieux d'un film
    // @param $id integer Code du film
    function getLieux($id) {
        $lieu = new lieu();
        return $lieu->getByFilmId($id);
    }

    function getRealisateur($id) {
        $realisateur = new realisateur();
        return $realisateur->getByFilmId($id);
    }

    function getArrondissement($id) {
        $arrondissement = new arrondissement();
        return $arrondissement->getByFilmId($id);
    }

    function getCommentaireByFilm($id){
        $commentaires = new commentaire();
        $lieux=$this->getLieux($id);

        foreach ($lieux as $key => $lieu) {
            $commentaire[] = $commentaires->getCommentaireByLieu($lieu['id'],'desc');
        }
        
        return $commentaire;
    }

    // compter le nb de lieux pour un film
    // @param $id integer Code du film
    function countLieux($id) {
    	$lieu = new Lieu();
        return $lieu->countLieux($id);
     }

    // récupèrer une liste de films à partir d'un titre
    // @param $titre string Titre du film
    function findByTitre($titre) {
    	$data = $this->sql->fetch("SELECT * FROM `ltp_film` WHERE titre=:titre", array(':titre' =>$titre ));
		return $data;
    }

    function allFilm(){
    	$data = $this->sql->fetchAll("SELECT * FROM `ltp_film` ");
		return $data;
    }

    function getOverview($titre){
        $movie = new movieAPI($titre);
        if(!empty($movie)){
            return  $movie->getOverview();
        }
        return  $movie;
    }

    function getPoster($titre){
        $movie = new movieAPI($titre);
        if(!empty($movie)){
            return  $movie->getPoster();
        }
        return $movie;
    }

}


/**
* 
*/
class movieAPI
{
    private $movie;
    private $tmdb;
    private $movies;

    function __construct($titre)
    {
        $keyCache = md5($titre);
        
         if (file_exists('cache/'.$keyCache.'.txt')){
            $this->movie = unserialize(file_get_contents('cache/'.$keyCache.'.txt'));
         }else
         {
            $this->tmdb = new TMDB();
            $this->tmdb->setAPIKey(APIKEY);
            $this->movies = $this->tmdb->searchMovie($titre);
            if (!empty($this->movies)){
                $this->movie = $this->tmdb->getMovie($this->getID()); 
            }
               
            file_put_contents('cache/'.$keyCache.'.txt',serialize($this->movie));
        }

        
         

    }

    function getID(){
        $data = $this->movies[0]->getID(); 
        return $data;
        
    }

    function getReviews(){
        $data = $this->movie->getReviews(); 
        return $data;
    }

    function getGenres(){
        $data = $this->movie->getGenres();
        return $data;
    }

    function getTrailer(){
        $data = $this->movie->getTrailer();
        return $data;
    }

    function getTrailers(){
        
        $data = $this->movie->getTrailers();
        return $data;

    }

    function getPoster(){
        $picture = "";
        if(!empty($this->movie)){
            $picture = "http://image.tmdb.org/t/p/original/". $this->movie->getPoster();
        }
        return $picture;
    }

    function getTagline(){
        $Tagline = '';

        if(!empty($this->movie)){
            $Tagline = $this->movie->getTagline();
        }

        return $Tagline;
    }

    function getOverview(){
        $Overview = "";

        if(!empty($this->movie)){
            $Overview = $this->movie->getOverview();
        }

        return $Overview;
    }
}



