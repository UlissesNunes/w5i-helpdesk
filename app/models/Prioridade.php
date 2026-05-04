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
            SELECT id, nome, nivel
            FROM prioridades
            ORDER BY FIELD(nivel, "critico", "alta", "medio", "baixo")
        ');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById(int $id): array|false
    {
        if ($id <= 0) return false;

        $stmt = $this->pdo->prepare('
            SELECT id, nome, nivel
            FROM prioridades
            WHERE id = ?
        ');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}