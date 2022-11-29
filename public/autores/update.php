<?php
session_start();

if(!isset($_GET['id'])){
    header("Location:index.php");
    die();
}

$id = $_GET['id'];
require __DIR__."/../../vendor/autoload.php";
use Src\Autor;

if(!Autor::existeAutor($id)){
    header("Location:index.php");
    die();
}

$autor = (new Autor)->setId_autor($id)->read();

function mostrarError($nom){
    if (isset($_SESSION[$nom])) {
        echo "<p class='text-danger mt-2' style='font-size:0.8em'>{$_SESSION[$nom]}</p>";
        unset($_SESSION[$nom]);
    }
}

if(isset($_POST['crear'])){
    $error = false;
    $nombre = trim($_POST['nombre']);
    if(strlen($nombre)<3){
        $error = true;
        $_SESSION['nombre']="*** El campo nombre debe de tener al menos 3 caracteres";
        header("Location:{$_SERVER['PHP_SELF']}?id=$id");
    }
    $apellido = trim($_POST['apellidos']);
    if(strlen($apellido)<6){
        $error = true;
        $_SESSION['apellidos']="*** El campo apellido debe de tener al menos 3 caracteres";
        header("Location:{$_SERVER['PHP_SELF']}?id=$id");
        die();
    }
    if($error){
        header("Location:{$_SERVER['PHP_SELF']}?id=$id");
        die();
    }
    (new Autor) ->setNombre($nombre)->setApellidos($apellido)->update($id);
    $_SESSION['mensaje'] ="*** AUTOR ACTUALIZADO CON EXITO";
    header("Location:index.php");
}else{
?>
<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!--FONT AWESOME -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <!-- BOOTSRAP -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <title>Autor</title>
    </head>
    <body style="background-color: #e6ee9c;">
        <h5 class="text-center mt-4">Actualizar Autor </h5>
        <div class="container">
            <form action="<?php echo $_SERVER['PHP_SELF']."?id=$id" ?>" enctype="multipart/form-data" method="POST" class="mx-auto bg-secondary px-4 py-4 rounded" style="width: 40rem;">
                <div class="mb-4">
                    <label for="nombre" class="form-label">Nombre Autor</label>
                    <input type="text" id="nombre" class="form-control" placeholder="Nombre" name="nombre" value="<?php echo $autor->nombre;  ?>" required />
                </div>
                <?php
                mostrarError('nombre');
                ?>
                <div class="mb-4">
                    <label for="apellidos" class="form-label">Apellidos Autor</label>
                    <input type="text" id="apellidos" class="form-control" placeholder="apellidos" name="apellidos" value="<?php echo $autor->apellidos;  ?>" required />
                </div>
                <?php
                mostrarError('apellidos');
                ?>
                <div>
                    <div>
                        <button type="submit" name="crear" id="crear" class="btn btn-success">
                            <i class="fas fa-save"></i>Actualizar
                        </button>
                        <button type="reset" name="limpiar" class="btn btn-danger">
                            <i class="fa-solid fa-recycle"></i>Limpiar
                        </button>
                        <a href="index.php" class="btn btn-info">
                            <i class="fa-solid fa-recycle"></i>Volver
                        </a>
                    </div>
                </div>
            </form>
        </div>
        <?php
        if (isset($_SESSION['nombre'])) {
            echo <<<TXT
                <SCRIPT>
                    Swal.fire({
                        icon: 'success',
                        title: '{$_SESSION['nombre']}',
                        showConfirmButton: false,
                        timer: 1500
                    })
                </SCRIPT>
            TXT;
            unset($_SESSION['nombre']);
        }
        ?>
    </body>
    </html>
    <?php
}
?>