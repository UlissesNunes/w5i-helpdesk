<?php

declare(strict_types=1);

require_once __DIR__ . '/../models/Chamado.php';
require_once __DIR__ . '/../helpers/prioridade.php';

class AtendimentoController
{
    private Chamado $model;

    public function __construct(PDO $pdo)
    {
        $this->model = new Chamado($pdo);
    }

    public function index(): void
    {
        $this->renderizar('atendimento/index', [
            'chamados' => $this->buscarAtivos(),
            'erro'     => $_GET['erro']    ?? null,
            'sucesso'  => $_GET['sucesso'] ?? null,
        ]);
    }

    public function checkin(): void
    {
        $this->garantirMetodo('POST');

        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            $this->redirecionar('atendimento', ['erro' => 'ID inválido.']);
        }

        if (!$this->model->checkin($id)) {
            $this->redirecionar('atendimento', [
                'erro' => 'Não foi possível iniciar. Verifique o status do chamado.',
            ]);
        }

        $this->redirecionar('atendimento', ['sucesso' => 'Atendimento iniciado com sucesso.']);
    }

    public function checkout(): void
    {
        $this->garantirMetodo('POST');

        $id      = (int) ($_POST['id']    ?? 0);
        $solucao = $this->limpar($_POST['solucao'] ?? '');

        if ($id <= 0) {
            $this->redirecionar('atendimento', ['erro' => 'ID inválido.']);
        }

        if (empty($solucao)) {
            $this->redirecionar('atendimento', [
                'erro' => 'Informe a solução aplicada antes de finalizar.',
            ]);
        }

        if (strlen($solucao) < 5) {
            $this->redirecionar('atendimento', [
                'erro' => 'A solução deve ter pelo menos 5 caracteres.',
            ]);
        }

        if (!$this->model->checkout($id, $solucao)) {
            $this->redirecionar('atendimento', [
                'erro' => 'Não foi possível finalizar. O chamado precisa estar em atendimento.',
            ]);
        }

        $this->redirecionar('atendimento', ['sucesso' => 'Chamado finalizado com sucesso.']);
    }

    private function buscarAtivos(): array
    {
        $todos = $this->model->getAll();

        $ativos = array_filter(
            $todos,
            fn($c) => in_array($c['status'], ['Aberto', 'Em atendimento'])
        );

        return array_map(fn($c) => $this->calcularTempo($c), $ativos);
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