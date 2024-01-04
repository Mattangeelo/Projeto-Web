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

            $this->enviaEmailRedefinicaoSenha($usuario);
            
            return redirect()->to;


        }else{
            /* Não e Post */
            return redirect()->back();
        }
    }

    private function enviaEmailRedefinicaoSenha (object $usuario){

        $email = service('email');

        $email->setFrom('no-replay@itaipu.com.br', 'Itaipu Engenharia');
        $email->setTo($usuario->email);


        $email->setSubject('Redefinição de Senha');

        $mensagem= view('Password/reset_email',['token' => $usuario->reset_token]);
        $email->setMessage('Testing the email class.');

        $email->send();
    }
}
