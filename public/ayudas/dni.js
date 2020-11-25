const api_dni = "https://dni.optimizeperu.com/api/persons/";

function ponerInformacion(respuesta)
{
    let usuario = JSON.parse(respuesta);
    if (usuario.name === undefined)
    {
	alert("No se encuentra a la persona");
	document.getElementById("dni").value = "";
	document.getElementById("nombres").value = "";
	document.getElementById("apellidos").value = "";
	return;
    }
    document.getElementById("nombres").value = usuario.name;
    document.getElementById("apellidos").value = usuario.first_name + " " + usuario.last_name;
}


function traerInformacion(entrada)
{
    let dni;

    dni = entrada.value;
    ajax_get((api_dni + dni), ponerInformacion);
}
