<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../../helpers/prioridade.php'; ?>

<?php
/** @var array $setores     Setores vindos do ChamadoController */
/** @var array $prioridades Prioridades vindas do ChamadoController */
?>

<main class="max-w-2xl mx-auto px-4 py-10">

  <!-- ── Breadcrumb ── -->
  <div class="flex items-center gap-2 text-sm text-gray-400 mb-6">
    <a href="/w5i-helpdesk/public/?url=chamados" class="hover:text-blue-600 transition">Chamados</a>
    <span class="text-gray-300">/</span>
    <span class="text-gray-600 font-medium">Novo chamado</span>
  </div>

  <!-- ── Cabeçalho ── -->
  <div class="mb-8">
    <h1 class="text-2xl font-semibold text-gray-800">Novo chamado</h1>
    <p class="text-sm text-gray-500 mt-1">
      Preencha os dados para abrir um chamado de suporte
    </p>
  </div>

  <!-- ── Formulário ── -->
  <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
    <form action="/w5i-helpdesk/public/?url=chamados/salvar" method="POST">

      <!-- Título -->
      <div class="mb-5">
        <label for="titulo" class="block text-sm font-semibold text-gray-700 mb-1.5">
          Título <span class="text-red-400">*</span>
        </label>
        <input type="text" id="titulo" name="titulo" placeholder="Descreva o problema brevemente" required
          maxlength="255" value="<?= htmlspecialchars($_POST['titulo'] ?? '') ?>" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm
                 text-gray-800 placeholder-gray-400 focus:outline-none
                 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
      </div>

      <!-- Descrição -->
      <div class="mb-5">
        <label for="descricao" class="block text-sm font-semibold text-gray-700 mb-1.5">
          Descrição
          <span class="text-gray-400 font-normal text-xs">(opcional)</span>
        </label>
        <textarea id="descricao" name="descricao" placeholder="Descreva o problema com mais detalhes..." rows="4"
          maxlength="2000" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm
                 text-gray-800 placeholder-gray-400 focus:outline-none
                 focus:ring-2 focus:ring-blue-500 focus:border-transparent
                 transition resize-none"><?= htmlspecialchars($_POST['descricao'] ?? '') ?></textarea>
      </div>

      <!-- Setor e Urgência -->
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">

        <!-- Setor -->
        <div>
          <label for="setor_id" class="block text-sm font-semibold text-gray-700 mb-1.5">
            Setor <span class="text-red-400">*</span>
          </label>

          <?php if (empty($setores)): ?>
          <div class="border border-orange-200 bg-orange-50 rounded-xl
                        px-4 py-2.5 text-sm text-orange-600">
            Nenhum setor —
            <a href="/w5i-helpdesk/public/?url=setores" class="underline hover:text-orange-800">cadastrar</a>
          </div>
          <?php else: ?>
          <select id="setor_id" name="setor_id" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm
                     text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500
                     focus:border-transparent transition bg-white">
            <option value="">Selecione o setor</option>
            <?php foreach ($setores as $setor): ?>
            <option value="<?= $setor['id'] ?>" <?= (($_POST['setor_id'] ?? '') == $setor['id']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($setor['nome']) ?>
            </option>
            <?php endforeach; ?>
          </select>
          <?php endif; ?>
        </div>

        <!-- Urgência -->
        <div>
          <label for="prioridade_id" class="block text-sm font-semibold text-gray-700 mb-1.5">
            Urgência <span class="text-red-400">*</span>
          </label>

          <?php if (empty($prioridades)): ?>
          <div class="border border-orange-200 bg-orange-50 rounded-xl
                        px-4 py-2.5 text-sm text-orange-600">
            Nenhuma prioridade —
            <a href="/w5i-helpdesk/public/?url=prioridades" class="underline hover:text-orange-800">ver prioridades</a>
          </div>
          <?php else: ?>
          <select id="prioridade_id" name="prioridade_id" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm
                     text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500
                     focus:border-transparent transition bg-white">
            <option value="">Selecione a urgência</option>
            <?php foreach ($prioridades as $prioridade): ?>
            <option value="<?= $prioridade['id'] ?>"
              <?= (($_POST['prioridade_id'] ?? '') == $prioridade['id']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($prioridade['nome']) ?>
              — <?= labelPorNivel($prioridade['nivel']) ?>
            </option>
            <?php endforeach; ?>
          </select>
          <p class="text-xs text-gray-400 mt-1.5">
            Escolha com base no impacto do problema
          </p>
          <?php endif; ?>
        </div>

      </div>

      <!-- Ações -->
      <div class="flex items-center justify-between pt-5 border-t border-gray-100">
        <a href="/w5i-helpdesk/public/?url=chamados" class="text-sm text-gray-400 hover:text-gray-600 transition">
          ← Voltar
        </a>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold
                 px-8 py-2.5 rounded-xl transition">
          Abrir chamado
        </button>
      </div>

    </form>
  </div>

  <p class="text-xs text-gray-400 text-center mt-5">
    O chamado será criado com status
    <span class="bg-blue-50 text-blue-600 text-xs font-semibold px-2 py-0.5 rounded-md">
      Aberto
    </span>
    e ficará disponível para atendimento.
  </p>

</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>