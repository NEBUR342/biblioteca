<?php
session_start();

require __DIR__."/../../vendor/autoload.php";
use Src\Autor;

(new Autor)->crearAutor(8);
$autores = Autor::readALL();

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
<body style="background-color: darkgrey;">
    <h5 class="text-center mt-2">AUTORES </h5>
    <div class="container">
    <a href="crear.php" class="my-2 btn btn-primary"><i class="fas fa-add"></i>Crear Autor</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Apellidos</th>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach($autores as $item){
                $autor = serialize($item);

                echo <<<TXT
                <tr>
                    <th scope="row">{$item->id_autor}</th>
                    <td>{$item->nombre}</td>
                    <td> {$item->apellidos}</td>
                    <td>
                    <form class="form form-inline" action="borrar.php" method="POST">
                    <input type="hidden" name="autor" value='{$autor}' />
                    <a href="update.php?id={$item->id_autor}" class="btn btn-info">
                    <i class="fas fa-edit"></i>
                    </a>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i>
                    </button>
                    </form>
                    </td>
                </tr>
                TXT;
}
?>
            </tbody>
        </table>
    </div>
    <?php
    if(isset($_SESSION['mensaje'])){
        echo <<<TXT
        <SCRIPT>
        Swal.fire({
            icon: 'success',
            title: '{$_SESSION['mensaje']}',
            showConfirmButton: false,
            timer: 1500
        })
        </SCRIPT>
        TXT;
        unset($_SESSION['mensaje']);
    }
?>
</body>
</html>