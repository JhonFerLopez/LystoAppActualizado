CREATE TABLE `ubicacion_fisica`(
  `ubicacion_id` BIGINT NOT NULL AUTO_INCREMENT,
  `ubicacion_nombre` VARCHAR(255),
  `deleted_at` DATETIME,
  PRIMARY KEY (`ubicacion_id`)
);

ALTER TABLE `producto`
  ADD COLUMN `producto_ubicacion_fisica` BIGINT(20) NULL AFTER `producto_componente`;

  ALTER TABLE `producto`
  ADD FOREIGN KEY (`producto_ubicacion_fisica`) REFERENCES `ubicacion_fisica`(`ubicacion_id`);

INSERT INTO opcion VALUES(101, 1, 'ubicacion_fisica','Ubicacion fisica');
INSERT INTO opcion_grupo VALUES(1,101, 1);