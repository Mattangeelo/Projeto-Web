<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Pedidos extends BaseController
{

    private $pedidosModel;

    public function __construct()
    {
        $this->pedidosModel = new \App\Models\PedidosModel();
    }

    public function index()
    {
        // Obtém o usuário atual (ajuste conforme sua lógica de autenticação)
        $usuario_id = session()->get('usuario_id');

        // Busca os últimos 5 pedidos do usuário, ordenados pela data mais recente
        $pedidos = $this->pedidosModel
            ->where('usuario_id', $usuario_id)
            ->orderBy('criado_em', 'DESC')
            ->findAll(5);

        return view('Pedidos/index', ['pedidos' => $pedidos]);
    }

}
