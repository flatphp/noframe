<?php

class HelloController extends ControllerAbstract
{
    public function world()
    {
        $name = 'Benjemin';
        include $this->template('hello/index.php');
    }
}