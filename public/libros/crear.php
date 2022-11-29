<?php
session_start();

require __DIR__ . "/../../vendor/autoload.php";
use Src\Autor;
use Src\Libro;
use Src\Tools;

$autores_ARRAY = Autor::AutorID();
$autores = Autor::readALL(1);

function mostrarError($nom){
    if (isset($_SESSION[$nom])) {
        echo "<p class='text-danger mt-2' style='font-size:0.8em'>{$_SESSION[$nom]}</p>";
        unset($_SESSION[$nom]);
    }
}

if (isset($_POST['crear'])) {
    //creamos un libro
    $error = false;
    $titulo = trim($_POST['nombre']);
    $autor = $_POST['autor'];
    $isbn = trim($_POST['isbn']);
//---------------------------------------------------COMPROBACIONES---------------------------------------------------
    if (strlen($titulo) < 5) {
        $error = true;
        $_SESSION['titulo'] = "*** EL TITULO DEBE DE TENER AL MENOS 5 CARACTERES";
    }
    if (!in_array($autor, $autores_ARRAY)) {
        $error = true;
        $_SESSION['autor'] = "*** ESE AUTOR NO ESTA PERMITIDO";
    }
    if(!preg_match('/^[0-9]{13}/',$isbn)){
        $error = true;
        $_SESSION['isbn'] = "*** EL ISBN DEBE DE TENER AL MENOS 13 CARACTERES";
    }

    if(Libro::ExisteISBN($isbn)){
        $error = true;
        $_SESSION['isbn'] = "*** EL ISBN YA EXISTE";
    }
    $control = false;
    foreach ($autores as $autorr) {
        if ($autorr->id_autor == $autor) {
            $control = true;
            break;
        }
    }
    if (!$control) {
        $_SESSION['autor'] = "*** ESE AUTOR NO ESTA EN NUESTRO SISTEMA";
        $error = true;
    }
    //Si hay errores en alguno de los campos simplemente recargo la pagina para que se muestren los errores
    if ($error) {
        header("Location:{$_SERVER['PHP_SELF']}");
        die();
    }
    // Vamos a comprobar la imagen, si la ruta es erronea simplemente le pondré una imagen default sin mostrar error.
    $portada = Tools::getImages();
    $nombrePortada = "/img/default.jpg";
    if($_FILES['portada']['error']==0){
        if(!in_array($_FILES['portada']['type'], $portada)){ 
            $_SESSION['portada'] ="*** ERROR SE ESPERABA UNA FOTO PORFAVOR";
            header("Location:{$_SERVER['PHP_SELF']}");
            die();
        }
        $nombrePortada = "/img/" . uniqid() . "_{$_FILES['portada']['name']}";

        if(!move_uploaded_file($_FILES['portada']['tmp_name'], __DIR__ . "/.." . $nombrePortada )){
            $nombrePortada = "/img/default.jpg";
        }
    }
    //creamos el libro
    (new Libro) ->setTitulo($titulo)
    ->setAutor($autor)
    ->setIsbn($isbn)
    ->setPortada($nombrePortada)
    ->create();
    $_SESSION['mensaje']= "SE HA CREADO EL LIBRO";
    header("Location:index.php");
} else {
?>
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
        <title>Libro</title>
    </head>
    <body style="background-color: darkgrey;">
        <h5 class="text-center mt-4">CREAR LIBRO </h5>
        <div class="container">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" method="POST" class="mx-auto bg-dark px-4 py-4 rounded" style="width: 40rem;">
                <div class="mb-4">
                    <label for="nombre" class="form-label bg-white">TITULO</label>
                    <input type="text" id="nombre" class="form-control" placeholder="Titulo" name="nombre" required>
                    <?php
                    MostrarError('titulo')
                    ?>
                </div>
                <div class="mb-4">
                    <label for="autor" class="form-label bg-white">AUTOR</label>
                    <select name="autor" class="form-select">
                        <?php
                        foreach ($autores as $autorr) {
                            echo "<option value='{$autorr->id_autor}'>{$autorr->nombre}</option>";
                        }
                        ?>
                    </select>
                    <?php
                    MostrarError('autor')
                    ?>
                </div>
                <div class="mb-4">
                    <label for="isbn" class="form-label bg-white">ISBN</label>
                    <input type="text" id="isbn" class="form-control" placeholder="ISBN" name="isbn" required>
                    <?php
                    MostrarError('isbn')
                    ?>
                </div>
                <div class="mb-4">
                    <label for="file" class="form-label bg-white">PORTADA</label>
                    <input type="file" class="form-control" id="file" name="portada" accept="/image*">
                    <?php
                            mostrarError('portada');
                    ?>
                </div>
                <div class="mb-4 text-center">
                    <img src="../img/default.jpg" alt="img" id="image" class="img-thumbnail" style="width: 12rem; height: 12rem;">
                </div>
                <div>
                    <div>
                        <button type="submit" name="crear" id="crear" class="btn btn-success">
                            <i class="fas fa-save">GUARDAR</i>
                        </button>
                        <button type="reset" name="limpiar" class="btn btn-danger">
                            <i class="fa-solid fa-recycle">LIMPIAR</i>
                        </button>
                        <a href="index.php" class="btn btn-info">
                            <i class="fa-solid fa-recycle">VOLVER</i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
        <script>
            document.getElementById('file').addEventListener('change', cambiarImagen);
            function cambiarImagen(event) {
                var file = event.target.files[0];
                var reader = new FileReader();
                reader.onload = (event) => {
                    document.getElementById('image').setAttribute('src', event.target.result);
                }
                reader.readAsDataURL(file);
            }
        </script>
        <?php
        if (isset($_SESSION['mensaje'])) {
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
<?php
}
?>