<?php

session_start();

if (!isset($_SESSION["nombres"]))
{
    echo "<script>alert('Usted no ha iniciado sesión');window.location.href = '".base_url()."/index.php/home/iniciar';</script>";
    return;
}

/* Recogemos todos los registros con el de id_alumno y de id_ciclo del $id enviado */
$modeloAlumnoPorCurso = new App\Models\ModeloAlumnoPorCurso();
$curso = $modeloAlumnoPorCurso->traerPorId($id, $_SESSION["id_cliente"]);
if (empty($curso))
{
    return json_encode(["Estado" => 404, "Detalles" => "El AlumnoPorCurso que busca no esta registrado"], true);
}
$curso = $curso[0];
//return json_encode($curso);
$datos = $modeloAlumnoPorCurso->traerMostrar($curso["id_alumno"], $curso["id_ciclo"], $curso["id_cliente"]);

foreach ($datos as $eliminado)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => base_url()."/index.php/alumnoPorCurso/delete/".$eliminado["idAlumnoPorCurso"],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "DELETE",
        CURLOPT_HTTPHEADER => array(
            $_SESSION["auth"]
                                    ),
                                   ));

    $response = curl_exec($curl);
    curl_close($curl);

    if ($_SERVER["SERVER_NAME"] == "localhost")
    {
        // Puede que tengamos caracteres ocultos la final de la respuesta
        $data = substr($response, 0, $_SESSION["tam"]);
        $data = json_decode($data, true);
    }
    else
    {
        $data = json_decode($response, true);
    }
    if ($data["Estado"] != 200)
        $mensaje = $data["Detalles"];
}
// Redireccion
$mensaje = $data["Detalles"];
echo "<script>alert('".$mensaje."');window.location.href = '".base_url()."/index.php/alumnoPorCurso/listar';</script>";

?>

