<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Carrinho</title>
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
   <h1 class="mb-4">Seu Carrinho</h1>

   <?php if (session()->getFlashdata('sucesso')): ?>
      <div class="alert alert-success">
         <?= session()->getFlashdata('sucesso') ?>
      </div>
   <?php endif; ?>

   <?php if (!empty($carrinho) && count($carrinho) > 0): ?>
      <table class="table table-bordered">
         <thead>
         <tr>
            <th>ID</th>
            <th>Quantidade</th>
            <th>Ações</th>
         </tr>
         </thead>
         <tbody>
         <?php foreach ($carrinho as $id => $item): ?>
            <tr>
               <td><?= htmlspecialchars($item['id']) ?></td>
               <td><?= htmlspecialchars($item['quantidade']) ?></td>
               <td>
                  <form action="/remover-item" method="post" style="display:inline-block;">
                     <input type="hidden" name="id" value="<?= htmlspecialchars($item['id']) ?>">
                     <button type="submit" class="btn btn-danger btn-sm">Remover</button>
                  </form>
               </td>
            </tr>
         <?php endforeach; ?>
         </tbody>
      </table>
      <a href="/finalizar-compra" class="btn btn-primary">Finalizar Compra</a>
   <?php else: ?>
      <div class="alert alert-warning">
         Seu carrinho está vazio.
      </div>
      <a href="/produtos" class="btn btn-secondary">Ver Produtos</a>
   <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>