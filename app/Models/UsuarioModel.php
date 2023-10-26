<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table            = 'usuarios';
    protected $returnType       = 'App\Entities\Usuario';
    protected $allowedFields    = ['nome','email','telefone'];
    protected $useSoftDeletes   = true;
    protected $useTimestamps    = true;
    protected $createdField     = 'criado_em'; // Nome da coluna no banco de dados
    protected $updatedField     = 'atualizado_em'; // Nome da coluna no banco de dados
    protected $deletedField     = 'deletado_em'; // Nome da coluna no banco de dados


    protected $validationRules = [
        'nome'     => 'required|max_length[120]|min_length[4]',
        'email'        => 'required|valid_email|is_unique[usuarios.email]',
        'cpf'        => 'required|exact_length[14]|is_unique[usuarios.cpf]',
        'password'     => 'required|min_length[6]',
        'password_confirmation' => 'required_with[password]|matches[password]',
    ];
    protected $validationMessages = [
        'email' => [
            'required' => 'Esse campo e Obrigatório.',
            'is_unique' => 'Desculpe. Esse email já existe.',
        ],
        'cpf' => [
            'required' => 'Esse campo e Obrigatório.',
            'is_unique' => 'Desculpe. Esse CPF já existe.',
        ],
        'nome' => [
            'required' => 'Esse campo e Obrigatório.',
        ],
    ];

    public function procurar($term) {
        if($term === null){
            return [];
        }

        return $this->select('id, nome')
                     ->like('nome', $term)
                     ->get()
                     ->getResult();
    }
    
}
