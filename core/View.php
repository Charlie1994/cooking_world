<?php

namespace core;
require dirname(__DIR__) . '/vendor/autoload.php';
/**
 * View
 *
 * PHP version 7.0
 */
class View
{

    /**
     * Render a view file
     *
     * @throws \Exception(File Not Found)
     * @param string $view  The view file
     * @param array $args  Associative array of data to display in the view (optional)
     *
     * @return string The rendered template
     */
    public static function render($view, $args = [])
    {
        // load views with Twig
        $loader = new \Twig_Loader_Filesystem(dirname(__DIR__).'/app/views');
        $twig = new \Twig_Environment($loader, array("debug"=>true));
        $twig->addExtension(new \Twig_Extension_Debug());
//        extract($args, EXTR_SKIP);
        $file = dirname(__DIR__) . "/app/views/$view";  // relative to Core directory
        if (is_readable($file)) {
            $template = $twig->load($view);
            return $template->render($args);
        } else {
            throw new \Exception("$file not found");
        }
    }

}
