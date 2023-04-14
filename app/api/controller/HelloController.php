<?php

class HelloController extends ControllerAbstract
{
    public function saySome()
    {
        return $this->success(['name' => 'Benjamin']);
    }
}