<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

use App\Entities\Bairro;

class Bairros extends BaseController
{

    private $bairroModel;

    public function __construct()
    {
        $this->bairroModel = new \App\Models\BairroModel();
    }
    public function index()
    {
        $data = [
            'titulo'=> 'Listando os Bairros',
            'bairros' => $this->bairroModel->withDeleted(true)->paginate(10),
            'pager' => $this->bairroModel->pager
        ];

        return view('Admin/Bairros/index',$data);
    }
}
