<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/controllers/SetorController.php';
require_once __DIR__ . '/../app/controllers/PrioridadeController.php';
require_once __DIR__ . '/../app/controllers/ChamadoController.php';
require_once __DIR__ . '/../app/controllers/AtendimentoController.php';

$pdo = conectar();
$url = trim($_GET['url'] ?? 'chamados', '/');

match($url) {
    'chamados'          => (new ChamadoController($pdo))->index(),
    'chamados/criar'    => (new ChamadoController($pdo))->create(),
    'chamados/salvar'   => (new ChamadoController($pdo))->store(),
    'chamados/cancelar' => (new ChamadoController($pdo))->cancelar(),
    'chamados/deletar'  => (new ChamadoController($pdo))->destroy(),
    'chamados/checkin'  => (new AtendimentoController($pdo))->checkin(),
    'chamados/checkout' => (new AtendimentoController($pdo))->checkout(),
    'atendimento'       => (new AtendimentoController($pdo))->index(),
    'setores'           => (new SetorController($pdo))->index(),
    'setores/salvar'    => (new SetorController($pdo))->store(),
    'setores/deletar'   => (new SetorController($pdo))->destroy(),
    'prioridades'       => (new PrioridadeController($pdo))->index(),
    default             => (new ChamadoController($pdo))->index(),
};