<?php

declare(strict_types=1);

class Prioridade
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->prepare('
            SELECT id, nome, tempo_estimado_horas
            FROM prioridades
            ORDER BY tempo_estimado_horas ASC
        ');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById(int $id): array|false
    {
        if ($id <= 0) return false;

        $stmt = $this->pdo->prepare('
            SELECT id, nome, tempo_estimado_horas
            FROM prioridades
            WHERE id = ?
        ');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create(string $nome, int $horas): int
    {
        $stmt = $this->pdo->prepare('
            INSERT INTO prioridades (nome, tempo_estimado_horas)
            VALUES (?, ?)
        ');
        $stmt->execute([$nome, $horas]);
        return (int) $this->pdo->lastInsertId();
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('
            DELETE FROM prioridades WHERE id = ?
        ');
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }
}