<?php

class EditorModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }
    //------------------------------------DAR DE ALTA PREGUNTAS----------------------------------------------------


    function altaPreguntaSugerida(   $categoria,
                             $pregunta,
                             $respuestaCorrecta,
                             $respuestaIncorrecta1,
                             $respuestaIncorrecta2,
                             $respuestaIncorrecta3,
                             $nivel_dificultad,
                             $valor){


        $sePropuso = false;
        $idPreguntaSugerida = $this->insertAltaPregunta($categoria, $pregunta,$nivel_dificultad,$valor);

        if ($idPreguntaSugerida !== null){
            $textoRespuestaCorrecta = $respuestaCorrecta["texto"];
            $textoRespuestaIncorrecta1 = $respuestaIncorrecta1["texto"];
            $textoRespuestaIncorrecta2 = $respuestaIncorrecta2["texto"];
            $textoRespuestaIncorrecta3 = $respuestaIncorrecta3["texto"];

            $sql = "INSERT INTO respuesta (texto, pregunta_id, correcta) 
                                                                    VALUES 
                                                                    ('$textoRespuestaCorrecta','$idPreguntaSugerida' ,true),
                                                                    ('$textoRespuestaIncorrecta1','$idPreguntaSugerida' ,false),
                                                                    ('$textoRespuestaIncorrecta2','$idPreguntaSugerida' ,false),
                                                                    ('$textoRespuestaIncorrecta3','$idPreguntaSugerida' ,false) ";
            $this->database->execute($sql);
            $sePropuso = true;
        }
        return $sePropuso;
    }
    function altaNuevaPregunta(   $categoria,
                             $pregunta,
                             $respuestaCorrecta,
                             $respuestaIncorrecta1,
                             $respuestaIncorrecta2,
                             $respuestaIncorrecta3,
                             $nivel_dificultad,
                             $valor){


        $sePropuso = false;
        $idPreguntaSugerida = $this->insertAltaPregunta($categoria, $pregunta,$nivel_dificultad,$valor);

        if ($idPreguntaSugerida !== null){
            $sql = "INSERT INTO respuesta (texto, pregunta_id, correcta) 
                                                                    VALUES 
                                                                    ('$respuestaCorrecta','$idPreguntaSugerida' ,true),
                                                                    ('$respuestaIncorrecta1','$idPreguntaSugerida' ,false),
                                                                    ('$respuestaIncorrecta2','$idPreguntaSugerida' ,false),
                                                                    ('$respuestaIncorrecta3','$idPreguntaSugerida' ,false) ";
            $this->database->execute($sql);
            $sePropuso = true;
        }
        return $sePropuso;
    }

    function insertAltaPregunta($categoria,$pregunta,$nivel_dificultad,$valor){
        $sql = "INSERT INTO pregunta (id_categoria, texto, nivel_dificultad,valor,cantidadAcertadas,cantidadEntregadas,tiempo_respuesta) 
                                                                VALUES (
                                                                '$categoria'
                                                                ,'$pregunta'
                                                                ,'$nivel_dificultad','$valor',0,0,30)";
        $this->database->execute($sql);

        $idPreguntaSugerida = $this->database->obtenerUltimoIdinsertado();
        return $idPreguntaSugerida;
    }


//------------------------------------DAR DE BAJA PREGUNTAS----------------------------------------------------
    function darDeBajaExistente($idPregunta)
    {
        $sql="DELETE FROM respuesta where pregunta_id = '$idPregunta'";
        $this->database->execute($sql);


        $sql="DELETE FROM pregunta where id = '$idPregunta'";
        $this->database->execute($sql);
    }

    function cambiarEstadoPreguntaSugerida($idPregunta)
    {
        $sql="UPDATE preguntaSugerida SET pendiente = false WHERE id = '$idPregunta'";
        $this->database->execute($sql);
    }
    function darDeBajaPreguntaReportada($idPregunta)
    {
        $sql="DELETE FROM reporte where id_pregunta = '$idPregunta'";
        $this->database->execute($sql);
    }

    function darDeBajaPreguntaUsuario($idPregunta)
    {
        $sql="DELETE FROM preguntaUsuario where idPregunta = $idPregunta";
        $this->database->execute($sql);
    }

//------------------------------------MODIFICAR PREGUNTA----------------------------------------------------



    public function modificarCategoria($categoria, $idPregunta)
    {
        $sql = "update pregunta set id_categoria = '$categoria' where id = '$idPregunta'";
        $this->database->execute($sql);
    }
    public function modificarDificultad($nivel_dificultad, $idPregunta)
    {
        $sql = "update pregunta set nivel_dificultad = '$nivel_dificultad' where id = '$idPregunta'";
        $this->database->execute($sql);
    }
    public function modificarTexto($texto, $idPregunta)
    {
        $sql = "update pregunta set texto = '$texto' where id = '$idPregunta'";
        $this->database->execute($sql);
    }
    public function modificarValor($valor, $idPregunta)
    {
        $sql = "update pregunta set valor = '$valor' where id = '$idPregunta'";
        $this->database->execute($sql);
    }

    function modificarRespuestas($idPregunta,$idDeRespuestas,$respuestaCorrecta,$respuestaIncorrecta1,$respuestaIncorrecta2,$respuestaIncorrecta3){
        $idDeRespuestas;// aca exploto
        $idRespuestaCorrecta = $idDeRespuestas[0]['id'];
        $idRespuestaIncorrecta1 = $idDeRespuestas[1]['id'];
        $idRespuestaIncorrecta2 = $idDeRespuestas[2]['id'];
        $idRespuestaIncorrecta3 = $idDeRespuestas[3]['id'];

        $sql = "UPDATE respuesta SET texto = '$respuestaCorrecta', correcta = 1 WHERE pregunta_id = '$idPregunta' and id = '$idDeRespuestas[0]['id']'";
        $this->database->execute($sql);

        $sql = "UPDATE respuesta SET texto = '$respuestaIncorrecta1' , correcta = 0 where pregunta_id = '$idPregunta'and id = '$idRespuestaIncorrecta1'";
        $this->database->execute($sql);

        $sql = "UPDATE respuesta SET texto = '$respuestaIncorrecta2', correcta = 0 where pregunta_id = '$idPregunta' and id = '$idRespuestaIncorrecta2'";
        $this->database->execute($sql);

        $sql = "UPDATE respuesta SET texto = '$respuestaIncorrecta3', correcta = 0 where pregunta_id = '$idPregunta' and id ='$idRespuestaIncorrecta3'";
        $this->database->execute($sql);

    }


    //------------------------------------OBTENER PREGUNTAS----------------------------------------------------

    public function obtenerPreguntasReportadas(){
        $sql = "SELECT DISTINCT id_pregunta FROM reporte";
        $result = $this->database->query($sql);
//        var_dump($result); // 5 y 7

        $preguntasReportadas = $this->obtenerPreguntas($result);
        return $preguntasReportadas;
    }

    public function obtenerPreguntas($idReportadas){
        if (empty($idReportadas)) {
            return [];
        }
        $idList = array_column($idReportadas, 'id_pregunta');
        $ids = implode(',', $idList);

        $sql = "SELECT * FROM pregunta WHERE id IN ($ids)";
        $result = $this->database->query($sql);

        return $result;
    }

    public function obtenerPreguntasSugeridas(){
        $sql = "SELECT * FROM preguntaSugerida WHERE pendiente = true";
        $preguntasSugeridas = $this->database->query($sql);
        return $preguntasSugeridas;
    }

    public function obtenerPreguntasExistentes(){
        $sql = "SELECT * FROM pregunta";
        $preguntasExistentes = $this->database->query($sql);
        return $preguntasExistentes;
    }

    //------------------------------------OBTENER PREGUNTA INDIVIDUAL----------------------------------------------------
    public function obtenerPreguntaPorId($idReportada){
        $sql = "SELECT * FROM pregunta WHERE id = $idReportada";
        $preguntaReportada = $this->database->query($sql);
        return $preguntaReportada;
    }
    //------------------------------------OBTENER MENSAJE REPORTE----------------------------------------------------

    public function obtenerMensajeReporte($idPregunta) {
        $sql = "SELECT texto FROM reporte WHERE id_pregunta = $idPregunta";
        $result = $this->database->query($sql);

        if (!empty($result)) {
            return $result[0]['texto'];
        } else {
            return null;
        }
    }

}
