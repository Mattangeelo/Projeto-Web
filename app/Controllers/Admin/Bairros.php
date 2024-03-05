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

    public function procurar(){

        if(!$this->request->isAJAX()){
            exit('Pagina não encontrada');
        }

        $bairros =  $this->bairroModel->procurar($this->request->getGet('term'));

        $retorno = [];

        foreach($bairros as $bairro){
            $data['id'] = $bairro->id;
            $data['value'] = $bairro->nome;
            
            $retorno[] = $data;
        }

        return $this->response->setJSON($retorno);
        
    }

    public function show($id=null){

        $bairro = $this->buscabairroOu404($id);
       
        $data= [

            'titulo' => "$bairro->nome",
            'bairro' => $bairro,
        ];
        return view('Admin/Bairros/show',$data);
    }

    public function editar($id=null){

        $bairro = $this->buscabairroOu404($id);

        if($bairro->deletado_em != null){
            return redirect()->back()->with('info',"O bairro $bairro->nome ja encontra-se excluído. Portanto, não é possível editá-lo.");
        }
       
        $data= [

            'titulo' => "Editando a bairro $bairro->nome",
            'bairro' => $bairro,
        ];
        return view('Admin/Bairros/editar',$data);
    }

    public function atualizar ($id = null){

        if($this->request->getMethod() === 'post'){

            $bairro=$this->buscabairroOu404($id);

            if($bairro->deletado_em != null){
                return redirect()->back()->with('info',"O bairro $bairro->nome ja encontra-se excluído. Portanto, não é possível editá-lo.");
            }
            

            $bairro->fill($this->request->getPost());

            $bairro->valor_entrega = str_replace(",","",$bairro->valor_entrega);

            if(!$bairro->hasChanged()){

                return redirect()->back()->with('info','Não há dados para atualizar');
            }

            if($this->bairroModel->save($bairro)){

                return redirect()->to(site_url("admin/bairros/show/$bairro->id"))
                    ->with('sucesso',"bairro $bairro->nome atualizadop com Sucesso.");
            }else{

                return redirect()->back()
                    ->with('errors_model', $this->bairroModel->errors())
                    ->with('atencao','Por favor verifique os erros abaixo')
                    ->withInput();
            }

        }else{
            /* Não e post */
            return redirect()->back();
        }

    }

    private function buscabairroOu404(int $id=null){
        if(!$id || !$bairro = $this->bairroModel->withDeleted(true)->where('id',$id)->first()){
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Não encontramos o Bairro $id");
        }
        return $bairro;
    }
}
