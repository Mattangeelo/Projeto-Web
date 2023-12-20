<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Password extends BaseController
{

    private $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new \App\Models\UsuarioModel();
    }

    public function esqueci(){

        $data = [
            'titulo' => 'Esqueci a minha senha',
        ];

        return view('Password/esqueci',$data);
    }

    public function processaEsqueci(){

        if($this->request->getMethod() === 'post'){

            $usuario = $this->usuarioModel->buscaUsuarioPorEmail($this->request->getPost('email'));

            if($usuario === null || !$usuario->ativo){
                return redirect()->to(\site_url('password/esqueci'))->with('atencao','Usuario não encontrado!')->withInput();
            }


            $usuario->iniciaPasswordReset();

            dd($usuario);

        }else{
            /* Não e Post */
            return redirect()->back();
        }
    }
}
