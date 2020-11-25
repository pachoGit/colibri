// Funcion ajax
// ejemplo ruta = "http://colibri.informaticapp.com/grados/traerGrado"
function ajax(id, cliente, ruta, funcion)
{
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function()
    {
	if (this.readyState == 4 && this.status == 200)
	    funcion(this.responseText);
    }
    xhttp.open("POST", ruta, true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("id=" + id + "&id_cliente=" + cliente);
}

function ajax_get(ruta, funcion)
{
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function()
    {
	if (this.readyState == 4 && this.status == 200)
	    funcion(this.responseText);
    }
    xhttp.open("GET", ruta, true);
    xhttp.send();
}

// Funcion para los reportes
function ajax_reportes(desde, hasta, cliente, ruta, funcion)
{
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function()
    {
	if (this.readyState == 4 && this.status == 200)
	    funcion(this.responseText);
    }
    xhttp.open("POST", ruta, true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("desde=" + desde + "&hasta=" + hasta + "&cliente=" + cliente);
}
