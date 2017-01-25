<?php
class lieu{
	private $sql;
	function __construct(){
		$this->sql= new SQLpdo();
	}

	function getById($id) {

        // récupèreration dans la base de données = on obtient un Array
        $data=$this->sql->fetch("SELECT * FROM `ltp_lieu` WHERE id=:id", array(':id' =>$id ));

        return $data;
    }
    function countLieux($id){
    	$data=$this->sql->fetch("SELECT COUNT(id) FROM `ltp_lieu` WHERE `id_film`=:id_film", array(':id_film' =>$id ));

        return $data;
    }
    function getByRealisateurId($id){
    	$data=$this->sql->fetchAll("SELECT * FROM `ltp_lieu` WHERE `id_realisateur`=:id_realisateur", array(':id_realisateur' =>$id ));
        return $data;
    }
    function getByFilmId($id){
    	$data=$this->sql->fetchAll("SELECT * FROM `ltp_lieu` WHERE `id_film`=:id_film", array(':id_film' =>$id ));
        return $data;
    }
    function findLieu($nd=0){
    	$data=$this->sql->fetchAll("SELECT * FROM `ltp_lieu` ");
		return $data;
    }
    function getArrondissement($id){
		$arrondissement=new arrondissement();
		$data=$arrondissement->getArrondissement($id);
		return $data;
	}
}