<?php
session_start();

require __DIR__."/../../vendor/autoload.php";
use Src\Autor;

if(!isset($_POST['autor'])){
    header("Location:index.php");
    die();
}

$autor = unserialize($_POST['autor']);
if(!Autor::existeAutor($autor->id_autor)){
    header("Location:index.php");
    die();
}

Autor::delete($autor->id_autor);
$_SESSION['mensaje']="*** EL AUTOR HA SIDO BORRADA";
header("Location:index.php");