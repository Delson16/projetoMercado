USE mercazon;

-- Criando o usuário
CREATE USER 'usuario'@'localhost' IDENTIFIED BY 'F7!vR$4x^Tp9@aJq';

-- Concedendo permissões ao usuário
GRANT INSERT, UPDATE, DELETE, SELECT ON mercazon.usuarios TO 'usuario'@'localhost';
GRANT INSERT, UPDATE, DELETE, SELECT ON mercazon.usuario_favorita_produto TO 'usuario'@'localhost';
GRANT SELECT ON mercazon.produtos TO 'usuario'@'localhost';
GRANT SELECT ON mercazon.lojistas TO 'usuario'@'localhost';

-- Criando o lojista
CREATE USER 'lojista'@'localhost' IDENTIFIED BY '7$Lz*R@8e%wX!qGm';

-- Concedendo permissões ao lojista
GRANT SELECT, INSERT, UPDATE, DELETE ON mercazon.produtos TO 'lojista'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON mercazon.lojistas TO 'lojista'@'localhost';

-- Aplicando as alterações
FLUSH PRIVILEGES;