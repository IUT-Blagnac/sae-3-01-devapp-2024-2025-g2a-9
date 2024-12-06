<?php
  try{
    $user = 'R2024MYSAE3005';
    $pass = 'j4X3F9Sn2bsqL7';
    $conn = new PDO('mysql:host=localhost;dbname=R2024MYSAE3005;charset=UTF8'  
            ,$user, $pass, array(PDO::ATTR_ERRMODE =>PDO::ERRMODE_EXCEPTION));
  }
  catch (PDOException $e){
    echo "Erreur: ".$e->getMessage()."<br>";
    die() ;
  }
?>