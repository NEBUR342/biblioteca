<?php
namespace Src;
use PDO;
use PDOException;

class Autor extends Conexion{
    private int $id_autor;
    private string $apellidos;
    private string $nombre;
    public function __construct(){
        parent::crearConexion();
    }
//------------------------------------METODOS CRUD------------------------------------
    //Funcion la cual me creara un autor
    public function create(){
    $q = "insert into autores (nombre , apellidos) values (:n , :a)";
    $stmt = parent::$conexion->prepare($q);
    try{
        $stmt->execute([
            ':n' => $this->nombre,
            ':a' => $this->apellidos
        ]);
    }catch(PDOException $ex){
        die("Error en crear autor : ".$ex->getMessage());
    }
    parent::$conexion=null;
    }

    //Funcion la cual me modificara un autor
    public function update(int $id_autor){
        $q = "update autores set nombre=:n , apellidos=:a where id_autor=:i";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([
                ":n" => $this-> nombre,
                ":a" => $this-> apellidos,
                ':i' => $id_autor
            ]);
        } catch (PDOException $ex) {
            die("Error en update Autores" .$ex->getMessage());
        }
        parent::$conexion=null;
    }

    //Funcion la cual me borrara un autor
    public static function delete(int $id_autor){
        parent::crearConexion();
        $q = "Delete from autores where id_autor=:i";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([
                ":i" => $id_autor
            ]);
        } catch (PDOException $ex) {
            die("Error en delete Autores" .$ex->getMessage());
        }
        parent::$conexion=null;
    }

    //Funcion la cual me muestra los datos de un autor
    public function read(){
        $q = "select * from autores where id_autor=:i";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([
                ":i" => $this->id_autor
            ]);
        } catch (PDOException $ex) {
            die("Error en read Autor " .$ex->getMessage());
        }
        parent::$conexion=null;
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    //Funcion la cual me muestra los datos de todos los autores
    public static function readALL(?int $modo=null){
        parent::crearConexion();
        $q = ($modo==null) ? "Select * from autores " : "Select id_autor , nombre from autores";
        $stmt = parent::$conexion->prepare($q);
        try{
            $stmt->execute();
        } catch(PDOException $ex){
            die("Error en read autor :" .$ex->getMessage());
        }
        parent::$conexion=null;
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
//------------------------------------OTROS METODOS------------------------------------
    private function HayAutor(){
        $q = "Select id_autor from autores";
        $stmt = parent::$conexion->prepare($q);
        try{
            $stmt->execute();
        }catch(PDOException $ex){
            die("Error en hay autor : ".$ex->getMessage());
        }
        parent::$conexion=null;
        return $stmt->rowCount();
    }

    public function crearAutor($cantidad){
        if($this->HayAutor()) return;
        $faker = \Faker\Factory::create('es_ES');
        for($x=0; $x<$cantidad ; $x++){
            (new Autor) ->setNombre($faker->firstName())
            ->setApellidos($faker->lastName()." ".$faker->lastName())
            ->create();
        }
    }

    public static function existeAutor($id):bool{
        parent::crearConexion();
        $q="Select id_autor from autores where id_autor=:i";
        $stmt = parent::$conexion->prepare($q);
        try{
            $stmt->execute([
                ":i" => $id
            ]);
        }catch(PDOException $ex){
            die("Error en Existe Autor:" .$ex->getMessage());
        }
        parent::$conexion=null;
        return $stmt->rowCount();
    }

    public static function AutorID():array{
        parent::crearConexion();
        $q = "Select id_autor from autores";
        $stmt= parent::$conexion->prepare($q);
        try{
            $stmt->execute();
        }catch(PDOException $ex){
            die("Error en AutorID ".$ex->getMessage());
        }
        parent::$conexion=null;
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public static function Autores():array{
        parent::crearConexion();
        $q = "Select nombre from autores";
        $stmt= parent::$conexion->prepare($q);
        try{
            $stmt->execute();
        }catch(PDOException $ex){
            die("Error en Autores ".$ex->getMessage());
        }
        parent::$conexion=null;
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
//------------------------------------SETTERS------------------------------------
    public function setId_autor($id_autor){
        $this->id_autor = $id_autor;
        return $this;
    }
    
    public function setApellidos($apellidos){
        $this->apellidos = $apellidos;
        return $this;
    }
    
    public function setNombre($nombre){
        $this->nombre = $nombre;
        return $this;
    }
}