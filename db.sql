drop database if exists Consumibles_impresoras;

CREATE DATABASE IF NOT EXISTS `Consumibles_impresoras`;
USE Consumibles_impresoras;

CREATE TABLE if not EXISTS Bodega(
	Id_bodega INT AUTO_INCREMENT PRIMARY KEY,
    Lugar VARCHAR(50) unique NOT NULL
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS Impresora(
    Id_impresora int AUTO_INCREMENT PRIMARY KEY,
    Marca_impresora VARCHAR(20) NOT NULL,
    Modelo_impresora VARCHAR(20) UNIQUE NOT NULL 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



CREATE TABLE if not EXISTS `Consumible` (
  `Id_consumible` int(11) AUTO_INCREMENT PRIMARY KEY,
  `Fecha_ingreso` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `Marca` varchar(20) NOT NULL,
  `Modelo` varchar(20) NOT NULL,
  `Tipo` enum('Tambor','Fusor','Tinta', 'Toner', 'Tambor de residuo', 'Tambor de arrastre', 'Correa de arrastre') NOT NULL,
  Id_impresora int NOT NULL,
    rango_stockMinimo int DEFAULT 0 NULL,
  rango_stockMaximo int DEFAULT 0 NULL,
   FOREIGN KEY (Id_impresora) REFERENCES Impresora(Id_impresora)
    ON DELETE CASCADE ON UPDATE CASCADE  
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS Bodega_Consumible(
    Id_ubicacion int AUTO_INCREMENT PRIMARY KEY,
    Id_bodega int not null,
    Id_consumible int not null,
    FOREIGN KEY (Id_bodega) REFERENCES Bodega(Id_bodega)
    ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (Id_consumible) REFERENCES Consumible(Id_consumible)
    ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

Create table if not exists Retiro(
    Id_retiro int AUTO_INCREMENT PRIMARY KEY,
    Fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    Usuario_retira varchar(120) NOT NULL,
    Usuario_recibe varchar(120) not null,
    Id_recibe int not null,
    Id_departamento int not null,
    Departamento varchar(100) not null,
    `Marca` varchar(20) NOT NULL,
    `Modelo` varchar(20) NOT NULL,
   `Tipo` varchar(20) NOT NULL,
    Cantidad int not null,
    Impresora varchar(30) not null,
    Id_impresora int not null,
    Id_direccion int not null,
    Bodega varchar(30) not null, 
    Id_bodega int not null
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE if not exists `funcionarios` (
  `id` int(100) NOT NULL,
  `rut` varchar(10) COLLATE latin1_spanish_ci NOT NULL,
  `nombres` varchar(150) COLLATE latin1_spanish_ci NOT NULL,
  `apellidos` varchar(200) COLLATE latin1_spanish_ci NOT NULL,
  `fechanac` date NOT NULL,
  `domicilio` varchar(200) COLLATE latin1_spanish_ci NOT NULL,
  `telefono` int(12) NOT NULL,
  `titulo` varchar(150) COLLATE latin1_spanish_ci NOT NULL,
  `correo` varchar(100) COLLATE latin1_spanish_ci NOT NULL,
  `sueldo` int(10) NOT NULL,
  `emolumento` varchar(10) COLLATE latin1_spanish_ci NOT NULL DEFAULT 'si',
  `encargado` varchar(10) COLLATE latin1_spanish_ci NOT NULL DEFAULT 'no',
  `ciudad` varchar(100) COLLATE latin1_spanish_ci NOT NULL,
  `direccion` int(12) NOT NULL,
  `depart` int(12) NOT NULL,
  `unidad` int(12) NOT NULL,
  `retenido` varchar(5) COLLATE latin1_spanish_ci NOT NULL DEFAULT 'no',
  `director` varchar(5) COLLATE latin1_spanish_ci NOT NULL DEFAULT 'no',
  `tipocontrato` int(5) NOT NULL,
  `activo` varchar(12) COLLATE latin1_spanish_ci NOT NULL DEFAULT 'si',
  `escalafon` varchar(100) COLLATE latin1_spanish_ci NOT NULL,
  `grado` int(11) NOT NULL,
  `subrogancia` int(10) NOT NULL,
  `grupos` varchar(50) COLLATE latin1_spanish_ci NOT NULL,
  `enfermedadcr` varchar(100) COLLATE latin1_spanish_ci NOT NULL,
  `alergias` varchar(200) COLLATE latin1_spanish_ci NOT NULL,
  `discapacidad` varchar(200) COLLATE latin1_spanish_ci NOT NULL,
  `credencial` varchar(20) COLLATE latin1_spanish_ci NOT NULL,
  `credencialfolio` int(30) NOT NULL,
  `pensioninvalidez` varchar(10) COLLATE latin1_spanish_ci NOT NULL,
  `credencialcodigo` varchar(50) COLLATE latin1_spanish_ci NOT NULL,
  `cargo` varchar(250) COLLATE latin1_spanish_ci NOT NULL,
  `decretosub` int(20) NOT NULL,
  `fechasub` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

ALTER TABLE `funcionarios`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `funcionarios`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=524;
  

CREATE TABLE if not exists `departamentos` (
  `iddepart` int(10) NOT NULL,
  `depart` varchar(200) COLLATE latin1_spanish_ci NOT NULL,
  `direccion` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

ALTER TABLE `departamentos`
  ADD PRIMARY KEY (`iddepart`);
  
ALTER TABLE `departamentos`
  MODIFY `iddepart` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;
  

CREATE TABLE if not exists `direcciones` (
  `iddireccion` int(12) NOT NULL,
  `direccion` varchar(150) COLLATE latin1_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

ALTER TABLE `direcciones`
  ADD PRIMARY KEY (`iddireccion`);
  
ALTER TABLE `direcciones`
  MODIFY `iddireccion` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;


