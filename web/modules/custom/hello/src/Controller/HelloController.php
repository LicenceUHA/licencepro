<?php

namespace Drupal\hello\Controller;

use Drupal\Core\Controller\ControllerBase;

class HelloController extends ControllerBase{
    public function content($param){
        return ['#markup' => $this->t('le parametre: %name', ['%name'=>$param])];
    }
}