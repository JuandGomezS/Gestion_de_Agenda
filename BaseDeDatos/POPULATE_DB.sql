USE `agenda`;


INSERT INTO `usuarios` (`id_usuarios`, `identificacion`, `nombres`, `apellidos`, `fecha_nacimiento`, `genero`) VALUES
(1, '1234', 'Pepito', 'Perez', '2018-07-22', 'M'),
(2, '4321', 'Mariana', 'Mendez', '2019-09-11', 'F');

INSERT INTO `contactos` (`id_contactos`, `nombre`, `numero`, `tipo_numero`, `parentesco`, `fk_usuarios`) VALUES
(1, 'Juan Delgado', '3120001234', 'Movil', 'Amigo', 1),
(2, 'Maria Mendoza', '4740000', 'Fijo', 'Familiar', 1),
(3, 'Manuel Osorio', '3201234567', 'Movil', 'Compañero de trabajo', 1),
(4, 'Natalia Gonzalez', '8781234', 'Fijo', 'Amigo', 2),
(5, 'Leonardo Gaitán', '3141234567', 'Fijo', 'Familiar', 2),
(6, 'Ximena Cordoba', '866000', 'Fijo', 'Compañero de trabajo', 2);


commit;
