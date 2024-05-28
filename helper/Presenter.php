<?php

class Presenter
{
    public function __construct()
    {
    }

    public function render($view, $data = [])
    {
        $this->includeTemplate('nav.mustache');
        $this->includeView($view);
        $this->includeTemplate('nav-admin.mustache');
    }

    private function includeTemplate($template)
    {
        $templatePath = "view/template/{$template}";
        if (file_exists($templatePath)) {
            include_once $templatePath;
        } else {
            // Manejar el caso en el que el archivo de plantilla no existe
            // Por ejemplo, puedes lanzar una excepción o mostrar un mensaje de error.
            echo "Template {$template} not found.";
        }
    }

    private function includeView($view)
    {
        $viewPath = "view/{$view}";
        if (file_exists($viewPath)) {
            include_once $viewPath;
        } else {
            // Manejar el caso en el que el archivo de vista no existe
            // Por ejemplo, puedes lanzar una excepción o mostrar un mensaje de error.
            echo "View {$view} not found.";
        }
    }
}