<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Entities\Medida;

class Medidas extends BaseController
{
    private $medidaModel;

    public function __construct() {

        $this->medidaModel = new \App\Models\MedidaModel();

    }


    public function index()
    {
        $data = [
            'titulo' => 'Listando as medidas de produtos',
            'medidas' => $this->medidaModel->withDeleted(true)->paginate(10),
            'pager' => $this->medidaModel->pager,
        ];

        return view('Admin/Medidas/index',$data);
    }
    public function procurar(){

        if(!$this->request->isAJAX()){
            exit('Pagina não encontrada');
        }

        $medidas =  $this->medidaModel->procurar($this->request->getGet('term'));

        $retorno = [];

        foreach($medidas as $medida){
            $data['id'] = $medida->id;
            $data['value'] = $medida->nome;
            
            $retorno[] = $data;
        }

        return $this->response->setJSON($retorno);
        
    }

    private function buscaMedidaOu404(int $id=null){
        if(!$id || !$medida = $this->medidaModel->withDeleted(true)->where('id',$id)->first()){
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Não encontramos a medida $id");
        }
        return $medida;
    }

    public function show($id=null){

        $medida = $this->buscaMedidaOu404($id);

        $data= [

            'titulo' => "$medida->nome",
            'medida' => $medida,
        ];
        return view('Admin/Medidas/show',$data);
    }
}
