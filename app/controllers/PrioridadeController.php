<?php

declare(strict_types=1);

require_once __DIR__ . '/../models/Prioridade.php';

class PrioridadeController
{
    private Prioridade $model;

    public function __construct(PDO $pdo)
    {
        $this->model = new Prioridade($pdo);
    }

    // ── GET ?url=prioridades ─────────────────────────────────
    public function index(): void
    {
        $prioridades = $this->model->getAll();
        $erro        = $_GET['erro']    ?? null;
        $sucesso     = $_GET['sucesso'] ?? null;

        require_once __DIR__ . '/../views/prioridades/index.php';
    }

    // ── POST ?url=prioridades/salvar ─────────────────────────
    public function store(): void
    {
        $this->garantirMetodo('POST');

        $nome  = trim($_POST['nome']  ?? '');
        $horas = (int) ($_POST['horas'] ?? 0);

        if (empty($nome) || $horas <= 0) {
            $this->redirecionar('prioridades', ['erro' => 'Preencha todos os campos corretamente.']);
        }

        $this->model->create($nome, $horas);
        $this->redirecionar('prioridades', ['sucesso' => 'Prioridade criada com sucesso.']);
    }

    // ── POST ?url=prioridades/deletar ────────────────────────
    public function destroy(): void
    {
        $this->garantirMetodo('POST');

        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            $this->redirecionar('prioridades', ['erro' => 'ID inválido.']);
        }

        try {
            $this->model->delete($id);
            $this->redirecionar('prioridades', ['sucesso' => 'Prioridade removida com sucesso.']);
        } catch (PDOException $e) {
            $this->redirecionar('prioridades', ['erro' => 'Não é possível remover uma prioridade com chamados vinculados.']);
        }
    }

    // ── Helpers privados ─────────────────────────────────────

    private function garantirMetodo(string $metodo): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== strtoupper($metodo)) {
            http_response_code(405);
            die('Método não permitido.');
        }
    }

    private function redirecionar(string $url, array $params = []): void
    {
        $query = !empty($params) ? '&' . http_build_query($params) : '';
        header("Location: /w5i-helpdesk/public/?url={$url}{$query}");
        exit();
    }
}