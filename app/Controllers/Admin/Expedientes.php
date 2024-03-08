<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;


class Expedientes extends BaseController
{
    private $expedienteModel;

    public function __construct(){
        $this->expedienteModel = new \App\Models\ExpedienteModel();
    }
    public function Expedientes()
    {
        $data = [
            'titulo' => 'Expediente',
            'expedientes' => $this->expedienteModel->findall(),
        ];
        return view('Admin/Expedientes/expedientes',$data);
    }
}
