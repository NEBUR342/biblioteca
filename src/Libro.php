<?php
namespace Src;
use PDO;
use PDOException;

class Libro extends Conexion{
//------------------------------------Atributos------------------------------------
    private int $id_libro;
    private string $titulo;
    private string $isbn;
    private int $autor;
    private ?string $portada;
    public function __construct(){
        parent::crearConexion();
    }
//------------------------------------METODOS CRUD------------------------------------
    //Funcion la cual me creara un libro
    public function create(){
        $q = "Insert into libros (titulo , isbn , autor , portada) values(:t , :i , :a , :p)";
        $stmt = parent::$conexion->prepare($q);
        try{
            $stmt->execute([
                ':t'=> $this->titulo,
                ':i' => $this->isbn,
                ':a' => $this->autor,
                ':p' => $this->portada
            ]);
        }catch(PDOException $ex){
            die("Error en crear Libro".$ex->getMessage());
        }
        parent::$conexion=null;
    }

    //Funcion la cual me modificara un libro
    public function update($id){
        $q="update libros set titulo=:t,isbn=:i,autor=:a, portada=:p where id_libro=:id";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([
                ":t" => $this->titulo,
                ":i" => $this->isbn,
                ":a" => $this->autor,
                ":p" => $this->portada,
                ":id" => $id
            ]);
        } catch (PDOException $ex) {
            die("Error en upgrate Libros" .$ex->getMessage());
        }
        parent::$conexion=null;
    }

    //Funcion la cual me borrara un libro
    public static function delete(int $id_libro){
        parent::crearConexion();
        $q = "Delete from libros where id_libro=:i";
        $stmt = parent::$conexion->prepare($q);
        try {
            $stmt->execute([
                ":i" => $id_libro
            ]);
        } catch (PDOException $ex) {
            die("Error en delete Libros" .$ex->getMessage());
        }
        parent::$conexion=null;
    }

    //Funcion la cual me muestra los datos de un libro
    public function read(){
        parent::crearConexion();
        $q = "Select * from libros where id_libro=:i";
        $stmt = parent::$conexion->prepare($q);
        try{
            $stmt->execute([':i'=>$this->id_libro]);
        }catch(PDOException $ex){
            die("Error en read libros".$ex->getMessage());
        }
        parent::$conexion=null;
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    //Funcion la cual me muestra los datos de todos los libros
    public static function readAll(){
        parent::crearConexion();
        $q = "select * , nombre , apellidos from libros,autores where autor=autores.id_autor order by libros.id_libro ";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute();

        }catch(PDOException $ex){
            die("Error en readAll Libro".$ex->getMessage());
        }
        parent::$conexion=null;
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

//------------------------------------OTROS METODOS------------------------------------
    private function HayLibro(){
        $q = "Select id_libro from libros";
        $stmt = parent::$conexion->prepare($q);
        try{
            $stmt->execute();
        }catch(PDOException $ex){
            die("Error en HayLibro libros" .$ex->getMessage());
        }
        parent::$conexion=null;
        return $stmt->rowCount();
    }
    
    public function crearLibro(int $cantidad){
        if(self::HayLibro()) return;
        $faker = \Faker\Factory::create('es_ES');
        $autor = Autor::AutorID();
        for($x=0; $x<$cantidad; $x++){
            (new Libro)->setTitulo($faker->words(3,true))
            ->setIsbn($faker->isbn13())
            ->setAutor($faker->randomElement($autor))
            ->setPortada('/../public/img/default.jpg')
            ->create();
        }
    }
    
    public static function ExisteLibroId(int $id_libro){
        parent::crearConexion();
        $q = "Select id_libro from libros where id_libro=:i";
        $stmt = parent::$conexion->prepare($q);
        try{
            $stmt->execute([
                ':i'=> $id_libro
            ]);
        }catch(PDOException $ex){
            die("Error en existeLibroID ".$ex->getMessage());
        }
        parent::$conexion=null;
        return $stmt->rowCount();
    }
    
    public static function ExisteISBN(string $isbn){
        parent::crearConexion();
        $q = "Select id_libro from libros where isbn=:i";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute([
                ':i' => $isbn
            ]);
        }catch(PDOException $ex){
            die("Error en EXISTE ISBN Libro".$ex->getMessage());
        }
        parent::$conexion=null;
        return $stmt->rowCount();
    }
    
    public static function ExisteISBNRepe(int $isbn, int $id){
        parent::crearConexion();
        $q = "Select id_libro from libros where isbn=:i and $id!=:id";
        $stmt=parent::$conexion->prepare($q);
        try{
            $stmt->execute([
                ':i' => $isbn,
                ':id'=> $id
            ]);
        }catch(PDOException $ex){
            die("Error en EXISTE ISBN Libro repetido".$ex->getMessage());
        }
        parent::$conexion=null;
        return $stmt->rowCount();
    }
//------------------------------------SETTERS------------------------------------
    public function setId_libro($id_libro){
        $this->id_libro = $id_libro;
        return $this;
    }
    
    public function setTitulo($titulo){
        $this->titulo = $titulo;
        return $this;
    }
    
    public function setIsbn($isbn){
        $this->isbn = $isbn;
        return $this;
    }
    
    public function setAutor($autor){
        $this->autor = $autor;
        return $this;
    }
    
    public function setPortada($portada){
        $this->portada = $portada;
        return $this;
    }
}