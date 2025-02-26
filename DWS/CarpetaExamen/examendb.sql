CREATE TABLE Autores (
    id_autor INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    nacionalidad VARCHAR(50)
);

CREATE TABLE Libros (
    id_libro INT PRIMARY KEY AUTO_INCREMENT,
    titulo VARCHAR(150) NOT NULL,
    id_autor INT,
    anno_publicacion INT,
    genero VARCHAR(50),
    FOREIGN KEY (id_autor) REFERENCES Autores(id_autor)
);

CREATE TABLE Miembros (
    id_miembro INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    telefono VARCHAR(20),
    fecha_registro DATE DEFAULT CURRENT_DATE
);

CREATE TABLE Prestamos (
    id_prestamo INT PRIMARY KEY AUTO_INCREMENT,
    id_miembro INT,
    id_libro INT,
    fecha_prestamo DATE DEFAULT CURRENT_DATE,
    fecha_devolucion DATE,
    FOREIGN KEY (id_miembro) REFERENCES Miembros(id_miembro),
    FOREIGN KEY (id_libro) REFERENCES Libros(id_libro)
);

-- Insertar Autores
INSERT INTO Autores (nombre, nacionalidad) VALUES
('Gabriel García Márquez', 'Colombiana'),
('J.K. Rowling', 'Británica'),
('George Orwell', 'Británica'),
('Isabel Allende', 'Chilena');

-- Insertar Libros
INSERT INTO Libros (titulo, id_autor, anno_publicacion, genero) VALUES
('Cien años de soledad', 1, 1967, 'Realismo mágico'),
('Harry Potter y la piedra filosofal', 2, 1997, 'Fantasía'),
('1984', 3, 1949, 'Ciencia ficción'),
('La casa de los espíritus', 4, 1982, 'Realismo mágico'),
('Rebelión en la granja', 3, 1945, 'Sátira política');

-- Insertar Miembros
INSERT INTO Miembros (nombre, email, telefono) VALUES
('Carlos Ramírez', 'cramirez@email.com', '123-456-7890'),
('Lucía Fernández', 'lfernandez@email.com', '987-654-3210'),
('Pedro López', 'plopez@email.com', '555-123-4567'),
('Ana Torres', 'atorres@email.com', '666-789-1234');

-- Insertar Préstamos
INSERT INTO Prestamos (id_miembro, id_libro, fecha_prestamo, fecha_devolucion) VALUES
(1, 1, '2024-02-01', '2024-02-15'), -- Carlos Ramírez pidió "Cien años de soledad"
(1, 3, '2024-02-02', '2024-02-20'), -- Carlos Ramírez pidió "1984"
(2, 2, '2024-02-05', '2024-02-18'), -- Lucía Fernández pidió "Harry Potter"
(3, 4, '2024-02-07', NULL), -- Pedro López pidió "La casa de los espíritus" (aún sin devolver)
(4, 5, '2024-02-10', '2024-02-25'); -- Ana Torres pidió "Rebelión en la granja"
