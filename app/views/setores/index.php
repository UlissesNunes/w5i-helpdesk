<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<main class="max-w-4xl mx-auto px-4 py-10">

  <!-- ── Cabeçalho ── -->
  <div class="flex items-center justify-between mb-8">
    <div>
      <h1 class="text-2xl font-semibold text-gray-800">Setores</h1>
      <p class="text-sm text-gray-500 mt-1">Gerencie os setores da empresa</p>
    </div>
  </div>

  <!-- ── Formulário ── -->
  <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
    <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">
      Novo setor
    </h2>
    <form action="/w5i-helpdesk/public/?url=setores/salvar" method="POST">
      <div class="flex gap-3">
        <input type="text" name="nome" placeholder="Nome do setor" required maxlength="255" class="flex-1 border border-gray-200 rounded-xl px-4 py-2.5 text-sm
                 text-gray-800 placeholder-gray-400 focus:outline-none
                 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium
                 px-6 py-2.5 rounded-xl transition whitespace-nowrap">
          Salvar
        </button>
      </div>
    </form>
  </div>

  <!-- ── Listagem ── -->
  <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

    <?php if (empty($setores)): ?>

    <div class="flex flex-col items-center justify-center py-16">
      <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center mb-3">
        <span class="text-xl">🗂</span>
      </div>
      <p class="text-sm font-medium text-gray-500">Nenhum setor cadastrado</p>
      <p class="text-xs text-gray-400 mt-1">Adicione o primeiro setor acima</p>
    </div>

    <?php else: ?>

    <table class="w-full text-sm">
      <thead>
        <tr class="border-b border-gray-100">
          <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider w-12">#</th>
          <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Nome</th>
          <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider w-24">Ação</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-50">
        <?php foreach ($setores as $setor): ?>
        <tr class="hover:bg-blue-50/30 transition">

          <td class="px-6 py-4 text-gray-300 font-mono text-xs">
            <?= $setor['id'] ?>
          </td>

          <td class="px-6 py-4">
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center
                              justify-center flex-shrink-0">
                <span class="text-blue-500 text-xs font-bold">
                  <?= strtoupper(substr($setor['nome'], 0, 2)) ?>
                </span>
              </div>
              <span class="font-medium text-gray-800">
                <?= htmlspecialchars($setor['nome']) ?>
              </span>
            </div>
          </td>

          <td class="px-6 py-4 text-right">
            <form action="/w5i-helpdesk/public/?url=setores/deletar" method="POST"
              onsubmit="return confirm('Remover o setor \'<?= htmlspecialchars($setor['nome'], ENT_QUOTES) ?>\'?')">
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

    <div class="px-6 py-3 bg-gray-50/60 border-t border-gray-100">
      <p class="text-xs text-gray-400">
        <?= count($setores) ?> setor<?= count($setores) !== 1 ? 'es' : '' ?>
        cadastrado<?= count($setores) !== 1 ? 's' : '' ?>
      </p>
    </div>

    <?php endif; ?>

  </div>

</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>