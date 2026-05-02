<?php

declare(strict_types=1);

require_once __DIR__ . '/../models/Setor.php';

class SetorController
{
    private Setor $model;

    public function __construct(PDO $pdo)
    {
        $this->model = new Setor($pdo);
    }

    // ── GET ?url=setores ─────────────────────────────────────
    public function index(): void
    {
        $setores = $this->model->getAll();
        $erro    = $_GET['erro'] ?? null;
        $sucesso = $_GET['sucesso'] ?? null;

        require_once __DIR__ . '/../views/setores/index.php';
    }

    // ── POST ?url=setores/salvar ─────────────────────────────
    public function store(): void
    {
        $this->garantirMetodo('POST');

        $nome = $this->limpar($_POST['nome'] ?? '');

        // ── Validações ───────────────────────────────────────
        $erros = $this->validarNome($nome);

        if ($this->model->nomeExiste($nome)) {
            $erros[] = 'Já existe um setor com esse nome.';
        }

        if (!empty($erros)) {
            $this->redirecionar('setores', ['erro' => implode(' ', $erros)]);
        }

        // ── Salvar ───────────────────────────────────────────
        $this->model->create($nome);
        $this->redirecionar('setores', ['sucesso' => 'Setor criado com sucesso.']);
    }

    // ── POST ?url=setores/deletar ────────────────────────────
    public function destroy(): void
    {
        $this->garantirMetodo('POST');

        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            $this->redirecionar('setores', ['erro' => 'ID inválido.']);
        }

        $setor = $this->model->findById($id);

        if (!$setor) {
            $this->redirecionar('setores', ['erro' => 'Setor não encontrado.']);
        }

        try {
            $this->model->delete($id);
            $this->redirecionar('setores', ['sucesso' => 'Setor removido com sucesso.']);
        } catch (PDOException $e) {
            // FK violation — setor tem chamados vinculados
            $this->redirecionar('setores', ['erro' => 'Não é possível remover um setor com chamados vinculados.']);
        }
    }

    // ── Helpers privados ─────────────────────────────────────

    private function limpar(string $valor): string
    {
        return trim(strip_tags($valor));
    }

    private function validarNome(string $nome): array
    {
        $erros = [];

        if (empty($nome)) {
            $erros[] = 'O nome é obrigatório.';
        }

        if (strlen($nome) < 2) {
            $erros[] = 'O nome deve ter pelo menos 2 caracteres.';
        }

        if (strlen($nome) > 255) {
            $erros[] = 'O nome deve ter no máximo 255 caracteres.';
        }

        return $erros;
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
        header("Location: ?url={$url}{$query}");
        exit();
    }
}