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

    public function index(): void
    {
        $this->renderizar('prioridades/index', [
            'prioridades' => $this->model->getAll(),
        ]);
    }

    private function renderizar(string $view, array $dados = []): void
    {
        extract($dados);
        require_once __DIR__ . "/../views/{$view}.php";
    }
}