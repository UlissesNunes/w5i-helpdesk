<?php

declare(strict_types=1);

require_once __DIR__ . '/../models/Chamado.php';
require_once __DIR__ . '/../models/Setor.php';
require_once __DIR__ . '/../models/Prioridade.php';

class ChamadoController
{
    private Chamado $model;
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo   = $pdo;
        $this->model = new Chamado($pdo);
    }

    public function index(): void
    {
        $chamados = $this->model->getAll();

        $chamados = array_map(function ($c) {
            if ($c['checkin_at']) {
                $ini  = new DateTime($c['checkin_at']);
                $fim  = new DateTime($c['checkout_at'] ?? 'now');
                $diff = $ini->diff($fim);
                $min  = ($diff->h * 60) + $diff->i;
                $c['tempo_exibir'] = $diff->h . 'h ' . $diff->i . 'min';
                $c['atrasado']     = $min > ($c['tempo_estimado_horas'] * 60);
            } else {
                $c['tempo_exibir'] = '—';
                $c['atrasado']     = false;
            }
            return $c;
        }, $chamados);

        require_once __DIR__ . '/../views/chamados/index.php';
    }

    public function create(): void
    {
        $setores     = (new Setor($this->pdo))->getAll();
        $prioridades = (new Prioridade($this->pdo))->getAll();
        require_once __DIR__ . '/../views/chamados/create.php';
    }

    public function store(): void
    {
        $titulo        = trim($_POST['titulo']        ?? '');
        $descricao     = trim($_POST['descricao']     ?? '');
        $setor_id      = (int)($_POST['setor_id']     ?? 0);
        $prioridade_id = (int)($_POST['prioridade_id'] ?? 0);

        if (empty($titulo) || !$setor_id || !$prioridade_id) {
            header('Location: ?url=chamados/criar&erro=Preencha todos os campos.');
            exit();
        }

        $this->model->create($titulo, $descricao, $setor_id, $prioridade_id);
        header('Location: ?url=chamados&sucesso=Chamado criado com sucesso.');
        exit();
    }

    public function checkin(): void
    {
        $id = (int)($_POST['id'] ?? 0);

        if (!$this->model->checkin($id)) {
            header('Location: ?url=chamados&erro=Não foi possível iniciar o atendimento.');
            exit();
        }

        header('Location: ?url=chamados&sucesso=Atendimento iniciado.');
        exit();
    }

    public function checkout(): void
    {
        $id      = (int)($_POST['id']      ?? 0);
        $solucao = trim($_POST['solucao']  ?? '');

        if (empty($solucao)) {
            header('Location: ?url=chamados&erro=Informe a solução aplicada.');
            exit();
        }

        if (!$this->model->checkout($id, $solucao)) {
            header('Location: ?url=chamados&erro=Não foi possível finalizar o chamado.');
            exit();
        }

        header('Location: ?url=chamados&sucesso=Chamado finalizado com sucesso.');
        exit();
    }
}