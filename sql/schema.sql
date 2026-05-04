

CREATE TABLE setores (
    id   INT          AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL
);

CREATE TABLE prioridades (
    id    INT          AUTO_INCREMENT PRIMARY KEY,
    nome  VARCHAR(255) NOT NULL,
    nivel ENUM('critico','alta','medio','baixo') NOT NULL DEFAULT 'medio'
);

CREATE TABLE chamados (
    id            INT          AUTO_INCREMENT PRIMARY KEY,
    titulo        VARCHAR(255) NOT NULL,
    descricao     TEXT         NULL,
    setor_id      INT          NOT NULL,
    prioridade_id INT          NOT NULL,
    status        VARCHAR(30)  NOT NULL DEFAULT 'Aberto',
    checkin_at    DATETIME     NULL,
    checkout_at   DATETIME     NULL,
    solucao       TEXT         NULL,
    criado_em     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (setor_id)      REFERENCES setores(id),
    FOREIGN KEY (prioridade_id) REFERENCES prioridades(id)
);