<?php

declare(strict_types=1);

class Setor
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->prepare('
            SELECT id, nome
            FROM setores
            ORDER BY nome ASC
        ');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById(int $id): array|false
    {
        if ($id <= 0) return false;

        $stmt = $this->pdo->prepare('
            SELECT id, nome
            FROM setores
            WHERE id = ?
        ');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function nomeExiste(string $nome, int $ignorarId = 0): bool
    {
        $stmt = $this->pdo->prepare('
            SELECT COUNT(*) as total
            FROM setores
            WHERE nome = ? AND id != ?
        ');
        $stmt->execute([$nome, $ignorarId]);
        $row = $stmt->fetch();
        return (int) $row['total'] > 0;
    }

    public function create(string $nome): int
    {
        $stmt = $this->pdo->prepare('
            INSERT INTO setores (nome)
            VALUES (?)
        ');
        $stmt->execute([$nome]);
        return (int) $this->pdo->lastInsertId();
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('
            DELETE FROM setores WHERE id = ?
        ');
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }
}