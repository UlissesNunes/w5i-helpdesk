<?php

declare(strict_types=1);

class Chamado
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->prepare('
            SELECT
                c.id,
                c.titulo,
                c.status,
                c.checkin_at,
                c.checkout_at,
                c.solucao,
                c.criado_em,
                s.nome AS setor,
                p.nome AS prioridade,
                p.tempo_estimado_horas
            FROM chamados c
            JOIN setores     s ON s.id = c.setor_id
            JOIN prioridades p ON p.id = c.prioridade_id
            ORDER BY c.id DESC
        ');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById(int $id): array|false
    {
        if ($id <= 0) return false;

        $stmt = $this->pdo->prepare('
            SELECT
                c.*,
                s.nome AS setor,
                p.nome AS prioridade,
                p.tempo_estimado_horas
            FROM chamados c
            JOIN setores     s ON s.id = c.setor_id
            JOIN prioridades p ON p.id = c.prioridade_id
            WHERE c.id = ?
        ');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create(
        string $titulo,
        string $descricao,
        int    $setor_id,
        int    $prioridade_id
    ): int {
        $stmt = $this->pdo->prepare('
            INSERT INTO chamados
                (titulo, descricao, setor_id, prioridade_id, status)
            VALUES (?, ?, ?, ?, "Aberto")
        ');
        $stmt->execute([$titulo, $descricao, $setor_id, $prioridade_id]);
        return (int) $this->pdo->lastInsertId();
    }

    public function checkin(int $id): bool
    {
        $stmt = $this->pdo->prepare('
            UPDATE chamados
            SET checkin_at = NOW(),
                status     = "Em atendimento"
            WHERE id     = ?
            AND   status  = "Aberto"
        ');
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }

    public function checkout(int $id, string $solucao): bool
    {
        $stmt = $this->pdo->prepare('
            UPDATE chamados
            SET checkout_at = NOW(),
                solucao     = ?,
                status      = "Finalizado"
            WHERE id     = ?
            AND   status = "Em atendimento"
        ');
        $stmt->execute([$solucao, $id]);
        return $stmt->rowCount() > 0;
    }

    public function cancelar(int $id): bool
    {
        $stmt = $this->pdo->prepare('
            UPDATE chamados
            SET status = "Cancelado"
            WHERE id   = ?
            AND status NOT IN ("Finalizado", "Cancelado")
        ');
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }

    public function delete(int $id): bool
    {
        if ($id <= 0) return false;

        $stmt = $this->pdo->prepare('
            DELETE FROM chamados
            WHERE id = ?
        ');
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }
}