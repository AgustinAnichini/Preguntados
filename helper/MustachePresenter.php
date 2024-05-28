<?php

class MustachePresenter{
    private $mustache;


    public function __construct($partialsPathLoader){
        Mustache_Autoloader::register();
        $this->mustache = new Mustache_Engine(
            array(
                'partials_loader' => new Mustache_Loader_FilesystemLoader( $partialsPathLoader )
            ));

    }

    public function render($contentFile , $data = array() ){
//        $this->presenter->render("view/registro_view.mustache", []);

        echo  $this->generateHtml($contentFile, $data);
    }

    public function generateHtml($contentFile, $data = array()) {
        $contentAsString = file_get_contents('view/template/header.mustache');
        $contentAsString .= file_get_contents('view/' . $contentFile . '_view.mustache');
        $contentAsString .= file_get_contents('view/template/footer.mustache');
        return $this->mustache->render($contentAsString, $data);
    }


}