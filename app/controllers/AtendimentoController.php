<?php

declare(strict_types=1);

require_once __DIR__ . '/../models/Chamado.php';

class AtendimentoController
{
    private Chamado $model;

    public function __construct(PDO $pdo)
    {
        $this->model = new Chamado($pdo);
    }

    public function checkin(): void
    {
        $id = (int)($_POST['id'] ?? 0);

        if (!$this->model->checkin($id)) {
            header('Location: ?url=chamados&erro=Não foi possível iniciar.');
            exit();
        }

        header('Location: ?url=chamados&sucesso=Atendimento iniciado.');
        exit();
    }

    public function checkout(): void
    {
        $id      = (int)($_POST['id']     ?? 0);
        $solucao = trim($_POST['solucao'] ?? '');

        if (empty($solucao)) {
            header('Location: ?url=chamados&erro=Informe a solução.');
            exit();
        }

        if (!$this->model->checkout($id, $solucao)) {
            header('Location: ?url=chamados&erro=Não foi possível finalizar.');
            exit();
        }

        header('Location: ?url=chamados&sucesso=Chamado finalizado.');
        exit();
    }
}