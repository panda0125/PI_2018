<?php
session_start();
include 'php/connection.php';

$global_sql = "SELECT * FROM oferta ORDER BY fecha_inicio DESC"; // Sql global que carga las ofertas; por defecto seleccionará todas las ofertas más recientes
$global_cont_sql = "SELECT COUNT(*) AS `count` FROM `oferta`";   // Contador de ofertas; por defecto contará todas las ofertas de la bd

/**
 *
 *      Comprobación de categoría
 *
 */
if (isset($_GET['category'])) {
    $categoria = $_GET['category'];
    $global_cont_sql = "SELECT COUNT(*) AS `count` FROM `oferta` WHERE categoria = "."'$categoria'";
    $global_sql = "select * from oferta where categoria = "."'$categoria'";
}
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="./css/style.css">
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  	<script type="text/javascript" src="./js/conectores.js"></script>
	<script type="text/javascript" src="./js/main.js"></script>
	<title>Index</title>
</head>
<body>
<!-- ESTO ES LO MISMO QUE EL COMPONENTE, BASTA CON VOLVER A SUSTITUIRLO, CON LA DIFERENCIA DE QUE EL COMPONENTE NO ENLAZA BIEN CON EL LOGIN -->
<header class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">Body Balance</a>
    </div>
    <ul class="nav navbar-nav navbar-right">
    	<?php
        /**
         *
         *      Submenú lateral de usuario/empresa conectado
         *
         */
        // Si el usuario NO está logueado.
        if (!isset($_SESSION['nombre'])) {
            echo '<li><a href="content/selec_reg.php"><span class="glyphicon glyphicon-download-alt"></span> Registrarse</a></li>';
            echo '<li><a href="content/login.html"><span class="glyphicon glyphicon-log-in"></span> Entrar</a></li>';
            // Si el usuario está logueado, y visualizará distintos menús dependiendo de si es EMPRESA o USUARIO.
        } else {
            echo '
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-user"></span> ' . $_SESSION['nombre']  . '
                    <span class="caret"></span></a>
                    <ul class="dropdown-menu">';
            if ($_SESSION['tipo'] === "usuario") {
                echo '<li><a href="content/perfil.php"><span class="glyphicon glyphicon-cog"></span> Mi perfil</a></li>
                          <li><a href="content/reservas.php"><span class="glyphicon glyphicon-th-list"></span> Mis reservas</a></li>';
            } else if ($_SESSION['tipo'] === "empresa") {
                echo '<li><a href="content/perfil.php"><span class="glyphicon glyphicon-briefcase"></span> Perfil de empresa</a></li>';
            }
            echo '<li><a href="php/logout.php"><span class="glyphicon glyphicon-log-out"></span> Cerrar sesión</a></a></li>
                    </ul>
                </li>';
        }
        ?>
    </ul>
  </div>
</header>
<nav class="menuPrincipalUser">

</nav>
<aside class="filtroBusqueda">

</aside>
<section>
	<article>
		<div id="myCarousel" class="carousel slide carousel-generico" data-ride="carousel">
		  <!-- Indicators -->
		  <ol class="carousel-indicators">
		    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
		    <li data-target="#myCarousel" data-slide-to="1"></li>
		    <li data-target="#myCarousel" data-slide-to="2"></li>
		    <li data-target="#myCarousel" data-slide-to="3"></li>
		  </ol>

		  <!-- Wrapper for slides -->
		  <div class="carousel-inner" role="listbox">
		    <div class="item active">
		      <img src="./img/ciclismo-slider.jpg" alt="ciclismo">
		      <div class="carousel-caption">
		        <h3>Ciclismo</h3>
			        <p>Todo sobre ruedas</p>
		      </div>
		    </div>

		    <div class="item">
		      <img src="./img/surf-slider.jpg" alt="Surf">
		      <div class="carousel-caption">
		        <h3>Surf</h3>
		        <p>¿Te apetece coger algunas olas?</p>
		      </div>
		    </div>

		    <div class="item">
		      <img src="./img/alpinismo-slider.jpg" alt="alpinismo">
		      <div class="carousel-caption">
		        <h3>Alpinismo</h3>
		        <p>Aire fresco</p>
		      </div>
		    </div>

		    <div class="item">
		      <img src="./img/submarinismo-slider.jpg" alt="submarinismo">
		      <div class="carousel-caption">
		        <h3>Submarinismo</h3>
		        <p>Conoce el fondo del mar</p>
		      </div>
		    </div>
		  </div>

		  <!-- Left and right controls -->
		  <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
		    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
		    <span class="sr-only">Previous</span>
		  </a>
		  <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
		    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
		    <span class="sr-only">Next</span>
		  </a>
		</div>
	</article>

	<article>
        <div class="row tabla">

        <?php
        	/* Cuenta las reservas que existen y dependiendo de las que salgan irá poniendo los elementos.
			   En el caso de que haya más reservas de 12, pues solo se mostrarán las 12 más recientes.
        	*/
        	$result = $conexion->query($global_cont_sql); // Select que se ejecutará. Si se usan filtros cambiará.
			$fila = $row = $result->fetch_assoc();
			$ofertas_encontradas = $fila['count'];
			$count = 12;

			if ($ofertas_encontradas == 0) {
                echo '<div id="sin_ofertas"><p>NO HAY OFERTAS DISPONIBLES</p></div>';
            }


			if ($ofertas_encontradas<12) {
				$count = $fila['count'];
			}
            $sql = $global_sql;
            $result = $conexion->query($sql);
			/* Realiza la consulta para extraer las ofertas de la base de datos ordenadas por su fecha de inicio de forma descendente. Tras esto, se realiza un corte de la descripción por si esta es demasiado extensa que solo salga una parte, y al lado un botón o enlace de leer más que llevara a la página de esa oferta concreta.

			*/
            /**
             *
             *      Comprobación de load more
             *
             */

            if (isset($_GET['load'])) {
                for ($i = 1; $i <= $ofertas_encontradas; $i++) {
                    $row = $result->fetch_assoc();
                    $nombre = $row['nombre'];
                    $provincia = $row['provincia'];
                    $actividad = $row['tipo_actividad'];
                    $precio = $row['precio'];
                    $dificultad = $row['dificultad'];
                    echo '  <div class="col-lg-4 actividad">
                                       <figure class="snip1208">
  <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/331810/sample66.jpg" alt="sample66"/>
  <div class="date"><span class="day">28</span><span class="month">Oct</span></div><i class="ion-film-marker"></i>
  <figcaption>
    <h3 id="nombre">'.$nombre.'</h3>
    <p id="actividad">'.$actividad.'</p>
    <p id="provincia">'.$provincia.'</p>
    <p id="dificultad">'.$dificultad.'</p>
    <p id="precio">'.$precio.'€</p>
    <button>Ver actividad</button>
  </figcaption><a href="content/oferta.php?id='.$row['id'].'"></a>
</figure>
                </div>';
                }
            } else if (isset($_GET['category'])) {
                for ($i = 1; $i <= $ofertas_encontradas; $i++) {
                    $row = $result->fetch_assoc();
                    $nombre = $row['nombre'];
                    $provincia = $row['provincia'];
                    $actividad = $row['tipo_actividad'];
                    $precio = $row['precio'];
                    $dificultad = $row['dificultad'];
                    echo '  <div class="col-lg-4 actividad">
                                                           <figure class="snip1208">
                      <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/331810/sample66.jpg" lt="sample66"/>
                      <div class="date"><span class="day">28</span><span class="month">Oct</span></div><i class="ion-film-marker"></i>
                      <figcaption>
                        <h3 id="nombre">'.$nombre.'</h3>
                        <p id="actividad">'.$actividad.'</p>
                        <p id="provincia">'.$provincia.'</p>
                        <p id="dificultad">'.$dificultad.'</p>
                        <p id="precio">'.$precio.'€</p>
                        <button>Ver actividad</button>
                      </figcaption><a href="content/oferta.php?id='.$row['id'].'"></a>
                    </figure>
                    </div>';
                }
            } else {


                /**
                 *
                 * Bloque de actividades recomendadas para el usuario.
                 * En este bloque aparecerán 3 ofertas recomendadas para el usuario, según su categoría marcada como favorita al registrarse.
                 *
                 */
                if (isset($_SESSION['nombre']) && $_SESSION['tipo'] === 'usuario') { // Si hay un usuario conectado y es de tipo usuario...
                    $sql_destacados = "SELECT * FROM oferta WHERE categoria = (SELECT actividad_fav FROM usuario WHERE alias = '" . $_SESSION['nombre'] . "') ORDER BY RAND() ";
                    $result_destacados = $conexion->query($sql_destacados); // Select que buscará la actividad_fav del usuario con la sesión iniciada.
                    $fila_destacados = $row_destacados = $result->fetch_assoc();
                    $ofertas_destacadas_encontradas = $result->num_rows;
                    $count = 9;
                    if ($ofertas_destacadas_encontradas > 0) { // Si no existen actividades con la categoría favorita del user (mínimo 1), no saldrá el cuadro de DESTACADOS!!
                        if (isset($_SESSION['nombre'])) {
                            echo "<h3>Destacadas</h3>";
                            for ($i = 1; $i <= 3; $i++) {
                                $row_destacados = $result_destacados->fetch_assoc();
                                $nombre = $row_destacados['nombre'];
                                $provincia = $row_destacados['provincia'];
                                $actividad = $row_destacados['tipo_actividad'];
                                $precio = $row_destacados['precio'];
                                $dificultad = $row_destacados['dificultad'];
                                echo '  <div class="col-lg-4 actividad destacada">
                    <div class="row">
                        <div class="col-lg-4">
                            <img src="./img/submarinismo.jpg" alt="submarinismo" class="listImg">
                        </div>
                        <div class="col-lg-8">
                            <p id="nombre">' . $nombre . '</p>
                            <p id="actividad">' . $actividad . '</p>
                            <p id="provincia">' . $provincia . '</p>
                            <p id="dificultad">' . $dificultad . '</p>
                            <p id="precio">' . $precio . '€</p>
                            <a href="content/oferta.php?id=' . $row_destacados['id'] . '">Ver actividad</a>
                        </div>
                    </div>
                </div>';
                            }
                        }
                    }
                }



                for ($i = 1; $i <= $count; $i++) {
                    $row = $result->fetch_assoc();
                    $nombre = $row['nombre'];
                    $provincia = $row['provincia'];
                    $actividad = $row['tipo_actividad'];
                    $precio = $row['precio'];
                    $dificultad = $row['dificultad'];
                    echo '  <div class="col-lg-4 actividad">
                                       <figure class="snip1208">
  <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/331810/sample66.jpg" alt="sample66"/>
  <div class="date"><span class="day">28</span><span class="month">Oct</span></div><i class="ion-film-marker"></i>
  <figcaption>
    <h3 id="nombre">'.$nombre.'</h3>
    <p id="actividad">'.$actividad.'</p>
    <p id="provincia">'.$provincia.'</p>
    <p id="dificultad">'.$dificultad.'</p>
    <p id="precio">'.$precio.'€</p>
    <button>Ver actividad</button>
  </figcaption><a href="content/oferta.php?id='.$row['id'].'"></a>
</figure>
                </div>';
                }
            }
        ?>
  		</div>
        <form action="?load=all" method="get">
            <input type="hidden" name="load" value="all">
            <button id="cargar">Cargar más</button>
        </form>
	</article>
</section>

<footer>

</footer>

</body>
</html>