<?php
require_once 'third-party/jpgraph-4.4.2/src/jpgraph.php';
require_once 'third-party/jpgraph-4.4.2/src/jpgraph_bar.php';
require_once 'third-party/jpgraph-4.4.2/src/jpgraph_pie.php';
class AdminController
{
    public function __construct($model, $presenter, $UsuarioModel)
    {
        $this->model = $model;
        $this->presenter = $presenter;
        $this->UsuarioModel = $UsuarioModel;
    }

    public function home()
    {
        $usuario = $_SESSION["usuario"];

        $homeData = array();
        $homeData["usuario"] = $usuario;
        $this->presenter->render("lobby_admin", $homeData);
    }
    public function adminUsuarios(){
        $homeData = array();
        $homeData["tituloGestion"] = "Administrar Usuarios";
        $homeData["gestionesUsuarios"] = " ";
        $this->presenter->render("gestiones_admin", $homeData);
    }
    public function adminPreguntas(){
        $homeData = array();
        $homeData["tituloGestion"] = "Administrar Preguntas";
        $homeData["gestionesPreguntas"] = " ";
        $this->presenter->render("gestiones_admin", $homeData);
    }
    public function adminPartidas(){
        $homeData = array();
        $homeData["tituloGestion"] = "Administrar Partidas";
        $homeData["gestionesPartidas"] = " ";
        $this->presenter->render("gestiones_admin", $homeData);
    }
    //------------------------------------ USUARIOS ----------------------------------------------------

    public function obtenerJugadoresTotales()
    {
        $jugadoresTotales = $this->model->obtenerJugadoresTotales();
        $homeData = array();
        $homeData["tituloGestion"] = "Jugadores totales";
        $homeData["cantidad"] = $jugadoresTotales;
        $homeData["espacioGestionUsuarios"] = " ";
        $this->presenter->render("ver_gestion_admin", $homeData);
    }

    public function generarGraficoUsuariosPorPais()
    {
        $datos = $this->prepararDatosParaGraficoUsuariosPorPais();

        // Crear el gráfico
        $grafico = new Graph(800, 600);
        $grafico->SetScale("textlin");
        $grafico->title->Set("Usuarios por país");

        $barras = new BarPlot($datos['cantidades']);
        $barras->SetFillColor('yellow');
        $barras->SetLegend("Cantidad de Usuarios");
        $grafico->xaxis->SetTickLabels($datos['paises']);

        $grafico->Add($barras);

        // Capturar la salida del gráfico en un buffer
        ob_start();
        $grafico->Stroke(_IMG_HANDLER);
        $image = $grafico->img->Stream();
        $imageContent = ob_get_contents();
        ob_end_clean();

        // Convertir la imagen a base64
        $graficoBase64 = base64_encode($imageContent);

        // Preparar los datos para la vista
        $homeData = array();
        $homeData["tituloGestion"] = "Usuarios por país";
        $homeData["grafico_base64"] = $graficoBase64; // Enviar la imagen base64 a la vista
        $homeData["espacioGestionUsuarios"] = " ";
        $this->presenter->render("ver_gestion_admin", $homeData);
    }



    public function prepararDatosParaGraficoUsuariosPorPais()
    {
        $cantidadPorPais = $this->obtenerCantidadUsuariosPorPais();
        $paises = [];
        $cantidades = [];

        foreach ($cantidadPorPais as $pais) {
            $paises[] = $pais['pais'];
            $cantidades[] = $pais['cantidad_pais'];
        }

        return ['paises' => $paises, 'cantidades' => $cantidades];
    }

    public function obtenerCantidadUsuariosPorPais()
    {
        $cantidadPorPais = $this->model->obtenerCantidadUsuariosPorPais();
        return $cantidadPorPais;
    }

// -----------------------------------------------------------------------------------------------------
    public function generarGraficoUsuariosPorSexo()
    {
        $datos = $this->prepararDatosParaGraficoUsuariosPorSexo();

        // Crear el gráfico
        $grafico = new Graph(800, 600);
        $grafico->SetScale("textlin");
        $grafico->title->Set("Usuarios por sexo");

        $barras = new BarPlot($datos['cantidades']);
        $barras->SetFillColor('green');
        $barras->SetLegend("Cantidad de Usuarios");
        $grafico->xaxis->SetTickLabels($datos['sexos']);

        $grafico->Add($barras);

        // Capturar la salida del gráfico en un buffer
        ob_start();
        $grafico->Stroke(_IMG_HANDLER);
        $image = $grafico->img->Stream();
        $imageContent = ob_get_contents();
        ob_end_clean();

        // Convertir la imagen a base64
        $graficoBase64 = base64_encode($imageContent);

        // Preparar los datos para la vista
        $homeData = array();
        $homeData["tituloGestion"] = "Usuarios por sexo";
        $homeData["grafico_base64"] = $graficoBase64;  // Enviar la imagen base64 a la vista
        $homeData["espacioGestionUsuarios"] = " ";
        $this->presenter->render("ver_gestion_admin", $homeData);
    }

    public function prepararDatosParaGraficoUsuariosPorSexo()
    {
        $cantidadPorSexo = $this->obtenerCantidadUsuariosPorSexo();
        $sexos = [];
        $cantidades = [];

        foreach ($cantidadPorSexo as $sexo) {
            $sexos[] = $sexo['sexo'];
            $cantidades[] = $sexo['cantidad_sexo'];
        }

        return ['sexos' => $sexos, 'cantidades' => $cantidades];
    }

    public function obtenerCantidadUsuariosPorSexo()
    {
        $cantidadPorSexo = $this->model->obtenerCantidadUsuariosPorSexo();
        return $cantidadPorSexo;
    }


// -----------------------------------------------------------------------------------------------------


    public function generarGraficoUsuariosPorGrupoEdad()
    {
        $datos = $this->prepararDatosParaGraficoUsuariosPorGrupoEdad();

        // Crear el gráfico
        $grafico = new Graph(800, 600);
        $grafico->SetScale("textlin");
        $grafico->title->Set("Usuarios por grupo de edad");

        // Configurar los datos de barras para cada grupo de edad
        $barras = new BarPlot(array(
            $datos['cantidadMenores'],
            $datos['cantidadAdultos'],
            $datos['cantidadJubilados']
        ));
        $barras->SetFillColor(array('blue', 'green', 'orange'));
        $barras->SetLegend("Cantidad de Usuarios");
        $grafico->xaxis->SetTickLabels(array('Menores', 'Adultos', 'Jubilados'));

        $grafico->Add($barras);

        // Capturar la salida del gráfico en un buffer
        ob_start();
        $grafico->Stroke(_IMG_HANDLER);
        $image = $grafico->img->Stream();
        $imageContent = ob_get_contents();
        ob_end_clean();

        // Convertir la imagen a base64
     
        $graficoBase64 = base64_encode($imageContent);

        $homeData = array();
        $homeData["tituloGestion"] = "Usuarios por grupo de edad";
        $homeData["grafico_base64"] = $graficoBase64;  // Enviar la imagen base64 a la vista
        $homeData["espacioGestionUsuarios"] = " ";
        $this->presenter->render("ver_gestion_admin", $homeData);
    }

    public function prepararDatosParaGraficoUsuariosPorGrupoEdad()
    {
        $cantidadMenores = $this->model->obtenerCantidadDeUsuariosMenores();
        $cantidadAdultos = $this->model->obtenerCantidadDeUsuariosAdultos();
        $cantidadJubilados = $this->model->obtenerCantidadUsuariosJubilados ();

        return [
            'cantidadMenores' => $cantidadMenores,
            'cantidadAdultos' => $cantidadAdultos,
            'cantidadJubilados' => $cantidadJubilados
        ];
    }



    public function verListaUsuarios(){
        $usuarios = $this->UsuarioModel->obtenerListaUsuarios();
        $homeData = array();
        $homeData["usuarios"] = $usuarios;
        $this->presenter->render("lista_usuarios_admin", $homeData);
    }

    public function generarGraficoPreguntasRespondidasUsuario()
    {
        $idUsuario = $_GET['id'];

        $usuario = $this->UsuarioModel->buscarUsuarioPorID($idUsuario);
        $totalDePreguntas = intval($this->model->obtenerCantidadPreguntasDelJuego());// Obtener la cantidad total de preguntas del juego
        $cantidadPreguntasRespondidas = intval($this->UsuarioModel->obtenerCantidadPreguntasRespondidasCorrectas($idUsuario));

        if ($totalDePreguntas > 0) {
            $porcentajeRespondido = ($cantidadPreguntasRespondidas / $totalDePreguntas) * 100;
            $porcentajeNoRespondido = 100 - $porcentajeRespondido;
        }
            $datos = [
                'porcentajeRespondido' => $porcentajeRespondido,
                'porcentajeNoRespondido' => $porcentajeNoRespondido,
                'nombre_usuario' => $usuario['nombre_usuario']
            ];

            // Crear el gráfico
            $grafico = new PieGraph(800, 600);
            $grafico->title->Set("Porcentaje de preguntas respondidas por usuario: " . $usuario['nombre_usuario']);

            // Crear un conjunto de datos de pie
            $datosPie = [$datos['porcentajeRespondido'], $datos['porcentajeNoRespondido']];
            $labels = ["Preguntas respondidas", "Preguntas no respondidas"]; // Etiquetas para mostrar en el gráfico

            $piePlot = new PiePlot($datosPie);
            $piePlot->SetLegends($labels);
            $piePlot->SetSize(0.3); // Tamaño del gráfico (0.3 = 30% del área total)
            $piePlot->SetCenter(0.5, 0.5); // Centrar el gráfico en la imagen

            $grafico->Add($piePlot);

            // Capturar la salida del gráfico en un buffer
            ob_start();
            $grafico->Stroke(_IMG_HANDLER);
            $image = $grafico->img->Stream();
            $imageContent = ob_get_contents();
            ob_end_clean();

            // Convertir la imagen a base64
            $graficoBase64 = base64_encode($imageContent);

            // Preparar los datos para la vista
            $homeData = [
                "tituloGestion" => "",
                "grafico_base64" => $graficoBase64, // Enviar la imagen base64 a la vista
                "espacioGestionUsuarios" => " ",
                "porcentajePreguntasRespondidas" => $datos['porcentajeRespondido'],
                "nombreUsuario" => $datos['nombre_usuario']
            ];

            $this->presenter->render("ver_gestion_admin", $homeData);
    }

    //------------------------------------ PREGUNTAS  ----------------------------------------------------

    public function obtenerCantidadPreguntasDelJuego()
    {
        $preguntasTotales = $this->model->obtenerCantidadPreguntasDelJuego();

        $homeData = array();
        $homeData["tituloGestion"] = "Cantidad de preguntas totales";
        $homeData["cantidad"] = $preguntasTotales;
        $homeData["espacioGestionPreguntas"] = " ";
        $this->presenter->render("ver_gestion_admin", $homeData);
    }

    public function cantidadPreguntasCreadas()
    {
        $cantidadCreadas = $this->model->cantidadPreguntasCreadas();
        $homeData = array();
        $homeData["tituloGestion"] = "Cantidad de preguntas creadas";
        $homeData["cantidad"] = $cantidadCreadas;
        $homeData["espacioGestionPreguntas"] = " ";
        $this->presenter->render("ver_gestion_admin", $homeData);
    }


    //------------------------------------ PARTIDA  ----------------------------------------------------

    public function obtenerCantidadPartidasJugadas()
    {
        $partidasTotales = $this->model->obtenerPartidasJugadas();
        $homeData = array();
        $homeData["tituloGestion"] = "Cantidad de partidas jugadas";
        $homeData["cantidad"] = $partidasTotales;
        $homeData["espacioGestionPartidas"] = " ";
        $this->presenter->render("ver_gestion_admin", $homeData);
    }
}
