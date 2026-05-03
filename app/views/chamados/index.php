<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<main class="max-w-7xl mx-auto px-4 py-10">

  <!-- ── Cabeçalho ── -->
  <div class="flex items-center justify-between mb-8">
    <div>
      <h1 class="text-2xl font-semibold text-gray-800">Chamados</h1>
      <p class="text-sm text-gray-500 mt-1">
        Acompanhe e gerencie todos os chamados de suporte
      </p>
    </div>
    <a href="/w5i-helpdesk/public/?url=chamados/criar" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium
              px-5 py-2.5 rounded-lg transition">
      + Novo chamado
    </a>
  </div>

  <!-- ── Alertas ── -->
  <?php if (!empty($erro)): ?>
  <div class="mb-6 flex items-center gap-3 px-4 py-3 bg-red-50
                border border-red-200 text-red-700 rounded-lg text-sm">
    <span>⚠</span>
    <span><?= htmlspecialchars($erro) ?></span>
  </div>
  <?php endif; ?>

  <?php if (!empty($sucesso)): ?>
  <div class="mb-6 flex items-center gap-3 px-4 py-3 bg-green-50
                border border-green-200 text-green-700 rounded-lg text-sm">
    <span>✓</span>
    <span><?= htmlspecialchars($sucesso) ?></span>
  </div>
  <?php endif; ?>

  <!-- ── Listagem ── -->
  <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

    <?php if (empty($chamados)): ?>

    <!-- Estado vazio -->
    <div class="flex flex-col items-center justify-center py-20 text-gray-400">
      <span class="text-5xl mb-4">📋</span>
      <p class="text-sm font-medium">Nenhum chamado aberto</p>
      <p class="text-xs mt-1 mb-6">Todos os chamados aparecerão aqui</p>
      <a href="/w5i-helpdesk/public/?url=chamados/criar" class="bg-blue-600 hover:bg-blue-700 text-white text-sm
                  font-medium px-5 py-2 rounded-lg transition">
        Abrir primeiro chamado
      </a>
    </div>

    <?php else: ?>

    <table class="w-full text-sm">
      <thead>
        <tr class="bg-gray-50 border-b border-gray-100">
          <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-10">
            #
          </th>
          <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
            Chamado
          </th>
          <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
            Setor
          </th>
          <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
            Prioridade
          </th>
          <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
            Status
          </th>
          <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
            Tempo
          </th>
          <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
            Ação
          </th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-50">

        <?php foreach ($chamados as $c): ?>

        <?php
              // ── Badge de status ──────────────────────────
              $badgeStatus = match($c['status']) {
                'Aberto'         => 'bg-blue-100 text-blue-700',
                'Em atendimento' => 'bg-yellow-100 text-yellow-700',
                'Finalizado'     => 'bg-green-100 text-green-700',
                'Cancelado'      => 'bg-gray-100 text-gray-500',
                default          => 'bg-gray-100 text-gray-500',
              };

              // ── Badge de prioridade ──────────────────────
              $badgePrioridade = match(true) {
                $c['tempo_estimado_horas'] <= 2  => 'bg-red-100 text-red-700',
                $c['tempo_estimado_horas'] <= 8  => 'bg-orange-100 text-orange-700',
                $c['tempo_estimado_horas'] <= 24 => 'bg-yellow-100 text-yellow-700',
                default                          => 'bg-green-100 text-green-700',
              };

              // ── Linha atrasada ───────────────────────────
              $linhaClass = $c['atrasado']
                ? 'bg-red-50 hover:bg-red-100 border-l-4 border-l-red-400'
                : 'hover:bg-gray-50';
            ?>

        <tr class="transition <?= $linhaClass ?>">

          <!-- ID -->
          <td class="px-4 py-4 text-gray-400 font-mono text-xs" data-label="#">
            <?= $c['id'] ?>
          </td>

          <!-- Título + descrição -->
          <td class="px-4 py-4" data-label="Chamado">
            <p class="font-medium text-gray-800">
              <?= htmlspecialchars($c['titulo']) ?>
            </p>
            <?php if (!empty($c['descricao'])): ?>
            <p class="text-xs text-gray-400 mt-0.5 truncate max-w-xs">
              <?= htmlspecialchars($c['descricao']) ?>
            </p>
            <?php endif; ?>
            <p class="text-xs text-gray-300 mt-1">
              <?= date('d/m/Y H:i', strtotime($c['criado_em'])) ?>
            </p>
          </td>

          <!-- Setor -->
          <td class="px-4 py-4 text-gray-600" data-label="Setor">
            <?= htmlspecialchars($c['setor']) ?>
          </td>

          <!-- Prioridade -->
          <td class="px-4 py-4" data-label="Prioridade">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full
                             text-xs font-medium <?= $badgePrioridade ?>">
              <?= htmlspecialchars($c['prioridade']) ?>
            </span>
            <p class="text-xs text-gray-400 mt-1">
              SLA: <?= $c['tempo_estimado_horas'] ?>h
            </p>
          </td>

          <!-- Status -->
          <td class="px-4 py-4" data-label="Status">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full
                             text-xs font-medium <?= $badgeStatus ?>">
              <?= $c['status'] ?>
            </span>
            <?php if ($c['atrasado']): ?>
            <p class="text-xs text-red-500 font-medium mt-1">
              ⚠ Atrasado
            </p>
            <?php endif; ?>
          </td>

          <!-- Tempo -->
          <td class="px-4 py-4" data-label="Tempo">
            <?php if ($c['checkin_at']): ?>
            <p class="text-sm <?= $c['atrasado'] ? 'text-red-600 font-semibold' : 'text-gray-700' ?>">
              <?= $c['tempo_exibir'] ?>
            </p>
            <p class="text-xs text-gray-400 mt-0.5">
              Início: <?= date('d/m H:i', strtotime($c['checkin_at'])) ?>
            </p>
            <?php if ($c['checkout_at']): ?>
            <p class="text-xs text-gray-400">
              Fim: <?= date('d/m H:i', strtotime($c['checkout_at'])) ?>
            </p>
            <?php endif; ?>
            <?php else: ?>
            <span class="text-gray-300 text-xs">Não iniciado</span>
            <?php endif; ?>
          </td>

          <!-- Ação -->
          <td class="px-4 py-4 text-right" data-label="Ação">

            <?php if ($c['status'] === 'Aberto'): ?>
            <!-- Check-in -->
            <form action="/w5i-helpdesk/public/?url=chamados/checkin" method="POST"
              onsubmit="return confirm('Iniciar atendimento do chamado #<?= $c['id'] ?>?')">
              <input type="hidden" name="id" value="<?= $c['id'] ?>">
              <button type="submit" class="text-xs font-medium text-blue-600
                                   hover:text-blue-800 transition">
                Iniciar
              </button>
            </form>

            <?php elseif ($c['status'] === 'Em atendimento'): ?>
            <!-- Check-out — abre o modal inline -->
            <button onclick="abrirCheckout(<?= $c['id'] ?>, '<?= htmlspecialchars($c['titulo'], ENT_QUOTES) ?>')"
              class="text-xs font-medium text-green-600 hover:text-green-800 transition">
              Finalizar
            </button>

            <?php elseif ($c['status'] === 'Finalizado'): ?>
            <span class="text-xs text-gray-300">Concluído</span>

            <?php else: ?>
            <span class="text-xs text-gray-300">—</span>
            <?php endif; ?>

          </td>

        </tr>

        <?php endforeach; ?>

      </tbody>
    </table>

    <!-- Rodapé da tabela -->
    <div class="px-6 py-3 bg-gray-50 border-t border-gray-100
                  flex items-center justify-between">
      <span class="text-xs text-gray-400">
        <?= count($chamados) ?> chamado<?= count($chamados) !== 1 ? 's' : '' ?>
        encontrado<?= count($chamados) !== 1 ? 's' : '' ?>
      </span>
      <?php
          $atrasados = count(array_filter($chamados, fn($c) => $c['atrasado']));
          if ($atrasados > 0):
        ?>
      <span class="text-xs text-red-500 font-medium">
        ⚠ <?= $atrasados ?> chamado<?= $atrasados !== 1 ? 's' : '' ?> atrasado<?= $atrasados !== 1 ? 's' : '' ?>
      </span>
      <?php endif; ?>
    </div>

    <?php endif; ?>

  </div>

</main>

<!-- ── Modal de checkout ── -->
<div id="modal-checkout" class="hidden fixed inset-0 bg-black/40 z-50 flex items-center justify-center px-4">
  <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">

    <h2 class="text-lg font-semibold text-gray-800 mb-1">
      Finalizar chamado
    </h2>
    <p id="modal-titulo" class="text-sm text-gray-500 mb-5"></p>

    <form action="/w5i-helpdesk/public/?url=chamados/checkout" method="POST">
      <input type="hidden" id="checkout-id" name="id" value="">

      <div class="mb-5">
        <label for="solucao" class="block text-sm font-medium text-gray-700 mb-1.5">
          Solução aplicada <span class="text-red-400">*</span>
        </label>
        <textarea id="solucao" name="solucao" placeholder="Descreva o que foi feito para resolver o problema..."
          rows="4" required maxlength="2000" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm
                 text-gray-800 placeholder-gray-400 focus:outline-none
                 focus:ring-2 focus:ring-blue-500 focus:border-transparent
                 transition resize-none"></textarea>
      </div>

      <div class="flex gap-3 justify-end">
        <button type="button" onclick="fecharCheckout()" class="text-sm font-medium text-gray-500 hover:text-gray-700
                 px-5 py-2.5 rounded-lg border border-gray-200
                 hover:bg-gray-50 transition">
          Cancelar
        </button>
        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white text-sm
                 font-medium px-6 py-2.5 rounded-lg transition">
          Finalizar chamado
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

// Fecha modal clicando fora
document.getElementById('modal-checkout').addEventListener('click', function(e) {
  if (e.target === this) fecharCheckout();
});

// Fecha modal com ESC
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') fecharCheckout();
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>