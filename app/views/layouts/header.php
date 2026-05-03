<?php
// ── URL atual para destacar link ativo ──────────────────────
$urlAtual = $_GET['url'] ?? 'chamados';

// ── Mensagens vindas do redirect ────────────────────────────
$toastSucesso = $_GET['sucesso'] ?? null;
$toastErro    = $_GET['erro']    ?? null;
$toastAviso   = $_GET['aviso']   ?? null;

// ── Classe do link ativo/inativo ────────────────────────────
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
  <style>
  /* ── Menu mobile ── */
  #menu-mobile {
    display: none;
    flex-direction: column;
    gap: 4px;
    padding: 12px 16px 16px;
    background: rgba(255, 255, 255, 0.95);
    border-top: 1px solid #f3f4f6;
    animation: slideDown .2s ease;
  }

  #menu-mobile.aberto {
    display: flex;
  }

  #menu-mobile a {
    font-size: 14px;
    font-weight: 500;
    padding: 10px 14px;
    border-radius: 8px;
    text-decoration: none;
    transition: background .2s, color .2s;
  }

  #btn-hamburger {
    display: none;
    flex-direction: column;
    gap: 5px;
    background: none;
    border: none;
    cursor: pointer;
    padding: 6px;
    border-radius: 6px;
  }

  #btn-hamburger span {
    display: block;
    width: 22px;
    height: 2px;
    background: #6b7280;
    border-radius: 2px;
    transition: transform .3s, opacity .3s;
  }

  #btn-hamburger.aberto span:nth-child(1) {
    transform: translateY(7px) rotate(45deg);
  }

  #btn-hamburger.aberto span:nth-child(2) {
    opacity: 0;
  }

  #btn-hamburger.aberto span:nth-child(3) {
    transform: translateY(-7px) rotate(-45deg);
  }

  @keyframes slideDown {
    from {
      opacity: 0;
      transform: translateY(-6px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  /* ── Toast ── */
  #toast-container {
    position: fixed;
    top: 24px;
    right: 24px;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    gap: 10px;
    pointer-events: none;
  }

  .toast {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 16px;
    border-radius: 12px;
    box-shadow: 0 4px 24px rgba(0, 0, 0, .10);
    pointer-events: all;
    min-width: 280px;
    max-width: 380px;
    opacity: 0;
    transform: translateX(40px);
    transition: opacity .3s ease, transform .3s ease;
  }

  .toast.show {
    opacity: 1;
    transform: translateX(0);
  }

  .toast-icon {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 12px;
    font-weight: 700;
  }

  .toast-msg {
    flex: 1;
    font-size: 13px;
    font-weight: 500;
    line-height: 1.4;
    margin: 0;
  }

  .toast-close {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 18px;
    line-height: 1;
    padding: 0;
    opacity: .5;
    flex-shrink: 0;
    transition: opacity .2s;
  }

  .toast-close:hover {
    opacity: 1;
  }

  /* Variantes */
  .toast-sucesso {
    background: #f0fdf4;
    border: 1px solid #86efac;
    color: #166534;
  }

  .toast-sucesso .toast-icon {
    background: #dcfce7;
    color: #16a34a;
  }

  .toast-erro {
    background: #fef2f2;
    border: 1px solid #fca5a5;
    color: #991b1b;
  }

  .toast-erro .toast-icon {
    background: #fee2e2;
    color: #dc2626;
  }

  .toast-aviso {
    background: #fffbeb;
    border: 1px solid #fcd34d;
    color: #92400e;
  }

  .toast-aviso .toast-icon {
    background: #fef3c7;
    color: #d97706;
  }

  @media (max-width: 768px) {
    #nav-desktop {
      display: none;
    }

    #btn-hamburger {
      display: flex;
    }

    #toast-container {
      top: auto;
      bottom: 24px;
      right: 16px;
      left: 16px;
    }

    .toast {
      max-width: 100%;
    }
  }
  </style>
</head>

<body class="bg-gray-100 min-h-screen">

  <!-- ── Toast container ── -->
  <div id="toast-container"></div>

  <!-- ── Header ── -->
  <header class="bg-white/90 shadow-sm sticky top-0 z-50 backdrop-blur-sm">
    <div class="max-w-6xl mx-auto px-6 py-3 flex items-center justify-between">

      <!-- Logo -->
      <a href="/w5i-helpdesk/public/" class="flex items-center gap-2">
        <img src="/w5i-helpdesk/public/helpdesk_diurno_logo.png" alt="W5i Help Desk" class="h-22 w-28">
      </a>

      <!-- Nav desktop — intocada -->
      <nav id="nav-desktop" class="flex items-center gap-1">
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
        <a href="/w5i-helpdesk/public/?url=chamados/criar" class="ml-3 bg-blue-600 text-white text-sm font-medium
                  px-4 py-2 rounded-md hover:bg-blue-700 transition">
          + Novo chamado
        </a>
      </nav>

      <!-- Hambúrguer mobile -->
      <button id="btn-hamburger" onclick="toggleMenu()" aria-label="Menu">
        <span></span>
        <span></span>
        <span></span>
      </button>

    </div>

    <!-- Menu mobile -->
    <nav id="menu-mobile">
      <a href="/w5i-helpdesk/public/?url=chamados" class="<?= navClass('chamados', $urlAtual) ?>">
        Chamados
      </a>
      <a href="/w5i-helpdesk/public/?url=setores" class="<?= navClass('setores', $urlAtual) ?>">
        Setores
      </a>
      <a href="/w5i-helpdesk/public/?url=prioridades" class="<?= navClass('prioridades', $urlAtual) ?>">
        Prioridades
      </a>
      <a href="/w5i-helpdesk/public/?url=chamados/criar"
        class="bg-blue-600 text-white text-center rounded-lg mt-2 hover:bg-blue-700">
        + Novo chamado
      </a>
    </nav>
  </header>

  <!-- Conteúdo da página entra aqui -->

  <script>
  // ══════════════════════════════════════════════════════════
  // Toast system
  // ══════════════════════════════════════════════════════════
  const toastConfig = {
    sucesso: {
      icon: '✓',
      classe: 'toast-sucesso'
    },
    erro: {
      icon: '✕',
      classe: 'toast-erro'
    },
    aviso: {
      icon: '⚠',
      classe: 'toast-aviso'
    },
  };

  function showToast(mensagem, tipo) {
    const cfg = toastConfig[tipo] || toastConfig.sucesso;
    const container = document.getElementById('toast-container');

    const toast = document.createElement('div');
    toast.className = `toast ${cfg.classe}`;
    toast.innerHTML = `
        <div class="toast-icon">${cfg.icon}</div>
        <p class="toast-msg">${mensagem}</p>
        <button class="toast-close" onclick="fecharToast(this.parentElement)">×</button>
      `;

    container.appendChild(toast);

    // Anima entrada
    requestAnimationFrame(() => requestAnimationFrame(() => toast.classList.add('show')));

    // Some automaticamente em 4s
    setTimeout(() => fecharToast(toast), 4000);
  }

  function fecharToast(toast) {
    if (!toast) return;
    toast.classList.remove('show');
    setTimeout(() => toast?.remove(), 300);
  }

  // ══════════════════════════════════════════════════════════
  // Menu hambúrguer
  // ══════════════════════════════════════════════════════════
  function toggleMenu() {
    document.getElementById('menu-mobile').classList.toggle('aberto');
    document.getElementById('btn-hamburger').classList.toggle('aberto');
  }

  document.querySelectorAll('#menu-mobile a').forEach(link => {
    link.addEventListener('click', () => {
      document.getElementById('menu-mobile').classList.remove('aberto');
      document.getElementById('btn-hamburger').classList.remove('aberto');
    });
  });

  // ══════════════════════════════════════════════════════════
  // Dispara toasts vindos do PHP via URL
  // ══════════════════════════════════════════════════════════
  document.addEventListener('DOMContentLoaded', function() {
    <?php if ($toastSucesso): ?>
    showToast('<?= addslashes(htmlspecialchars($toastSucesso)) ?>', 'sucesso');
    <?php endif; ?>

    <?php if ($toastErro): ?>
    showToast('<?= addslashes(htmlspecialchars($toastErro)) ?>', 'erro');
    <?php endif; ?>

    <?php if ($toastAviso): ?>
    showToast('<?= addslashes(htmlspecialchars($toastAviso)) ?>', 'aviso');
    <?php endif; ?>
  });
  </script>