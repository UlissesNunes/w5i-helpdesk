<?php

declare(strict_types=1);

require_once __DIR__ . '/../models/Chamado.php';
require_once __DIR__ . '/../models/Setor.php';
require_once __DIR__ . '/../models/Prioridade.php';
require_once __DIR__ . '/../helpers/prioridade.php';

class ChamadoController
{
    private Chamado    $model;
    private Setor      $setorModel;
    private Prioridade $prioridadeModel;

    public function __construct(PDO $pdo)
    {
        $this->model           = new Chamado($pdo);
        $this->setorModel      = new Setor($pdo);
        $this->prioridadeModel = new Prioridade($pdo);
    }

    public function index(): void
    {
        $this->renderizar('chamados/index', [
            'chamados' => $this->enriquecerLista($this->model->getAll()),
            'erro'     => $_GET['erro']    ?? null,
            'sucesso'  => $_GET['sucesso'] ?? null,
        ]);
    }

    public function create(): void
    {
        $this->renderizar('chamados/create', [
            'setores'     => $this->setorModel->getAll(),
            'prioridades' => $this->prioridadeModel->getAll(),
            'erro'        => $_GET['erro'] ?? null,
        ]);
    }

    public function store(): void
    {
        $this->garantirMetodo('POST');

        $titulo        = $this->limpar($_POST['titulo']        ?? '');
        $descricao     = $this->limpar($_POST['descricao']     ?? '');
        $setor_id      = (int) ($_POST['setor_id']             ?? 0);
        $prioridade_id = (int) ($_POST['prioridade_id']        ?? 0);

        if (empty($titulo) || !$setor_id || !$prioridade_id) {
            $this->redirecionar('chamados/criar', [
                'erro' => 'Título, setor e urgência são obrigatórios.',
            ]);
        }

        $this->model->create($titulo, $descricao, $setor_id, $prioridade_id);
        $this->redirecionar('chamados', ['sucesso' => 'Chamado aberto com sucesso.']);
    }

    public function cancelar(): void
    {
        $this->garantirMetodo('POST');

        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            $this->redirecionar('chamados', ['erro' => 'ID inválido.']);
        }

        if (!$this->model->cancelar($id)) {
            $this->redirecionar('chamados', [
                'erro' => 'Não foi possível cancelar.',
            ]);
        }

        $this->redirecionar('chamados', ['sucesso' => 'Chamado cancelado.']);
    }

    public function destroy(): void
    {
        $this->garantirMetodo('POST');

        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            $this->redirecionar('chamados', ['erro' => 'ID inválido.']);
        }

        if (!$this->model->findById($id)) {
            $this->redirecionar('chamados', ['erro' => 'Chamado não encontrado.']);
        }

        $this->model->delete($id);
        $this->redirecionar('chamados', ['sucesso' => 'Chamado removido com sucesso.']);
    }

    private function enriquecerLista(array $chamados): array
    {
        return array_map(fn($c) => $this->calcularTempo($c), $chamados);
    }

    private function calcularTempo(array $c): array
    {
        if (!$c['checkin_at']) {
            $c['tempo_exibir'] = '—';
            $c['atrasado']     = false;
            return $c;
        }

        $ini  = new DateTime($c['checkin_at']);
        $fim  = new DateTime($c['checkout_at'] ?? 'now');
        $diff = $ini->diff($fim);
        $min  = ($diff->days * 1440) + ($diff->h * 60) + $diff->i;

        $c['tempo_exibir'] = $diff->h . 'h ' . $diff->i . 'min';
        $c['atrasado']     = $min > (horasPorNivel($c['prioridade_nivel']) * 60);

        return $c;
    }

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