/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 100432
 Source Host           : localhost:3306
 Source Schema         : evaluacion

 Target Server Type    : MySQL
 Target Server Version : 100432
 File Encoding         : 65001

 Date: 06/05/2025 15:53:31
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for menus
-- ----------------------------
DROP TABLE IF EXISTS `menus`;
CREATE TABLE `menus`  (
  `id_menu` int NOT NULL AUTO_INCREMENT COMMENT 'identificador del menu',
  `id_parent` int NOT NULL COMMENT 'identificador del menu padre',
  `status` int NOT NULL DEFAULT 1 COMMENT 'estatus del menu',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'nombre del menu',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'descripcion del menu',
  PRIMARY KEY (`id_menu`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of menus
-- ----------------------------
INSERT INTO `menus` VALUES (1, 0, 1, 'Catalogos', 'Menu padre de los catalogos');
INSERT INTO `menus` VALUES (3, 0, 1, 'Areas', 'menu de areas');
INSERT INTO `menus` VALUES (4, 1, 1, 'paises', 'catalogo de paises');
INSERT INTO `menus` VALUES (5, 3, 1, 'TI', 'Departamento de TI');
INSERT INTO `menus` VALUES (14, 1, 1, 'Ciudades', '');
INSERT INTO `menus` VALUES (15, 3, 1, 'Sistemas', '');

-- ----------------------------
-- Procedure structure for get_menu_json
-- ----------------------------
DROP PROCEDURE IF EXISTS `get_menu_json`;
delimiter ;;
CREATE PROCEDURE `get_menu_json`()
BEGIN
    SELECT 
        CONCAT(
            '[', 
            GROUP_CONCAT(
                CONCAT(
                    '{',
                        '"id_menu":', m.id_menu, ',',
                        '"nombre":"', m.name, '",',
                        '"descripcion":"', m.description, '",',
                        '"status":', m.status, ',',
                        '"submenus":[',
                            IFNULL((
                                SELECT GROUP_CONCAT(
                                    CONCAT(
                                        '{',
                                            '"id_menu":', sm.id_menu, ',',
                                            '"nombre":"', sm.name, '",',
                                            '"descripcion":"', sm.description, '",',
                                            '"status":', sm.status,
                                        '}'
                                    )
                                )
                                FROM menus sm
                                WHERE sm.id_parent = m.id_menu
																AND sm.status = 1
                            ), ''),
                        ']',
                    '}'
                )
            ),
            ']'
        ) AS menu_json
    FROM menus m
    WHERE m.id_parent = 0
		AND m.status = 1;
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for sp_get_menu_items
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_get_menu_items`;
delimiter ;;
CREATE PROCEDURE `sp_get_menu_items`(IN `ban` VARCHAR(50), IN `filter_i` TEXT)
BEGIN
    IF ban = 'get_all' THEN
        SELECT 
            CONCAT(
                '[', 
                GROUP_CONCAT(
                    CONCAT(
                        '{',
                            '"id_menu":', m.id_menu, ',',
                            '"name":"', m.name, '",',
                            '"description":"', m.description, '",',
                            '"status":', m.status, ',',
                            '"id_parent":', m.id_parent,
                        '}'
                    )
                ),
                ']'
            ) AS items
        FROM menus m
        WHERE m.status = 1;

    ELSEIF ban = 'get_menu_parents' THEN
        SELECT 
            CONCAT(
                '[', 
                GROUP_CONCAT(
                    CONCAT(
                        '{',
                            '"id_menu":', m.id_menu, ',',
                            '"name":"', m.name, '",',
                            '"description":"', m.description, '",',
                            '"status":', m.status, ',',
                            '"id_parent":', m.id_parent,
                        '}'
                    )
                ),
                ']'
            ) AS items
        FROM menus m
        WHERE m.status = 1
        AND m.id_parent = 0;  -- Solo menús padres
				
		ELSEIF ban = 'get_item_by_id' THEN
		
				SET @id = (SELECT JSON_UNQUOTE(JSON_EXTRACT(filter_i, '$.id')));
				
        SELECT 
					JSON_OBJECT(
						'id_menu', m.id_menu,
						'name', m.name,
						'description', m.description,
						'parent', JSON_OBJECT(
								'id', m.id_parent,
								'name', (SELECT name FROM menus WHERE id_menu = m.id_parent)
						)
					)
				AS item
        FROM menus m
        WHERE m.id_menu = @id
				AND m.status = 1
				;

    ELSE
        SELECT '{"error": "Invalid case"}' AS items;
    END IF;
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for sp_save_item
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_save_item`;
delimiter ;;
CREATE PROCEDURE `sp_save_item`(IN `ban` VARCHAR(50), 
IN `id_menu_i` BIGINT,
IN `id_parent_i` BIGINT, 
IN `name_i` VARCHAR(100),
IN `description_i` TEXT)
BEGIN

	DECLARE hasError BOOLEAN DEFAULT 0;
	DECLARE CONTINUE HANDLER FOR sqlexception SET hasError = 1;
	
	
    IF ban = 'create' THEN
		
				START TRANSACTION;
				
        INSERT INTO menus
				(
					id_parent,
					name,
					description
				)
				VALUES
				(
					id_parent_i,
					name_i,
					description_i
				);
				
				IF hasError THEN
					ROLLBACK;
		      SELECT 
						true AS ERROR;
		  ELSE
		      COMMIT;
		      SELECT 
						false AS ERROR;
		  END IF;
			
		ELSEIF ban = 'update' THEN
	
			START TRANSACTION;
			
			UPDATE menus
			SET 
				id_parent = id_parent_i,
				name = name_i,
				description = description_i
			WHERE id_menu = id_menu_i
			;
				
			IF hasError THEN
				ROLLBACK;
				SELECT 
					true AS ERROR;
		  ELSE
		      COMMIT;
		      SELECT 
						false AS ERROR;
		  END IF;
			
		ELSEIF ban = 'delete' THEN
		
				START TRANSACTION;
				
				UPDATE menus
				SET 
					status = 0
				WHERE id_menu = id_menu_i
				;
					
				IF hasError THEN
					ROLLBACK;
					SELECT 
						true AS ERROR;
				ELSE
						COMMIT;
						SELECT 
							false AS ERROR;
				END IF;
				
		

    ELSE
        SELECT '{"error": "Invalid case"}' AS items;
    END IF;
END
;;
delimiter ;

SET FOREIGN_KEY_CHECKS = 1;
