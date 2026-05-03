<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<main class="max-w-4xl mx-auto px-4 py-10">

  <!-- ── Cabeçalho ── -->
  <div class="flex items-center justify-between mb-8">
    <div>
      <h1 class="text-2xl font-semibold text-gray-800">Prioridades</h1>
      <p class="text-sm text-gray-500 mt-1">Defina os níveis de urgência e seus prazos</p>
    </div>

  </div>

  <!-- ── Formulário ── -->
  <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
    <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">
      Nova prioridade
    </h2>
    <form action="/w5i-helpdesk/public/?url=prioridades/salvar" method="POST">
      <div class="flex gap-3">
        <input type="text" name="nome" placeholder="Nome da prioridade" required maxlength="255" class="flex-1 border border-gray-200 rounded-xl px-4 py-2.5 text-sm
                 text-gray-800 placeholder-gray-400 focus:outline-none
                 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
        <div class="relative">
          <input type="number" name="horas" placeholder="Horas" required min="1" max="720" class="w-28 border border-gray-200 rounded-xl px-4 py-2.5 text-sm
                   text-gray-800 placeholder-gray-400 focus:outline-none
                   focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
          <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400
                       pointer-events-none">h</span>
        </div>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium
                 px-6 py-2.5 rounded-xl transition whitespace-nowrap">
          Salvar
        </button>
      </div>
      <p class="text-xs text-gray-400 mt-2.5">
        O tempo estimado define o prazo máximo para resolução do chamado
      </p>
    </form>
  </div>

  <!-- ── Listagem ── -->
  <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

    <?php if (empty($prioridades)): ?>

    <div class="flex flex-col items-center justify-center py-16">
      <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center mb-3">
        <span class="text-xl">⚡</span>
      </div>
      <p class="text-sm font-medium text-gray-500">Nenhuma prioridade cadastrada</p>
      <p class="text-xs text-gray-400 mt-1">Adicione a primeira prioridade acima</p>
    </div>

    <?php else: ?>

    <table class="w-full text-sm">
      <thead>
        <tr class="border-b border-gray-100">
          <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider w-12">#</th>
          <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Nome</th>
          <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Tempo</th>
          <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Nível</th>
          <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider w-24">Ação</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-50">
        <?php foreach ($prioridades as $p): ?>

        <?php
              [$bgBadge, $textBadge, $label] = match(true) {
                $p['tempo_estimado_horas'] <= 2  => ['bg-red-50',    'text-red-600',    'Crítica'],
                $p['tempo_estimado_horas'] <= 8  => ['bg-orange-50', 'text-orange-600', 'Alta'   ],
                $p['tempo_estimado_horas'] <= 24 => ['bg-yellow-50', 'text-yellow-600', 'Média'  ],
                default                          => ['bg-green-50',  'text-green-600',  'Baixa'  ],
              };
            ?>

        <tr class="hover:bg-blue-50/30 transition">

          <td class="px-6 py-4 text-gray-300 font-mono text-xs">
            <?= $p['id'] ?>
          </td>

          <td class="px-6 py-4 font-medium text-gray-800">
            <?= htmlspecialchars($p['nome']) ?>
          </td>

          <td class="px-6 py-4">
            <span class="font-semibold text-gray-700"><?= $p['tempo_estimado_horas'] ?>h</span>
            <span class="text-gray-400 text-xs ml-1">
              (<?= $p['tempo_estimado_horas'] * 60 ?>min)
            </span>
          </td>

          <td class="px-6 py-4">
            <span class="inline-flex items-center px-2.5 py-1 rounded-lg
                             text-xs font-semibold <?= $bgBadge ?> <?= $textBadge ?>">
              <?= $label ?>
            </span>
          </td>

          <td class="px-6 py-4 text-right">
            <form action="/w5i-helpdesk/public/?url=prioridades/deletar" method="POST"
              onsubmit="return confirm('Remover a prioridade \'<?= htmlspecialchars($p['nome'], ENT_QUOTES) ?>\'?')">
              <input type="hidden" name="id" value="<?= $p['id'] ?>">
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
        <?= count($prioridades) ?> prioridade<?= count($prioridades) !== 1 ? 's' : '' ?>
        cadastrada<?= count($prioridades) !== 1 ? 's' : '' ?>
      </p>
    </div>

    <?php endif; ?>

  </div>

</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>