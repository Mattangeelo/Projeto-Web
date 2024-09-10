<?php echo $this->extend('layout/principal_web'); ?>


<?php echo $this->section('titulo'); ?> <?php echo $titulo ?> <?php echo $this->endSection(); ?>




<?php echo $this->section('estilos'); ?>

<<link rel="stylesheet" href="<?php echo site_url("web/src/assets/css/produto.css"); ?>" />

<?php echo $this->endSection(); ?>





<?php echo $this->section('conteudo'); ?>

<div class="container-fluid section" id="menu" data-aos="fade-up" style="margin-top: 3em">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <!-- product -->
        <div class="product-content product-wrap clearfix product-deatil">
            <div class="row">
                <?php if (!isset($carrinho)): ?>
                    <h2 class="text-center">Seu Carrinho de compras está vazio</h2>
                    <div class="col-sm-4">
                        <a href="<?php echo site_url("/"); ?>" class="btn btn-lg"
                            style="background-color: #990100;color:white;margin-top:2em">Voltar</a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <h3 class="text-center">Carrinho de Compras</h3>

                        <?php if (session()->has('error_model')): ?>
                            <ul style="list-style: decimal">
                                <?php foreach (session('errors_model') as $error): ?>
                                    <li class="text-danger"><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>

                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="text-center" scope="col">Remover</th>
                                    <th scope="col">Produto</th>
                                    <th scope="col">Tamanho</th>
                                    <th class="text-center" scope="col">Quantidade</th>
                                    <th scope="col">Preço</th>
                                    <th scope="col">Sub Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $total = 0; ?>
                                <?php foreach ($carrinho as $produto): ?>
                                    <tr>
                                        <th class="text-center" scope="row">
                                            <a class="btn btn-danger btn-sm"
                                                href="<?php echo site_url("carrinho/remover/$produto->slug"); ?>">X</a>
                                        </th>
                                        <td><?php echo esc($produto->nome); ?></td>
                                        <td><?php echo esc($produto->tamanho); ?></td>
                                        <td class="text-center">
                                            <?php echo form_open("carrinho/atualizar", ['class' => 'form-inline']); ?>
                                            <div class="form-group">
                                                <input type="number" class="form-control" name="produto[quantidade]"
                                                    value="<?php echo $produto->quantidade; ?>" min="1" max="10" ste="1"
                                                    required="">
                                                <input type="hidden" name="produto[slug]" value="<?php echo $produto->slug ?>">
                                            </div>

                                            <button type="submit" class="btn btn-primary float-rigth">
                                                <i class="fa fa-refresh"></i>
                                            </button>
                                            <?php echo form_close(); ?>
                                        </td>
                                        <td>R$ <?php echo esc($produto->preco); ?></td>
                                        <?php
                                        $subTotal = $produto->preco * $produto->quantidade;
                                        $total += $subTotal;
                                        ?>
                                        <td>R$ <?php echo number_format($subTotal, 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td class="text-right" colspan="5" style="font-weight: bold">Total Produtos:</td>
                                    <td colspan="5">R$ <?php echo number_format($total, 2); ?></td>
                                </tr>
                                <tr>
                                    <td class="text-right border-top-0" colspan="5" style="font-weight: bold">Taxa de
                                        entrega:</td>
                                    <td class="border-top-o" colspan="5" id="valor_entrega">R$ 30.00</td>
                                </tr>
                                <tr>
                                    <td class="text-right border-top-0" colspan="5" style="font-weight: bold">Total</td>
                                    <td class="border-top-o" colspan="5" id="total">
                                        <?php echo 'R$&nbsp;' . number_format($total, 2); ?>
                                    </td>
                                </tr>
                            </tbody>

                        </table>
                        <div class="row">
                            <div class="col-sm-6">
                                <!-- Formulário para enviar o pedido via POST -->
                                <form action="<?php echo site_url('carrinho/concluir'); ?>" method="post">
                                    <!-- Para segurança, adicione o token CSRF se ele estiver ativado -->
                                    <?php echo csrf_field(); ?>

                                    <!-- Passa o total e outros dados importantes para o controller -->
                                    <input type="hidden" name="total" value="<?php echo number_format($total, 2); ?>">

                                    <!-- Enviar dados dos produtos como array -->
                                    <?php foreach ($carrinho as $produto): ?>
                                        <input type="hidden" name="produtos[<?php echo $produto->slug; ?>][nome]"
                                            value="<?php echo esc($produto->nome); ?>">
                                        <input type="hidden" name="produtos[<?php echo $produto->slug; ?>][quantidade]"
                                            value="<?php echo esc($produto->quantidade); ?>">
                                        <input type="hidden" name="produtos[<?php echo $produto->slug; ?>][preco]"
                                            value="<?php echo esc($produto->preco); ?>">
                                        <input type="hidden" name="produtos[<?php echo $produto->slug; ?>][slug]"
                                            value="<?php echo esc($produto->slug); ?>">
                                    <?php endforeach; ?>

                                    <!-- Botão para submeter o formulário -->
                                    <button type="submit" id="btn-concluir" class="btn btn-success">
                                        Concluir Pedido
                                    </button>
                                </form>

                                <!-- Link para voltar à página inicial -->
                                <a href="<?php echo site_url("/"); ?>" class="btn btn-info ml-2">Voltar</a>
                            </div>
                        </div>




                    </div>
                <?php endif; ?>
            </div>
            <!-- end product -->
        </div>
    </div>
    <?php echo $this->endSection(); ?>





    <?php echo $this->section('scripts'); ?>
    <script>

    </script>

    <?php echo $this->endSection(); ?>