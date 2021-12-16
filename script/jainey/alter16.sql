ALTER TABLE producto
  ADD COLUMN `is_paquete` BOOLEAN DEFAULT 0  NULL  COMMENT 'PAra saber si el producto es un pqeuete que va formado de otros productos' AFTER `pedir_fecha_venc`;
CREATE TABLE `paquete_has_prod`(
  `paquete_id` BIGINT NOT NULL,
  `prod_id` BIGINT NOT NULL,
  PRIMARY KEY (`paquete_id`, `prod_id`),
  FOREIGN KEY (`paquete_id`) REFERENCES `producto`(`producto_id`),
  FOREIGN KEY (`prod_id`) REFERENCES `producto`(`producto_id`)
);
