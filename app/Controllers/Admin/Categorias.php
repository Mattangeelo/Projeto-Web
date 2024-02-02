<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;


class Categorias extends BaseController
{
    private $categoriaModel;

    public function __construct(){
        $this->categoriaModel= new \App\Models\CategoriaModel();
    }
    public function index()
    {
        
        $data = [
            'titulo'=> 'Listando as Categorias',
            'categorias' => $this->categoriaModel->withDeleted(true)->paginate(10),
            'pager' => $this->categoriaModel->pager
        ];

        return view('Admin/Categorias/index',$data);
    }

    public function procurar(){

        if(!$this->request->isAJAX()){
            exit('Pagina não encontrada');
        }

        $categorias =  $this->categoriaModel->procurar($this->request->getGet('term'));

        $retorno = [];

        foreach($categorias as $categoria){
            $data['id'] = $categoria->id;
            $data['value'] = $categoria->nome;
            
            $retorno[] = $data;
        }

        return $this->response->setJSON($retorno);
        
    }
}
