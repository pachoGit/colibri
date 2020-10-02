<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "http://localhost/colibri/index.php/usuarios",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "Authorization: Basic YTJhYTA3YWRmaGRmcmV4ZmhnZGZoZGZlcnR0Z2VMaHJqbVR2b2cyS0hMZ2l4b0s4YjZjcHR0dS8wZFRXOm8yYW8wN29kZmhkZnJleGZoZ2RmaGRmZXJ0dGdlL3BKUmZVVlhYc1E0MW9TUURnUHUzNDB6VU42TlZSbQ=="
  ),
));

$response = curl_exec($curl);

curl_close($curl);
$data =json_decode($response, true);

switch(json_last_error()) {
        case JSON_ERROR_NONE:
            echo ' - Sin errores';
        break;
        case JSON_ERROR_DEPTH:
            echo ' - Excedido tamaño máximo de la pila';
        break;
        case JSON_ERROR_STATE_MISMATCH:
            echo ' - Desbordamiento de buffer o los modos no coinciden';
        break;
        case JSON_ERROR_CTRL_CHAR:
            echo ' - Encontrado carácter de control no esperado';
        break;
        case JSON_ERROR_SYNTAX:
            echo ' - Error de sintaxis, JSON mal formado';
        break;
        case JSON_ERROR_UTF8:
            echo ' - Caracteres UTF-8 malformados, posiblemente codificados de forma incorrecta';
        break;
        default:
            echo ' - Error desconocido';
        break;
    }

    echo PHP_EOL;
die;

?>

  <div class="container col-x1-12">
    <h1>Lista de usuarios</h1>
    <a href="registrar.php" class="btn btn-primary">Registrar</a>
    <table class="table">
      <thead class="thead-light">
	<tr>
	  <th scope="col">ID</th>
	  <th scope="col">Nombres</th>
	  <th scope="col">Edad</th>
	  <th scope="col">DNI</th>
          <th scope="col">Correo</th>
	  <th scope="col">Foto</th>
	  <th scope="col" colspan="3">Operaciones</th>
	</tr>
      </thead>
      <tbody>
	<?php foreach($data["Detalles"] as $cliente): ?>
	<tr>
	  <td><?php echo $cliente["nombre"] ?> </td>
	  <td><?php echo $cliente["correo"] ?> </td>
	  <td><?php echo $cliente["pais"] ?> </td>
	  <td><?php echo $cliente["direccion"] ?> </td>
	  <td><a href="editar.php?id=<?php echo $cliente["id"]?>" class="btn btn-warning">Editar</a></td>
	  <td><a href="eliminar.php?id=<?php echo $cliente["id"]?>" class="btn btn-danger">Eliminar</a></td>	  
	</tr>
	<?php endforeach; ?>
      </tbody>
    </table>
  </div>

