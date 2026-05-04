<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../../helpers/prioridade.php'; ?>

<main class="max-w-7xl mx-auto px-4 py-10">

  <!-- ── Cabeçalho ── -->
  <div class="flex items-center justify-between mb-8">
    <div>
      <h1 class="text-2xl font-semibold text-gray-800">Chamados</h1>
      <p class="text-sm text-gray-500 mt-1">Acompanhe e gerencie todos os chamados de suporte</p>
    </div>
    <a href="/w5i-helpdesk/public/?url=chamados/criar" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold
              px-5 py-2.5 rounded-xl transition">
      + Novo chamado
    </a>
  </div>

  <!-- ── Cards de resumo ── -->
  <?php
    $totais    = ['Aberto' => 0, 'Em atendimento' => 0, 'Finalizado' => 0, 'Cancelado' => 0];
    $atrasados = 0;
    /** @var array $chamados */
    foreach ($chamados as $c) {
        if (isset($totais[$c['status']])) $totais[$c['status']]++;
        if ($c['atrasado']) $atrasados++;
    }
  ?>

  <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
      <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-1">Abertos</p>
      <p class="text-2xl font-bold text-blue-600"><?= $totais['Aberto'] ?></p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
      <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-1">Em atendimento</p>
      <p class="text-2xl font-bold text-yellow-500"><?= $totais['Em atendimento'] ?></p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
      <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-1">Finalizados</p>
      <p class="text-2xl font-bold text-green-500"><?= $totais['Finalizado'] ?></p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
      <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-1">Atrasados</p>
      <p class="text-2xl font-bold text-red-500"><?= $atrasados ?></p>
    </div>
  </div>

  <!-- ── Tabela ── -->
  <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

    <?php if (empty($chamados)): ?>

    <div class="flex flex-col items-center justify-center py-20">
      <div class="w-14 h-14 rounded-full bg-blue-50 flex items-center justify-center mb-4">
        <span class="text-2xl">📋</span>
      </div>
      <p class="text-sm font-medium text-gray-500 mb-1">Nenhum chamado cadastrado</p>
      <p class="text-xs text-gray-400 mb-6">Todos os chamados aparecerão aqui</p>
      <a href="/w5i-helpdesk/public/?url=chamados/criar" class="bg-blue-600 hover:bg-blue-700 text-white text-sm
                  font-semibold px-5 py-2 rounded-xl transition">
        Abrir primeiro chamado
      </a>
    </div>

    <?php else: ?>

    <?php
        /**
         * Pré-processa cada chamado antes de renderizar.
         * Garante que badge_status, cor_dot, label_prioridade e row_class
         * estejam disponíveis em ambos os loops — desktop e mobile —
         * sem variáveis indefinidas ou escopo implícito.
         */
        $chamadosProcessados = array_map(function (array $c): array {
            $c['badge_status'] = match($c['status']) {
                'Aberto'         => 'bg-blue-50 text-blue-600',
                'Em atendimento' => 'bg-yellow-50 text-yellow-600',
                'Finalizado'     => 'bg-green-50 text-green-600',
                'Cancelado'      => 'bg-gray-100 text-gray-400',
                default          => 'bg-gray-100 text-gray-400',
            };
            $c['cor_dot']          = corPorNivel($c['prioridade_nivel']);
            $c['label_prioridade'] = labelPorNivel($c['prioridade_nivel']);
            $c['row_class']        = $c['atrasado']
                ? 'bg-red-50/50 border-l-2 border-l-red-400'
                : 'hover:bg-blue-50/20 transition';
            return $c;
        }, $chamados);
      ?>

    <!-- ── Desktop ── -->
    <div class="hidden sm:block overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-gray-100">
            <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider w-10">#</th>
            <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Chamado</th>
            <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Setor</th>
            <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Urgência</th>
            <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
            <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Tempo</th>
            <th class="px-5 py-3.5 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Ação</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          <?php foreach ($chamadosProcessados as $c): ?>
          <tr class="<?= $c['row_class'] ?>">

            <td class="px-5 py-4 text-gray-300 font-mono text-xs">
              <?= $c['id'] ?>
            </td>

            <td class="px-5 py-4">
              <p class="font-medium text-gray-800 truncate max-w-[200px]">
                <?= htmlspecialchars($c['titulo']) ?>
              </p>
              <?php if (!empty($c['descricao'])): ?>
              <p class="text-xs text-gray-400 mt-0.5 truncate max-w-[200px]">
                <?= htmlspecialchars($c['descricao']) ?>
              </p>
              <?php endif; ?>
              <p class="text-xs text-gray-300 mt-1">
                <?= date('d/m/Y H:i', strtotime($c['criado_em'])) ?>
              </p>
            </td>

            <td class="px-5 py-4 text-xs text-gray-500">
              <?= htmlspecialchars($c['setor']) ?>
            </td>

            <td class="px-5 py-4">
              <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full flex-shrink-0 <?= $c['cor_dot'] ?>"
                  title="<?= $c['label_prioridade'] ?>"></span>
                <span class="text-xs text-gray-700 font-medium">
                  <?= htmlspecialchars($c['prioridade']) ?>
                </span>
              </div>
              <p class="text-xs text-gray-400 mt-1 ml-4">
                <?= $c['label_prioridade'] ?>
              </p>
            </td>

            <td class="px-5 py-4">
              <span class="inline-flex items-center px-2.5 py-1 rounded-lg
                               text-xs font-semibold <?= $c['badge_status'] ?>">
                <?= $c['status'] ?>
              </span>
              <?php if ($c['atrasado']): ?>
              <p class="text-xs text-red-500 font-medium mt-1">⚠ Atrasado</p>
              <?php endif; ?>
            </td>

            <td class="px-5 py-4">
              <?php if ($c['checkin_at']): ?>
              <div class="flex items-baseline gap-1">
                <span class="text-sm font-semibold
                                   <?= $c['atrasado'] ? 'text-red-600' : 'text-gray-700' ?>">
                  <?= $c['tempo_exibir'] ?>
                </span>
                <?php if ($c['atrasado']): ?>
                <span class="text-xs text-red-400">acima do prazo</span>
                <?php endif; ?>
              </div>
              <div class="mt-1 space-y-0.5">
                <p class="text-xs text-gray-400">
                  <span class="text-gray-300">início</span>
                  <?= date('d/m H:i', strtotime($c['checkin_at'])) ?>
                </p>
                <?php if ($c['checkout_at']): ?>
                <p class="text-xs text-gray-400">
                  <span class="text-gray-300">fim</span>
                  <?= date('d/m H:i', strtotime($c['checkout_at'])) ?>
                </p>
                <?php endif; ?>
              </div>
              <?php else: ?>
              <div class="flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-gray-200"></span>
                <span class="text-xs text-gray-400">Aguardando início</span>
              </div>
              <?php endif; ?>
            </td>

            <td class="px-5 py-4 text-right">
              <?php if ($c['status'] === 'Aberto'): ?>
              <div class="flex flex-col items-end gap-1.5">
                <form action="/w5i-helpdesk/public/?url=chamados/checkin" method="POST"
                  onsubmit="return confirm('Iniciar atendimento do chamado #<?= $c['id'] ?>?')">
                  <input type="hidden" name="id" value="<?= $c['id'] ?>">
                  <button type="submit" class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition">
                    Iniciar
                  </button>
                </form>
                <form action="/w5i-helpdesk/public/?url=chamados/cancelar" method="POST"
                  onsubmit="return confirm('Cancelar o chamado #<?= $c['id'] ?>?')">
                  <input type="hidden" name="id" value="<?= $c['id'] ?>">
                  <button type="submit" class="text-xs font-medium text-gray-400 hover:text-red-500 transition">
                    Cancelar
                  </button>
                </form>
              </div>

              <?php elseif ($c['status'] === 'Em atendimento'): ?>
              <button onclick="abrirCheckout(<?= $c['id'] ?>, '<?= htmlspecialchars($c['titulo'], ENT_QUOTES) ?>')"
                class="text-xs font-semibold text-green-600 hover:text-green-800 transition">
                Finalizar
              </button>

              <?php elseif (in_array($c['status'], ['Finalizado', 'Cancelado'])): ?>
              <form action="/w5i-helpdesk/public/?url=chamados/deletar" method="POST"
                onsubmit="return confirm('Excluir permanentemente o chamado #<?= $c['id'] ?>?')">
                <input type="hidden" name="id" value="<?= $c['id'] ?>">
                <button type="submit" class="text-xs font-medium text-red-400 hover:text-red-600 transition">
                  Excluir
                </button>
              </form>

              <?php else: ?>
              <span class="text-xs text-gray-300">—</span>
              <?php endif; ?>
            </td>

          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- ── Mobile ── -->
    <div class="sm:hidden divide-y divide-gray-100">
      <?php foreach ($chamadosProcessados as $c): ?>
      <div class="p-4 <?= $c['atrasado']
            ? 'bg-red-50/50 border-l-4 border-l-red-400'
            : 'hover:bg-blue-50/20 transition' ?>">

        <div class="flex items-start justify-between gap-3 mb-2">
          <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 mb-1">
              <span class="text-xs text-gray-300 font-mono">#<?= $c['id'] ?></span>
              <?php if ($c['atrasado']): ?>
              <span class="text-xs text-red-500 font-semibold">⚠ Atrasado</span>
              <?php endif; ?>
            </div>
            <p class="font-medium text-gray-800 text-sm truncate">
              <?= htmlspecialchars($c['titulo']) ?>
            </p>
          </div>
          <span class="inline-flex items-center px-2 py-0.5 rounded-lg
                           text-xs font-semibold <?= $c['badge_status'] ?> flex-shrink-0">
            <?= $c['status'] ?>
          </span>
        </div>

        <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-gray-400">
          <span>📁 <?= htmlspecialchars($c['setor']) ?></span>
          <span class="flex items-center gap-1">
            <span class="w-2 h-2 rounded-full <?= $c['cor_dot'] ?>"></span>
            <?= htmlspecialchars($c['prioridade']) ?>
          </span>
          <?php if ($c['checkin_at']): ?>
          <span class="<?= $c['atrasado'] ? 'text-red-500 font-semibold' : '' ?>">
            ⏱ <?= $c['tempo_exibir'] ?>
          </span>
          <?php endif; ?>
        </div>

      </div>
      <?php endforeach; ?>
    </div>

    <!-- ── Rodapé ── -->
    <div class="px-5 py-3 bg-gray-50/60 border-t border-gray-100
                  flex items-center justify-between">
      <span class="text-xs text-gray-400">
        <?= count($chamados) ?> chamado<?= count($chamados) !== 1 ? 's' : '' ?>
      </span>
      <?php if ($atrasados > 0): ?>
      <span class="text-xs text-red-500 font-semibold">
        ⚠ <?= $atrasados ?> atrasado<?= $atrasados !== 1 ? 's' : '' ?>
      </span>
      <?php endif; ?>
    </div>

    <?php endif; ?>
  </div>

</main>

<!-- ── Modal checkout ── -->
<div id="modal-checkout" class="hidden fixed inset-0 bg-black/40 z-50 flex items-center justify-center px-4">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">

    <div class="flex items-center justify-between mb-4">
      <h2 class="text-base font-semibold text-gray-800">Finalizar chamado</h2>
      <button onclick="fecharCheckout()"
        class="text-gray-300 hover:text-gray-500 transition text-2xl leading-none">×</button>
    </div>

    <p id="modal-titulo" class="text-sm text-gray-400 mb-5 pb-5 border-b border-gray-100"></p>

    <form action="/w5i-helpdesk/public/?url=chamados/checkout" method="POST">
      <input type="hidden" id="checkout-id" name="id" value="">

      <div class="mb-5">
        <label for="solucao" class="block text-sm font-semibold text-gray-700 mb-1.5">
          Solução aplicada <span class="text-red-400">*</span>
        </label>
        <textarea id="solucao" name="solucao" placeholder="Descreva o que foi feito para resolver o problema..."
          rows="4" required maxlength="2000" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm
                 text-gray-800 placeholder-gray-400 focus:outline-none
                 focus:ring-2 focus:ring-blue-500 focus:border-transparent
                 transition resize-none"></textarea>
      </div>

      <div class="flex gap-3">
        <button type="button" onclick="fecharCheckout()" class="flex-1 text-sm font-medium text-gray-500 hover:text-gray-700
                       py-2.5 rounded-xl border border-gray-200 hover:bg-gray-50 transition">
          Cancelar
        </button>
        <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white text-sm
                       font-semibold py-2.5 rounded-xl transition">
          Finalizar
        </button>
      </div>

    </form>
  </div>
</div>

<script>
function abrirCheckout(id, titulo) {
  document.getElementById('checkout-id').value = id;
  document.getElementById('modal-titulo').textContent = '#' + id + ' — ' + titulo;
  document.getElementById('solucao').value = '';
  document.getElementById('modal-checkout').classList.remove('hidden');
}

function fecharCheckout() {
  document.getElementById('modal-checkout').classList.add('hidden');
}

document.getElementById('modal-checkout').addEventListener('click', function(e) {
  if (e.target === this) fecharCheckout();
});

document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') fecharCheckout();
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>