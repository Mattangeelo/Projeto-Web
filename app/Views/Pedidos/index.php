<?php echo $this->extend('layout/principal_web'); ?>

<?php echo $this->section('titulo'); ?> Meus Pedidos <?php echo $this->endSection(); ?>

<?php echo $this->section('conteudo'); ?>

<div class="container mt-5">
    <h2 class="text-center">Meus Pedidos</h2>

    <?php if (empty($pedidos)): ?>
        <p class="text-center">Você ainda não fez nenhum pedido.</p>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col">ID do Pedido</th>
                    <th scope="col">Total</th>
                    <th scope="col">Status</th>
                    <th scope="col">Data</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedidos as $pedido): ?>
                    <tr>
                        <td><?php echo $pedido->id; ?></td>
                        <td><?php echo $pedido->slug; ?></td>
                        <td>R$ <?php echo number_format($pedido->total, 2); ?></td>
                        <td><?php echo ucfirst($pedido->status); ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($pedido->criado_em)); ?></td>
                        <td>
                            <a href="<?php echo site_url('pedidos/detalhes/' . $pedido->slug); ?>" class="btn btn-info btn-sm">Ver detalhes</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php echo $this->endSection(); ?>
