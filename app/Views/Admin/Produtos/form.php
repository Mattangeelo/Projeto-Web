
<div class="form-row">

    <div class="form-group col-md-8">
        <label for="nome">Nome</label>
        <input type="text" class="form-control" name="nome" id="nome" value="<?php echo old('nome', esc($produto->nome)); ?>">
    </div>

    <div class="form-group col-md-4">
        <label for="categoria_id">Categoria</label>
        <select class="custom-select" name="categoria_id">
            <option value="">Escolha uma Categoria</option>
            <?php foreach($categorias as $categoria): ?>
                <?php if($produto->id): ?>
                    <option value="<?php echo $categoria->id; ?>" <?php echo($categoria->id == $produto->categoria_id ? 'selected' : '') ?>><?php echo esc($categoria->nome); ?></option>
                <?php else: ?>
                    <option value="<?php echo $categoria->id; ?>" ><?php echo esc($categoria->nome); ?></option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
    </div>

</div>
<div class="form-group col-md-12">
        <label for="ingredientes">Ingredientes</label>
        <textarea class="form-control" name="ingredientes" rows="4" id="ingredientes"><?php echo old('ingredientes', esc($produto->ingredientes)); ?></textarea>
</div>

<div class="form-check form-check-flat form-check-primary mb-4">
    <label for="is_admin" class="form-check-label">


        <input type="hidden" name="ativo" value="0">
        <input type="checkbox" class="form-check-input" id="ativo" name="ativo" value="1" <?php if(old('ativo',$produto->ativo)): ?> checked="" <?php endif; ?>>
            Ativo
    </label>
</div>

                    
<button type="submit" class="btn btn-secondary mr-2 btn-sm">
    <i class="mdi mdi-checkbox-marked-circle btn-icon-prepend"></i>
    Salvar
</button>
