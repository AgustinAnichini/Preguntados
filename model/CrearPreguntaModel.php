<?php

class CrearPreguntaModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

   function obtenerCategorias(){
        $categorias = $this->database->query("SELECT * FROM categoria");
        return $categorias;
    }

    function sugerirPregunta($categoria,
                             $preguntaSugerida,
                             $respuestaIncorrecta1,
                             $respuestaIncorrecta2,
                             $respuestaIncorrecta3){
        $sePropuso = false;
        $idPreguntaSugerida = $this->insertarPregunta($categoria, $preguntaSugerida);
        if ($idPreguntaSugerida !== null){
            $sql = "INSERT INTO respuestaSugerida (texto, pregunta_id, correcta) 
                                                                    VALUES 
                                                                    ('$respuestaIncorrecta1','$idPreguntaSugerida' ,true),
                                                                    ('$respuestaIncorrecta2','$idPreguntaSugerida' ,false),
                                                                    ('$respuestaIncorrecta3','$idPreguntaSugerida' ,false)
                                                                        ";
            $this->database->execute($sql);
            $sePropuso = true;
        }
        return $sePropuso;
    }

    function insertarPregunta($categoria,$preguntaSugerida){
        $sql = "INSERT INTO preguntaSugerida (id_categoria, texto, nivel_dificultad,valor,cantidadAcertadas,cantidadEntregadas,tiempo_respuesta) 
                                                                VALUES (
                                                                '$categoria'
                                                                ,'$preguntaSugerida'
                                                                ,'bajo',0,0,0,0)";
        $this->database->execute($sql);

        $idPreguntaSugerida = $this->database->obtenerUltimoIdinsertado();
        return $idPreguntaSugerida;
    }

}