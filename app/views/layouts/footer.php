<?php // footer.php ?>

<!-- ── Modal de confirmação global ── -->
<div id="modal-confirmar" class="hidden fixed inset-0 bg-black/40 z-50 flex items-center justify-center px-4">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6">

    <h2 class="text-base font-semibold text-gray-800 mb-2">Confirmar ação</h2>
    <p id="modal-confirmar-texto" class="text-sm text-gray-500 mb-6"></p>

    <div class="flex gap-3">
      <button type="button" onclick="fecharConfirmar()" class="flex-1 text-sm font-medium text-gray-500 hover:text-gray-700
                 py-2.5 rounded-xl border border-gray-200 hover:bg-gray-50 transition">
        Cancelar
      </button>
      <button id="modal-confirmar-btn" type="button" class="flex-1 bg-blue-600 hover:bg-blue-800 text-white text-sm
                 font-semibold py-2.5 rounded-xl transition">
        Confirmar
      </button>
    </div>

  </div>
</div>

<footer class="mt-16 py-6" style="border-top: 1px solid #f3f4f6;">
  <p class="text-center text-xs text-gray-400">
    W5i Help Desk &copy; <?= date('Y') ?>
  </p>
</footer>

<script>
// ── Modal de confirmação global ──────────────────────────
let _formConfirmar = null;

function confirmar(form, mensagem) {
  _formConfirmar = form;
  document.getElementById('modal-confirmar-texto').textContent = mensagem;
  document.getElementById('modal-confirmar').classList.remove('hidden');
}

function fecharConfirmar() {
  _formConfirmar = null;
  document.getElementById('modal-confirmar').classList.add('hidden');
}

document.getElementById('modal-confirmar-btn').addEventListener('click', function() {
  if (_formConfirmar) {
    _formConfirmar.submit();
  }
});

document.getElementById('modal-confirmar').addEventListener('click', function(e) {
  if (e.target === this) fecharConfirmar();
});

document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') fecharConfirmar();
});
</script>

</body>

</html>