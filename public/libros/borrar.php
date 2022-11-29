<?php
session_start();

require __DIR__."/../../vendor/autoload.php";
use Src\Libro;

if(!isset($_POST['libro'])){
    header("Location:index.php");
    die();
}

$libro = unserialize($_POST['libro']);
if(!Libro::ExisteLibroId($libro->id_libro)){
    header("Location:index.php");
    die();
}

if(basename($libro->portada)!='default.jpg'){
    unlink("..".$libro->portada);
}

Libro::delete($libro->id_libro);
$_SESSION['mensaje']="*** EL LIBRO SE HA BORRADO";
header("Location:index.php");