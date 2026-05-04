<?php
$urlAtual     = $_GET['url']    ?? 'chamados';
$toastSucesso = $_GET['sucesso'] ?? null;
$toastErro    = $_GET['erro']    ?? null;
$toastAviso   = $_GET['aviso']   ?? null;

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
    Tailwind CSS via CDN — adequado para desenvolvimento e prototipagem.
    Em produção: substituir por build via PostCSS ou Tailwind CLI.
  -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

  <!-- ── Toast container ── -->
  <div id="toast-container" class="fixed top-6 right-6 z-[9999] flex flex-col gap-2.5 pointer-events-none">
  </div>

  <!-- Toasts pré-renderizados com classes Tailwind estáticas — invisíveis até o JS animar -->
  <?php if ($toastSucesso): ?>
  <div id="toast-sucesso-tpl" class="hidden">
    <div class="flex items-center gap-3 px-4 py-3.5 rounded-xl border shadow-lg
                  pointer-events-auto min-w-[280px] max-w-[380px]
                  bg-green-50 border-green-200 text-green-800
                  opacity-0 translate-x-10 transition-all duration-300">
      <div class="w-7 h-7 rounded-full flex items-center justify-center
                    flex-shrink-0 text-xs font-bold bg-green-100 text-green-600">✓</div>
      <p class="flex-1 text-sm font-medium leading-snug">
        <?= htmlspecialchars($toastSucesso) ?>
      </p>
      <button class="opacity-40 hover:opacity-100 transition text-lg leading-none
                       bg-transparent border-none cursor-pointer text-green-800">×</button>
    </div>
  </div>
  <?php endif; ?>

  <?php if ($toastErro): ?>
  <div id="toast-erro-tpl" class="hidden">
    <div class="flex items-center gap-3 px-4 py-3.5 rounded-xl border shadow-lg
                  pointer-events-auto min-w-[280px] max-w-[380px]
                  bg-red-50 border-red-200 text-red-800
                  opacity-0 translate-x-10 transition-all duration-300">
      <div class="w-7 h-7 rounded-full flex items-center justify-center
                    flex-shrink-0 text-xs font-bold bg-red-100 text-red-600">✕</div>
      <p class="flex-1 text-sm font-medium leading-snug">
        <?= htmlspecialchars($toastErro) ?>
      </p>
      <button class="opacity-40 hover:opacity-100 transition text-lg leading-none
                       bg-transparent border-none cursor-pointer text-red-800">×</button>
    </div>
  </div>
  <?php endif; ?>

  <?php if ($toastAviso): ?>
  <div id="toast-aviso-tpl" class="hidden">
    <div class="flex items-center gap-3 px-4 py-3.5 rounded-xl border shadow-lg
                  pointer-events-auto min-w-[280px] max-w-[380px]
                  bg-yellow-50 border-yellow-200 text-yellow-800
                  opacity-0 translate-x-10 transition-all duration-300">
      <div class="w-7 h-7 rounded-full flex items-center justify-center
                    flex-shrink-0 text-xs font-bold bg-yellow-100 text-yellow-600">⚠</div>
      <p class="flex-1 text-sm font-medium leading-snug">
        <?= htmlspecialchars($toastAviso) ?>
      </p>
      <button class="opacity-40 hover:opacity-100 transition text-lg leading-none
                       bg-transparent border-none cursor-pointer text-yellow-800">×</button>
    </div>
  </div>
  <?php endif; ?>

  <!-- ── Header ── -->
  <header class="bg-white/90 backdrop-blur-sm shadow-sm sticky top-0 z-50">
    <div class="max-w-6xl mx-auto px-6 py-3 flex items-center justify-between">

      <!-- Logo -->
      <a href="/w5i-helpdesk/public/" class="flex items-center gap-2">
        <img src="/w5i-helpdesk/public/helpdesk_diurno_logo.png" alt="W5i Help Desk" class="h-22 w-28">
      </a>

      <!-- Nav desktop -->
      <nav id="nav-desktop" class="hidden md:flex items-center gap-1">
        <a href="/w5i-helpdesk/public/?url=chamados" class="px-3 py-2 rounded-md text-sm font-medium transition
                  <?= navClass('chamados', $urlAtual) ?>">
          Chamados
        </a>
        <a href="/w5i-helpdesk/public/?url=atendimento" class="px-3 py-2 rounded-md text-sm font-medium transition
                  <?= navClass('atendimento', $urlAtual) ?>">
          Atendimento
        </a>
        <a href="/w5i-helpdesk/public/?url=setores" class="px-3 py-2 rounded-md text-sm font-medium transition
                  <?= navClass('setores', $urlAtual) ?>">
          Setores
        </a>
        <a href="/w5i-helpdesk/public/?url=prioridades" class="px-3 py-2 rounded-md text-sm font-medium transition
                  <?= navClass('prioridades', $urlAtual) ?>">
          Prioridades
        </a>
        <a href="/w5i-helpdesk/public/?url=chamados/criar" class="ml-3 bg-blue-600 text-white text-sm font-medium
                  px-4 py-2 rounded-md hover:bg-blue-700 transition">
          + Novo chamado
        </a>
      </nav>

      <!-- Hambúrguer mobile -->
      <button id="btn-hamburger" onclick="toggleMenu()" aria-label="Menu" class="md:hidden flex flex-col gap-[5px] bg-transparent
                     border-none cursor-pointer p-1.5 rounded-md">
        <span class="block w-[22px] h-0.5 bg-gray-500 rounded-sm transition-all duration-300" id="bar1"></span>
        <span class="block w-[22px] h-0.5 bg-gray-500 rounded-sm transition-all duration-300" id="bar2"></span>
        <span class="block w-[22px] h-0.5 bg-gray-500 rounded-sm transition-all duration-300" id="bar3"></span>
      </button>

    </div>

    <!-- Menu mobile -->
    <nav id="menu-mobile" class="hidden md:hidden flex-col gap-1 px-4 pb-4 pt-3
                bg-white/95 border-t border-gray-100">
      <a href="/w5i-helpdesk/public/?url=chamados" class="px-3 py-2.5 rounded-lg text-sm font-medium transition
                <?= navClass('chamados', $urlAtual) ?>">
        Chamados
      </a>
      <a href="/w5i-helpdesk/public/?url=atendimento" class="px-3 py-2.5 rounded-lg text-sm font-medium transition
                <?= navClass('atendimento', $urlAtual) ?>">
        Atendimento
      </a>
      <a href="/w5i-helpdesk/public/?url=setores" class="px-3 py-2.5 rounded-lg text-sm font-medium transition
                <?= navClass('setores', $urlAtual) ?>">
        Setores
      </a>
      <a href="/w5i-helpdesk/public/?url=prioridades" class="px-3 py-2.5 rounded-lg text-sm font-medium transition
                <?= navClass('prioridades', $urlAtual) ?>">
        Prioridades
      </a>
      <a href="/w5i-helpdesk/public/?url=chamados/criar" class="mt-1 bg-blue-600 text-white text-sm font-medium text-center
                px-4 py-2.5 rounded-lg hover:bg-blue-700 transition">
        + Novo chamado
      </a>
    </nav>
  </header>

  <!-- Conteúdo da página entra aqui -->

  <script>
  // ── Toast ────────────────────────────────────────────────
  function ativarToast(tplId) {
    const tpl = document.getElementById(tplId);
    if (!tpl) return;

    const toast = tpl.querySelector('div');
    const container = document.getElementById('toast-container');

    container.appendChild(toast);
    tpl.remove();

    // Botão fechar
    toast.querySelector('button').addEventListener('click', () => fecharToast(toast));

    // Anima entrada
    requestAnimationFrame(() => requestAnimationFrame(() => {
      toast.classList.remove('opacity-0', 'translate-x-10');
    }));

    // Some após 4s
    setTimeout(() => fecharToast(toast), 4000);
  }

  function fecharToast(toast) {
    if (!toast) return;
    toast.classList.add('opacity-0', 'translate-x-10');
    setTimeout(() => toast?.remove(), 300);
  }

  // ── Menu hambúrguer ──────────────────────────────────────
  function toggleMenu() {
    const menu = document.getElementById('menu-mobile');
    const isOpen = menu.classList.contains('flex');

    menu.classList.toggle('hidden', isOpen);
    menu.classList.toggle('flex', !isOpen);

    document.getElementById('bar1').style.transform = isOpen ? '' : 'translateY(7px) rotate(45deg)';
    document.getElementById('bar2').style.opacity = isOpen ? '1' : '0';
    document.getElementById('bar3').style.transform = isOpen ? '' : 'translateY(-7px) rotate(-45deg)';
  }

  document.querySelectorAll('#menu-mobile a').forEach(link => {
    link.addEventListener('click', () => {
      document.getElementById('menu-mobile').classList.add('hidden');
      document.getElementById('menu-mobile').classList.remove('flex');
    });
  });

  // ── Dispara toasts e limpa a URL ─────────────────────────
  document.addEventListener('DOMContentLoaded', function() {
    <?php if ($toastSucesso): ?>
    ativarToast('toast-sucesso-tpl');
    <?php endif; ?>
    <?php if ($toastErro): ?>
    ativarToast('toast-erro-tpl');
    <?php endif; ?>
    <?php if ($toastAviso): ?>
    ativarToast('toast-aviso-tpl');
    <?php endif; ?>

    <?php if ($toastSucesso || $toastErro || $toastAviso): ?>
    const url = new URL(window.location.href);
    url.searchParams.delete('sucesso');
    url.searchParams.delete('erro');
    url.searchParams.delete('aviso');
    window.history.replaceState({}, '', url.toString());
    <?php endif; ?>
  });
  </script>