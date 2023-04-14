<?php
abstract class ControllerAbstract
{
    protected function template($template)
    {
        return __DIR__ .'/../view/'. $template;
    }
}