<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<main class="max-w-4xl mx-auto px-4 py-10">

  <!-- ── Cabeçalho da página ── -->
  <div class="flex items-center justify-between mb-8">
    <div>
      <h1 class="text-2xl font-semibold text-gray-800">Setores</h1>
      <p class="text-sm text-gray-500 mt-1">Gerencie os setores da empresa</p>
    </div>
  </div>

  <!-- ── Alertas ── -->
  <?php if (!empty($erro)): ?>
  <div class="mb-6 flex items-center gap-3 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
    <span>⚠</span>
    <span><?= htmlspecialchars($erro) ?></span>
  </div>
  <?php endif; ?>

  <?php if (!empty($sucesso)): ?>
  <div
    class="mb-6 flex items-center gap-3 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
    <span>✓</span>
    <span><?= htmlspecialchars($sucesso) ?></span>
  </div>
  <?php endif; ?>

  <!-- ── Formulário de cadastro ── -->
  <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
    <h2 class="text-base font-medium text-gray-700 mb-4">Novo setor</h2>

    <form action="/w5i-helpdesk/public/?url=setores/salvar" method="POST">
      <div class="flex gap-3">
        <input type="text" name="nome" placeholder="Nome do setor" required maxlength="255" class="flex-1 border border-gray-200 rounded-lg px-4 py-2.5 text-sm text-gray-800
                 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500
                 focus:border-transparent transition">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium
                 px-6 py-2.5 rounded-lg transition">
          Salvar
        </button>
      </div>
    </form>
  </div>

  <!-- ── Listagem ── -->
  <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

    <?php if (empty($setores)): ?>

    <!-- Estado vazio -->
    <div class="flex flex-col items-center justify-center py-16 text-gray-400">
      <span class="text-4xl mb-3">🗂</span>
      <p class="text-sm font-medium">Nenhum setor cadastrado</p>
      <p class="text-xs mt-1">Adicione o primeiro setor acima</p>
    </div>

    <?php else: ?>

    <table class="w-full text-sm">
      <thead>
        <tr class="bg-gray-50 border-b border-gray-100">
          <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-12">
            #
          </th>
          <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
            Nome
          </th>
          <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider w-24">
            Ação
          </th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-50">
        <?php foreach ($setores as $setor): ?>
        <tr class="hover:bg-gray-50 transition">

          <td class="px-6 py-4 text-gray-400 font-mono text-xs">
            <?= $setor['id'] ?>
          </td>

          <td class="px-6 py-4 text-gray-800 font-medium">
            <?= htmlspecialchars($setor['nome']) ?>
          </td>

          <td class="px-6 py-4 text-right">
            <form action="/w5i-helpdesk/public/?url=setores/deletar" method="POST"
              onsubmit="return confirm('Remover o setor \'<?= htmlspecialchars($setor['nome']) ?>\'?')">
              <input type="hidden" name="id" value="<?= $setor['id'] ?>">
              <button type="submit" class="text-xs font-medium text-red-400 hover:text-red-600 transition">
                Remover
              </button>
            </form>
          </td>

        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <!-- Rodapé da tabela -->
    <div class="px-6 py-3 bg-gray-50 border-t border-gray-100 text-xs text-gray-400">
      <?= count($setores) ?> setor<?= count($setores) !== 1 ? 'es' : '' ?>
      cadastrado<?= count($setores) !== 1 ? 's' : '' ?>
    </div>

    <?php endif; ?>

  </div>

</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>