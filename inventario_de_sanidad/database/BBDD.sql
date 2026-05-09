-- Eliminar la base de datos si existe.
DROP DATABASE IF EXISTS healthcare;

-- Crear la base de datos.
CREATE DATABASE IF NOT EXISTS healthcare COMMENT 'Base de datos para gestionar usuarios, actividades de los usuarios, materiales y modificaciones de los materiales sanitarios';

-- Seleccionar la base de datos 'healthcare' para su uso.
USE healthcare;

-- Establecer InnoDB como motor de la base de datos.
SET default_storage_engine = InnoDB;

-- Crear la tabla de usuarios.
CREATE TABLE users (
    user_id            INT AUTO_INCREMENT NOT NULL COMMENT 'Identificador del usuario',
    first_name         VARCHAR(40) NOT NULL COMMENT 'Nombre del usuario',
    last_name          VARCHAR(60) NOT NULL COMMENT 'Apellidos del usuario',
    email              VARCHAR(100) NOT NULL COMMENT 'Correo electrónico del usuario',
    hashed_password    VARCHAR(255) NOT NULL COMMENT 'Contraseña del usuario (hasheada)',
    first_log          BOOLEAN NOT NULL DEFAULT FALSE COMMENT 'Primer inicio de sesión del usuario',
    user_type          ENUM('student', 'teacher', 'admin') NOT NULL COMMENT 'Tipo de usuario',
    created_at         DATETIME NOT NULL COMMENT 'Fecha de alta del usuario',
    PRIMARY KEY (user_id),
    UNIQUE KEY (email)
);

-- Crear la tabla de materiales.
CREATE TABLE materials (
    material_id   INT AUTO_INCREMENT NOT NULL COMMENT 'Identificador del material',
    name          VARCHAR(60) NOT NULL COMMENT 'Nombre del material',
    description   VARCHAR(255) NOT NULL COMMENT 'Descripción del material',
    image_path    VARCHAR(255) COMMENT 'Ruta donde está guardada la imagen en el servidor',
    PRIMARY KEY (material_id)
);

-- Crear la tabla de almacenamiento.
CREATE TABLE storages (
    material_id    INT NOT NULL COMMENT 'Identificador del material',
    storage        ENUM('odontology','CAE') NOT NULL COMMENT 'Almacén físico del material',
    storage_type   ENUM('use','reserve') NOT NULL COMMENT 'Tipo de almacenamiento',
    cabinet        VARCHAR(30) NOT NULL COMMENT 'Armario',
    shelf          INT UNSIGNED NOT NULL COMMENT 'Balda',
    drawer         INT UNSIGNED COMMENT 'Cajón',
    units          INT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Cantidad de unidades almacenadas',
    min_units      INT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Cantidad mínima esperada',
    PRIMARY KEY (material_id, storage, storage_type),
    FOREIGN KEY (material_id) REFERENCES materials(material_id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Crear la tabla del historial de modificaciones.
CREATE TABLE modifications (
    user_id          INT NOT NULL COMMENT 'Identificador del usuario que realiza la acción',
    material_id      INT NOT NULL COMMENT 'Identificador del material modificado',
    storage          ENUM('odontology','CAE') NOT NULL COMMENT 'Ubicación del almacén',
    storage_type     ENUM('use','reserve') NOT NULL COMMENT 'Tipo de almacenamiento afectado',
    action_datetime  DATETIME NOT NULL COMMENT 'Fecha y hora de la modificación',
    units            INT NOT NULL COMMENT 'Unidades añadidas o retiradas',
    PRIMARY KEY (user_id, material_id, storage_type, storage, action_datetime),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (material_id, storage, storage_type) REFERENCES storages(material_id, storage, storage_type) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Crear la tabla de actividades.
CREATE TABLE activities (
    activity_id   INT AUTO_INCREMENT NOT NULL COMMENT 'Identificador de la actividad',
    user_id       INT NOT NULL COMMENT 'Usuario que realiza la actividad',
    teacher_id    INT NOT NULL COMMENT 'Docente responsable de la actividad',
    title         VARCHAR(100) NOT NULL COMMENT 'Título de la actividad',
    created_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de creación de la actividad',
    PRIMARY KEY (activity_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Crear la tabla para materiales utilizados en actividades.
CREATE TABLE material_activity (
    activity_id   INT NOT NULL COMMENT 'Identificador de la actividad',
    material_id   INT NOT NULL COMMENT 'Identificador del material utilizado',
    units         INT UNSIGNED NOT NULL COMMENT 'Cantidad de unidades utilizadas',
    PRIMARY KEY (activity_id, material_id),
    FOREIGN KEY (activity_id) REFERENCES activities(activity_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (material_id) REFERENCES materials(material_id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Índices para storages
CREATE INDEX idx_storages_material ON storages (material_id) COMMENT 'Búsquedas por material';
CREATE INDEX idx_storages_type ON storages (storage_type) COMMENT 'Búsquedas por tipo de almacenamiento';

-- Índices para modifications
CREATE INDEX idx_modifications_user ON modifications (user_id) COMMENT 'Búsquedas por usuario';
CREATE INDEX idx_modifications_storage_type ON modifications (storage_type) COMMENT 'Búsquedas por tipo de almacenamiento';
CREATE INDEX idx_modifications_datetime ON modifications (action_datetime) COMMENT 'Búsquedas por fecha';
CREATE INDEX idx_modifications_user_datetime ON modifications (user_id, action_datetime) COMMENT 'Búsquedas por usuario y fecha';

-- Índices para material_activity
CREATE INDEX idx_material_activity_material ON material_activity (material_id) COMMENT 'Búsquedas por material en actividades';

-- Índices para activities
CREATE INDEX idx_activities_created_at ON activities (created_at) COMMENT 'Búsquedas por fecha de actividad';

-- Confirmar cambios.
COMMIT;
