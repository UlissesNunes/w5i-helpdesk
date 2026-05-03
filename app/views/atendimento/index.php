<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<main class="max-w-4xl mx-auto px-4 py-10">

  <!-- ── Cabeçalho ── -->
  <div class="flex items-center justify-between mb-8">
    <div>
      <h1 class="text-2xl font-semibold text-gray-800">Atendimento</h1>
      <p class="text-sm text-gray-500 mt-1">Chamados aguardando ação</p>
    </div>
    <?php
    $chamados = $chamados ?? [];
      $totalAtivos    = count($chamados);
      $totalAtrasados = count(array_filter($chamados, fn($c) => $c['atrasado']));
    ?>
    <div class="flex items-center gap-2">
      <?php if ($totalAtrasados > 0): ?>
      <span class="bg-red-50 text-red-600 text-xs font-semibold px-3 py-1 rounded-full">
        ⚠ <?= $totalAtrasados ?> atrasado<?= $totalAtrasados !== 1 ? 's' : '' ?>
      </span>
      <?php endif; ?>
      <span class="bg-blue-50 text-blue-600 text-xs font-semibold px-3 py-1 rounded-full">
        <?= $totalAtivos ?> ativo<?= $totalAtivos !== 1 ? 's' : '' ?>
      </span>
    </div>
  </div>

  <?php
    $chamados = $chamados ?? [];
    $abertos       = array_filter($chamados, fn($c) => $c['status'] === 'Aberto');
    $emAtendimento = array_filter($chamados, fn($c) => $c['status'] === 'Em atendimento');
  ?>

  <!-- ── Em atendimento ── -->
  <section class="mb-10">

    <div class="flex items-center gap-2 mb-4">
      <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
        Em atendimento
      </h2>
      <span class="bg-yellow-50 text-yellow-600 text-xs font-semibold px-2 py-0.5 rounded-full">
        <?= count($emAtendimento) ?>
      </span>
    </div>

    <?php if (empty($emAtendimento)): ?>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm
                  flex items-center justify-center py-10">
      <p class="text-sm text-gray-400">Nenhum chamado em atendimento no momento</p>
    </div>

    <?php else: ?>
    <div class="flex flex-col gap-4">
      <?php foreach ($emAtendimento as $c): ?>
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5
                      <?= $c['atrasado']
                        ? 'border-l-4 border-l-red-400 bg-red-50/40'
                        : 'border-l-4 border-l-yellow-400' ?>">

        <div class="flex items-start justify-between gap-4">
          <div class="flex-1 min-w-0">

            <div class="flex items-center gap-2 mb-2">
              <span class="text-xs text-gray-300 font-mono">#<?= $c['id'] ?></span>
              <?php if ($c['atrasado']): ?>
              <span class="bg-red-50 text-red-600 text-xs font-semibold
                                 px-2 py-0.5 rounded-lg">
                ⚠ Atrasado
              </span>
              <?php endif; ?>
            </div>

            <h3 class="font-semibold text-gray-800 truncate mb-2">
              <?= htmlspecialchars($c['titulo']) ?>
            </h3>

            <?php if (!empty($c['descricao'])): ?>
            <p class="text-sm text-gray-400 mb-3 line-clamp-1">
              <?= htmlspecialchars($c['descricao']) ?>
            </p>
            <?php endif; ?>

            <div class="flex flex-wrap gap-3">
              <span class="text-xs text-gray-400">📁 <?= htmlspecialchars($c['setor']) ?></span>
              <span class="text-xs text-gray-400">⚡ <?= htmlspecialchars($c['prioridade']) ?> — SLA
                <?= $c['tempo_estimado_horas'] ?>h</span>
              <span class="text-xs text-gray-400">🕐 <?= date('d/m H:i', strtotime($c['checkin_at'])) ?></span>
              <span class="text-xs font-semibold <?= $c['atrasado'] ? 'text-red-600' : 'text-gray-600' ?>">
                ⏱ <?= $c['tempo_exibir'] ?>
              </span>
            </div>

          </div>

          <button onclick="abrirCheckout(<?= $c['id'] ?>, '<?= htmlspecialchars($c['titulo'], ENT_QUOTES) ?>')" class="bg-green-600 hover:bg-green-700 text-white text-xs font-semibold
                       px-4 py-2 rounded-xl transition flex-shrink-0">
            Finalizar
          </button>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

  </section>

  <!-- ── Aguardando ── -->
  <section>

    <div class="flex items-center gap-2 mb-4">
      <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
        Aguardando atendimento
      </h2>
      <span class="bg-blue-50 text-blue-600 text-xs font-semibold px-2 py-0.5 rounded-full">
        <?= count($abertos) ?>
      </span>
    </div>

    <?php if (empty($abertos)): ?>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm
                  flex items-center justify-center py-10">
      <p class="text-sm text-gray-400">Nenhum chamado aguardando atendimento</p>
    </div>

    <?php else: ?>
    <div class="flex flex-col gap-3">
      <?php foreach ($abertos as $c): ?>
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100
                      border-l-4 border-l-blue-400 p-5">

        <div class="flex items-center justify-between gap-4">
          <div class="flex-1 min-w-0">

            <div class="flex items-center gap-2 mb-1">
              <span class="text-xs text-gray-300 font-mono">#<?= $c['id'] ?></span>
              <span class="bg-blue-50 text-blue-600 text-xs font-semibold
                               px-2 py-0.5 rounded-lg">
                Aberto
              </span>
            </div>

            <h3 class="font-medium text-gray-800 truncate mb-2">
              <?= htmlspecialchars($c['titulo']) ?>
            </h3>

            <div class="flex flex-wrap gap-3">
              <span class="text-xs text-gray-400">📁 <?= htmlspecialchars($c['setor']) ?></span>
              <span class="text-xs text-gray-400">⚡ <?= htmlspecialchars($c['prioridade']) ?> — SLA
                <?= $c['tempo_estimado_horas'] ?>h</span>
              <span class="text-xs text-gray-400">📅 <?= date('d/m/Y H:i', strtotime($c['criado_em'])) ?></span>
            </div>

          </div>

          <form action="/w5i-helpdesk/public/?url=chamados/checkin" method="POST"
            onsubmit="return confirm('Iniciar atendimento do chamado #<?= $c['id'] ?>?')">
            <input type="hidden" name="id" value="<?= $c['id'] ?>">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold
                         px-4 py-2 rounded-xl transition flex-shrink-0">
              Iniciar
            </button>
          </form>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

  </section>

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