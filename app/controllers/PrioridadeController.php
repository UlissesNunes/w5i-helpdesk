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
        $this->renderizar('prioridades/index', [
            'prioridades' => $this->model->getAll(),
            'erro'        => $_GET['erro']    ?? null,
            'sucesso'     => $_GET['sucesso'] ?? null,
        ]);
    }

    // ── POST ?url=prioridades/salvar ─────────────────────────
    public function store(): void
    {
        $this->garantirMetodo('POST');

        $nome  = $this->limpar($_POST['nome']  ?? '');
        $horas = (int) ($_POST['horas']        ?? 0);

        if (empty($nome) || $horas <= 0) {
            $this->redirecionar('prioridades', [
                'erro' => 'Preencha todos os campos corretamente.',
            ]);
        }

        $this->model->create($nome, $horas);
        $this->redirecionar('prioridades', [
            'sucesso' => 'Prioridade criada com sucesso.',
        ]);
    }

    // ── POST ?url=prioridades/deletar ────────────────────────
    public function destroy(): void
    {
        $this->garantirMetodo('POST');

        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            $this->redirecionar('prioridades', ['erro' => 'ID inválido.']);
        }

        if (!$this->model->findById($id)) {
            $this->redirecionar('prioridades', ['erro' => 'Prioridade não encontrada.']);
        }

        try {
            $this->model->delete($id);
            $this->redirecionar('prioridades', [
                'sucesso' => 'Prioridade removida com sucesso.',
            ]);
        } catch (PDOException) {
            $this->redirecionar('prioridades', [
                'erro' => 'Não é possível remover uma prioridade com chamados vinculados.',
            ]);
        }
    }

    // ── Helpers privados ─────────────────────────────────────

    private function renderizar(string $view, array $dados = []): void
    {
        extract($dados);
        require_once __DIR__ . "/../views/{$view}.php";
    }

    private function limpar(string $valor): string
    {
        return trim(strip_tags($valor));
    }

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