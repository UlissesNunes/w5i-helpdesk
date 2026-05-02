<?php
// Pega a URL atual para destacar o link ativo
$urlAtual = $_GET['url'] ?? 'chamados';

// Retorna a classe CSS correta — ativo ou inativo
function navClass(string $pagina, string $urlAtual): string {
    $ativo   = 'text-blue-600 bg-blue-50';
    $inativo = 'text-gray-600 hover:text-blue-600 hover:bg-blue-50';
    return str_starts_with($urlAtual, $pagina) ? $ativo : $inativo;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>W5i Help Desk</title>
  <!-- 
  Tailwind CSS via CDN (Play CDN)
  Adequado para desenvolvimento e prototipagem.
  Em produção: substituir por build via PostCSS ou Tailwind CLI.
-->
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

  <header class="bg-white/90 shadow-sm sticky top-0 z-50">
    <div class="max-w-6xl mx-auto px-6 py-3 flex items-center justify-between">

      <!-- Logo -->
      <a href="/w5i-helpdesk/public/" class="flex items-center gap-2">
        <img src="/w5i-helpdesk/public/helpdesk_diurno_logo.png" alt="Logo Help Desk Diurno" class="h-22 w-28">
      </a>

      <!-- Navegação com link ativo -->
      <nav class="flex items-center gap-1">

        <a href="/w5i-helpdesk/public/?url=chamados" class="px-3 py-2 rounded-md text-sm font-medium transition
                  <?= navClass('chamados', $urlAtual) ?>">
          Chamados
        </a>

        <a href="/w5i-helpdesk/public/?url=setores" class="px-3 py-2 rounded-md text-sm font-medium transition
                  <?= navClass('setores', $urlAtual) ?>">
          Setores
        </a>

        <a href="/w5i-helpdesk/public/?url=prioridades" class="px-3 py-2 rounded-md text-sm font-medium transition
                  <?= navClass('prioridades', $urlAtual) ?>">
          Prioridades
        </a>

        <!-- Botão de destaque -->
        <a href="/w5i-helpdesk/public/?url=chamados/criar" class="ml-3 bg-blue-600 text-white text-sm font-medium
                  px-4 py-2 rounded-md hover:bg-blue-700 transition">
          + Novo chamado
        </a>


      </nav>
    </div>
  </header>

  <!-- O conteúdo de cada página entra aqui embaixo -->