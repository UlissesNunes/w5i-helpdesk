<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<main class="max-w-3xl mx-auto px-4 py-8">

  <div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-semibold text-gray-800">Setores</h1>
  </div>

  <?php if (!empty($erro)): ?>
  <div class="mb-4 px-4 py-3 bg-red-100 border border-red-300 text-red-700 rounded">
    <?= htmlspecialchars($erro) ?>
  </div>
  <?php endif; ?>

  <?php if (!empty($sucesso)): ?>
  <div class="mb-4 px-4 py-3 bg-green-100 border border-green-300 text-green-700 rounded">
    <?= htmlspecialchars($sucesso) ?>
  </div>
  <?php endif; ?>

  <!-- Formulário de cadastro -->
  <div class="bg-white rounded-lg shadow p-6 mb-8">
    <h2 class="text-lg font-medium text-gray-700 mb-4">Novo setor</h2>
    <form action="?url=setores/salvar" method="POST" class="flex gap-3">
      <input type="text" name="nome" placeholder="Nome do setor" required maxlength="255"
        class="flex-1 border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
      <button type="submit"
        class="bg-blue-600 text-white px-5 py-2 rounded text-sm font-medium hover:bg-blue-700 transition">
        Salvar
      </button>
    </form>
  </div>

  <!-- Listagem -->
  <div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-sm">
      <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
        <tr>
          <th class="px-4 py-3 text-left">#</th>
          <th class="px-4 py-3 text-left">Nome</th>
          <th class="px-4 py-3 text-right">Ação</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100">
        <?php if (empty($setores)): ?>
        <tr>
          <td colspan="3" class="px-4 py-6 text-center text-gray-400">
            Nenhum setor cadastrado.
          </td>
        </tr>
        <?php else: ?>
        <?php foreach ($setores as $s): ?>
        <tr class="hover:bg-gray-50 transition">
          <td class="px-4 py-3 text-gray-400"><?= $s['id'] ?></td>
          <td class="px-4 py-3 font-medium text-gray-800">
            <?= htmlspecialchars($s['nome']) ?>
          </td>
          <td class="px-4 py-3 text-right">
            <form action="?url=setores/deletar" method="POST" onsubmit="return confirm('Deseja remover este setor?')">
              <input type="hidden" name="id" value="<?= $s['id'] ?>">
              <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-medium transition">
                Remover
              </button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>