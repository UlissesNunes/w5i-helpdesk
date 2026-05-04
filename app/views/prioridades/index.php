<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../../helpers/prioridade.php'; ?>

<?php
/** @var array $prioridades Prioridades vindas do PrioridadeController */

$niveis = [
    'critico' => [
        'cor'      => 'border-red-700',
        'corDot'   => 'bg-red-800',
        'corTexto' => 'text-red-700',
        'corBg'    => 'bg-red-50',
        'prazo'    => '1h até 3h',
        'desc'     => 'Sistema fora do ar, perda de dados ou impacto crítico no negócio.',
    ],
    'alta' => [
        'cor'      => 'border-orange-400',
        'corDot'   => 'bg-orange-400',
        'corTexto' => 'text-orange-600',
        'corBg'    => 'bg-orange-50',
        'prazo'    => '4h até 7h',
        'desc'     => 'Funcionalidade importante comprometida, afeta vários usuários.',
    ],
    'medio' => [
        'cor'      => 'border-yellow-400',
        'corDot'   => 'bg-yellow-400',
        'corTexto' => 'text-yellow-600',
        'corBg'    => 'bg-yellow-50',
        'prazo'    => '8h até 20h',
        'desc'     => 'Problema com solução de contorno disponível, impacto parcial.',
    ],
    'baixo' => [
        'cor'      => 'border-green-400',
        'corDot'   => 'bg-green-500',
        'corTexto' => 'text-green-600',
        'corBg'    => 'bg-green-50',
        'prazo'    => 'A partir de 24h',
        'desc'     => 'Dúvidas, melhorias ou problemas sem impacto operacional.',
    ],
];
?>

<main class="max-w-4xl mx-auto px-4 py-10">

  <!-- ── Cabeçalho ── -->
  <div class="mb-8">
    <h1 class="text-2xl font-semibold text-gray-800">Prioridades</h1>
    <p class="text-sm text-gray-500 mt-1">
      Níveis de urgência e seus prazos de atendimento
    </p>
  </div>

  <!-- ── Cards dos níveis ── -->
  <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-10">
    <?php foreach ($niveis as $nivel => $info): ?>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm
                  border-l-4 <?= $info['cor'] ?> p-5">

      <div class="flex items-center gap-3 mb-3">
        <span class="w-3 h-3 rounded-full <?= $info['corDot'] ?>"></span>
        <span class="text-sm font-semibold text-gray-800">
          <?= labelPorNivel($nivel) ?>
        </span>
      </div>

      <div class="<?= $info['corBg'] ?> rounded-xl px-4 py-2.5 mb-3">
        <p class="text-xs font-semibold <?= $info['corTexto'] ?>">
          Prazo de atendimento
        </p>
        <p class="text-lg font-bold <?= $info['corTexto'] ?>">
          <?= $info['prazo'] ?>
        </p>
      </div>

      <p class="text-xs text-gray-500 leading-relaxed">
        <?= $info['desc'] ?>
      </p>

    </div>
    <?php endforeach; ?>
  </div>

  <!-- ── Tabela cadastradas ── -->
  <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

    <div class="px-6 py-4 border-b border-gray-100">
      <h2 class="text-sm font-semibold text-gray-700">Prioridades cadastradas</h2>
    </div>

    <?php if (empty($prioridades)): ?>
    <div class="flex flex-col items-center justify-center py-12">
      <p class="text-sm text-gray-400">Nenhuma prioridade cadastrada</p>
    </div>

    <?php else: ?>
    <table class="w-full text-sm">
      <thead>
        <tr class="border-b border-gray-100">
          <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider w-12">
            Nível
          </th>
          <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
            Nome
          </th>
          <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
            Prazo
          </th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-50">
        <?php foreach ($prioridades as $p): ?>
        <tr class="hover:bg-blue-50/20 transition">
          <td class="px-6 py-4">
            <span class="w-2.5 h-2.5 rounded-full inline-block <?= corPorNivel($p['nivel']) ?>"></span>
          </td>
          <td class="px-6 py-4 font-medium text-gray-800">
            <?= htmlspecialchars($p['nome']) ?>
          </td>
          <td class="px-6 py-4 text-xs text-gray-500">
            <?= isset($niveis[$p['nivel']]) ? $niveis[$p['nivel']]['prazo'] : '—' ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>

  </div>

</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>