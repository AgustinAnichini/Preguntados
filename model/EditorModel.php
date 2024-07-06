<?php

class EditorModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }
    //------------------------------------DAR DE ALTA PREGUNTAS----------------------------------------------------


    function altaPregunta(   $categoria,
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
                                                                    ('$respuestaIncorrecta3','$idPreguntaSugerida' ,false)
                                                                        ";
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
                                                                ,$nivel_dificultad,$valor,0,0,30)";
        $this->database->execute($sql);

        $idPreguntaSugerida = $this->database->obtenerUltimoIdinsertado();
        return $idPreguntaSugerida;
    }


//------------------------------------DAR DE BAJA PREGUNTAS----------------------------------------------------
    function darDeBajaExistente($idPregunta)
    {
        $sql="DELETE FROM pregunta where id = $idPregunta";
        $this->database->execute($sql);

        $sql="DELETE FROM respuesta where pregunta_id = $idPregunta";
        $this->database->execute($sql);

    }

    function darDeBajaPreguntaSugerida($idPregunta)
    {
        $sql="DELETE FROM preguntaSugerida where id = $idPregunta";
        $this->database->execute($sql);

        $sql="DELETE FROM respuestaSugerida where pregunta_id = $idPregunta";
        $this->database->execute($sql);
    }
    function darDeBajaPreguntaReportada($idPregunta)
    {
        $sql="DELETE FROM reporte where id_pregunta = $idPregunta";
        $this->database->execute($sql);
    }

//------------------------------------MODIFICAR PREGUNTA----------------------------------------------------


    function modificarPregunta($categoria,
                               $idPregunta,
                               $texto,
                               $respuestaCorrecta,
                               $respuestaIncorrecta1,
                               $respuestaIncorrecta2,
                               $respuestaIncorrecta3,
                               $nivel_dificultad,
                               $valor){
        $this->modificarCategoria($categoria, $idPregunta); // id
        $this->modificarDificultad($nivel_dificultad, $idPregunta);
        $this->modificarTexto($texto, $idPregunta);
        $this->modificarValor($valor, $idPregunta);
        $this->modificarRespuestas($idPregunta,$respuestaCorrecta,$respuestaIncorrecta1,$respuestaIncorrecta2,$respuestaIncorrecta3);
    }


    public function modificarCategoria($categoria, $idPregunta)
    {
        $sql = "update pregunta set categoria = $categoria where id = $idPregunta";
        $this->database->execute($sql);
    }
    public function modificarDificultad($nivel_dificultad, $idPregunta)
    {
        $sql = "update pregunta set nivel_dificultad = $nivel_dificultad where id = $idPregunta";
        $this->database->execute($sql);
    }
    public function modificarTexto($texto, $idPregunta)
    {
        $sql = "update pregunta set texto = $texto where id = $idPregunta";
        $this->database->execute($sql);
    }
    public function modificarValor($valor, $idPregunta)
    {
        $sql = "update pregunta set valor = $valor where id = $idPregunta";
        $this->database->execute($sql);
    }

    function modificarRespuestas($idPregunta,$respuestaCorrecta,$respuestaIncorrecta1,$respuestaIncorrecta2,$respuestaIncorrecta3){

        $sql = "UPDATE respuesta SET texto = $respuestaCorrecta, correcta = 1 WHERE pregunta_id = $idPregunta";
        $this->database->execute($sql);

        $sql = "update respuesta set texto = $respuestaIncorrecta1 , correcta = 0 where pregunta_id = $idPregunta";
        $this->database->execute($sql);

        $sql = "update respuesta set texto = $respuestaIncorrecta2, correcta = 0 where pregunta_id = $idPregunta";
        $this->database->execute($sql);

        $sql = "update respuesta set texto = $respuestaIncorrecta3, correcta = 0 where pregunta_id = $idPregunta";
        $this->database->execute($sql);
    }


    //------------------------------------OBTENER PREGUNTAS----------------------------------------------------

    public function obtenerPreguntasReportadas(){
        $sql = "SELECT DISTINCT  id_pregunta FROM reporte";
        $result = $this->database->query($sql);
    var_dump($result);

        $preguntasReportadas = $this->obtenerPreguntas($result);
        return $preguntasReportadas;
    }

    public function obtenerPreguntas($idReportadas){
        if (empty($idReportadas)) {
            return [];
        }
        $ids = implode(',', array_map('intval', $idReportadas));
        $sql = "SELECT * FROM pregunta WHERE id IN ($ids)";
        $preguntasReportadas = $this->database->query($sql);

        return $preguntasReportadas;
    }

    public function obtenerPreguntasSugeridas(){
        $sql = "SELECT * FROM preguntaSugerida";
        $preguntasSugeridas = $this->database->query($sql);
        return $preguntasSugeridas;
    }

    public function obtenerPreguntasExistentes(){
        $sql = "SELECT * FROM pregunta";
        $preguntasExistentes = $this->database->query($sql);
        return $preguntasExistentes;
    }

    //------------------------------------OBTENER PREGUNTA INDIVIDUAL----------------------------------------------------
    public function obtenerPreguntaReportadaPorId($idReportada){
        $sql = "SELECT * FROM pregunta WHERE id = $idReportada";
        $preguntaReportada = $this->database->query($sql);
        return $preguntaReportada;
    }

}
