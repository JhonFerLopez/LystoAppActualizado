CREATE TABLE `componentes`(
  `componente_id` BIGINT NOT NULL AUTO_INCREMENT,
  `componente_nombre` VARCHAR(255),
  `deleted_at` DATETIME,
  PRIMARY KEY (`componente_id`)
);

ALTER TABLE producto
  ADD COLUMN `producto_componente` BIGINT(20) NULL AFTER `producto_tipo`;

  ALTER TABLE producto
  ADD FOREIGN KEY (`producto_componente`) REFERENCES `componentes`(`componente_id`);

INSERT INTO opcion VALUES(100, 1, 'componentes','Componentes');
INSERT INTO opcion_grupo VALUES(1,100, 1);