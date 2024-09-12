# BELLO PIZZO
## História de Usuário 1: Cadastro de Cliente
Como um novo cliente,
Eu quero me cadastrar no sistema,
Para que eu possa fazer pedidos e acompanhar meu histórico de compras.

Critérios de Aceitação:

O sistema deve permitir o cadastro com e-mail, senha, nome e endereço.
O cliente deve receber um e-mail de confirmação após o cadastro.
O cliente deve ser capaz de fazer login após o cadastro.

## História de Usuário 2: Fazer Pedido
Como um cliente autenticado,
Eu quero escolher uma pizza e adicionar ao meu carrinho,
Para que eu possa fazer um pedido e receber a pizza em casa.

Critérios de Aceitação:

O cliente deve ser capaz de visualizar o menu de pizzas e suas opções (tamanho, ingredientes).
O cliente deve ser capaz de adicionar pizzas ao carrinho.
O cliente deve ser capaz de revisar o carrinho e confirmar o pedido.
O cliente deve receber uma confirmação do pedido e uma estimativa de entrega.

## História de Usuário 3: Consultar Histórico de Pedidos
Como um cliente autenticado,
Eu quero consultar meu histórico de pedidos,
Para que eu possa revisar meus pedidos passados e repetir pedidos anteriores facilmente.

Critérios de Aceitação:

O cliente deve ser capaz de ver uma lista de todos os pedidos anteriores.
O cliente deve ser capaz de visualizar os detalhes de cada pedido (itens, data, status).

Padrões de Projeto Utilizados
Active Record
O padrão de projeto Active Record é utilizado para facilitar a interação com a base de dados. Nesse padrão, cada modelo é responsável por gerenciar suas próprias operações de CRUD (Create, Read, Update, Delete) e validações. O modelo ProdutoModel, por exemplo, representa a tabela produtos e inclui métodos para realizar buscas, inserções e atualizações diretamente na tabela.

Exemplos:

procura($term): Busca produtos com base em um termo.
desfazerExclusao(int $id): Restaura um produto que foi marcado como excluído.
php
public function procura($term) {
    return $this->select('id, nome')
                ->like('nome', $term)
                ->withDeleted(true)
                ->get()
                ->getResult();
}
Factory Method (Método Fábrica)
O padrão Factory Method é utilizado para criar objetos com base em um conjunto de dados ou condições específicas. No projeto, o método criaSlug é um exemplo de como o padrão é aplicado. Este método gera e atribui um slug ao nome do produto antes da inserção ou atualização no banco de dados.

Exemplo:

criaSlug(array $data): Cria um slug a partir do nome do produto.
php
protected function criaSlug(array $data) {
    if (isset($data['data']['nome'])) {
        $data['data']['slug'] = mb_url_title($data['data']['nome'], '-', true);
    }
    return $data;
}
Esses padrões ajudam a manter o código organizado e a separar a lógica de negócios da lógica de acesso aos dados, promovendo uma arquitetura mais limpa e escalável.


