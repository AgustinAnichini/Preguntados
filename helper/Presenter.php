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
            echo "Template {$template} not found.";
        }
    }

    private function includeView($view)
    {
        $viewPath = "view/{$view}";
        if (file_exists($viewPath)) {
            include_once $viewPath;
        } else {
            echo "View {$view} not found.";
        }
    }
}