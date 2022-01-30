<?php

namespace App\Controller;

class TestController 
{
    public function index(): Response
    {
        var_dump('Page 1 fonctionnelle');die;
    }

    public function rand(){
        var_dump('page 2 validée');die;
    }
}
