<?php

namespace Drupal\hello\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;

class node_listController extends ControllerBase{

    public function content($nodetype = NULL){
        $node_types = $this-> entityTypeManager()->getStorage('node_type')->loadMultiple();
        //ksm($node_types);

        foreach ($node_types as $node_type){
            $url = new Url('hello.node_list', ['nodetype' => $node_type->id()]);
            $type_items[] = new link($node_type->label(), $url);
        }

        $node_types_list = [
            '#theme' =>'item_list',
            '#items' => $type_items,
        ];

        $node_storage = $this->entityTypeManager()->getStorage('node');
        $query = $node_storage->getQuery();
        if ($nodetype){
            $query->condition('type', $nodetype);
        }
        $nids = $query->pager(10)->execute();
        $nodes = $node_storage->loadMultiple($nids);
        $items = [];
        foreach ($nodes as $node){
            $items[] = $node->tolink();
        }
        $pager = ['#type' => 'pager'];
        
        $list = [
            '#theme' =>'item_list',
            '#items' => $items,
        ];

        return [
            'node_type' => $node_types_list,
            'list' => $list,
            'pager' => $pager,
            '#cache' => [
                'keys' => [ 'hello:node_list'],
                'tags' => [ 'node_list'],
                'contexts' => ['url'],
                ],
        ];
    }
}
