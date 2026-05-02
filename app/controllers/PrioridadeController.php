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
        $prioridades = $this->model->getAll();
        $erro        = $_GET['erro']    ?? null;
        $sucesso     = $_GET['sucesso'] ?? null;
        require_once __DIR__ . '/../views/prioridades/index.php';
    }

    public function store(): void
    {
        $nome  = trim($_POST['nome']  ?? '');
        $horas = (int)($_POST['horas'] ?? 0);

        if (empty($nome) || $horas <= 0) {
            header('Location: ?url=prioridades&erro=Preencha todos os campos.');
            exit();
        }

        $this->model->create($nome, $horas);
        header('Location: ?url=prioridades&sucesso=Prioridade criada.');
        exit();
    }
}