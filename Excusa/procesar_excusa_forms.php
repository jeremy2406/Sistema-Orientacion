<?php
// Configuración de encabezados para permitir solicitudes desde Google Apps Script
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Incluir archivo de conexión
include '../Componentes/conexion.php';

// Leer datos del formulario
$data = json_decode(file_get_contents("php://input"), true);

// Validar datos básicos
if (
    !isset($data['nombres']) || 
    !isset($data['apellidos']) || 
    !isset($data['grado']) || 
    !isset($data['seccion']) || 
    !isset($data['responsable']) || 
    !isset($data['justificacion']) || 
    !isset($data['fecha_envio'])
) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
    exit;
}

// Función para normalizar texto (eliminar acentos y caracteres especiales)
function normalizar_texto($texto) {
    $texto = strtolower(trim($texto));
    $no_permitidas = array("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","Ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç");
    $permitidas = array("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","A","A","E","I","O","U","c","C");
    return str_replace($no_permitidas, $permitidas, $texto);
}

// Normalizar nombres para búsqueda
$nombre_completo = normalizar_texto($data['nombres'] . ' ' . $data['apellidos']);

// Conectar a la base de datos
$pdo = conectarDB();
if (!$pdo) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Error de conexión a la base de datos']);
    exit;
}

try {
    // 1. Buscar estudiante por nombre
    $stmt = $pdo->prepare('SELECT "Matricula", "Nombre", "Apellido" FROM "Estudiante"');
    $stmt->execute();
    $estudiantes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 2. Comparar nombres para encontrar la mejor coincidencia
    $mejor_match = null;
    $mejor_similitud = 0;
    
    foreach ($estudiantes as $est) {
        $est_nombre = normalizar_texto($est['Nombre'] . ' ' . $est['Apellido']);
        similar_text($nombre_completo, $est_nombre, $sim);
        if ($sim > $mejor_similitud) {
            $mejor_similitud = $sim;
            $mejor_match = $est;
        }
    }
    
    // Determinar si la coincidencia es aceptable (75% o más)
    if ($mejor_similitud >= 75) {
        $matricula = $mejor_match['Matricula'];
        
        // 3. Insertar excusa en la base de datos solo si tenemos una matrícula válida
        $stmt = $pdo->prepare('
            INSERT INTO "Excusas" 
            ("Matricula_estudiantes", "Fecha", "Tutor", "Justificacion") 
            VALUES (?, ?, ?, ?)
        ');
        
        // Convertir la fecha al formato correcto (d-m-Y)
        $fecha_original = strtotime($data['fecha_envio']);
        $fecha = date('Y-d-m', $fecha_original);
        
        $resultado = $stmt->execute([
            $matricula,
            $fecha,
            $data['responsable'],
            $data['justificacion'],
        ]);
        
        if ($resultado) {
            echo json_encode([
                'status' => 'ok', 
                'message' => 'Excusa registrada correctamente',
                'data' => [
                    'matricula' => $matricula,
                    'nombre_proporcionado' => $nombre_completo,
                    'nombre_encontrado' => $mejor_match['Nombre'] . ' ' . $mejor_match['Apellido'],
                    'similitud' => $mejor_similitud
                ]
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se pudo registrar la excusa']);
        }
    } else {
        // No se encontró una coincidencia aceptable para el estudiante
        echo json_encode([
            'status' => 'error', 
            'message' => 'No se encontró un estudiante que coincida con el nombre proporcionado: ' . $data['nombres'] . ' ' . $data['apellidos'],
            'debug' => [
                'mejor_coincidencia' => $mejor_match ? $mejor_match['Nombre'] . ' ' . $mejor_match['Apellido'] : 'Ninguna',
                'similitud' => $mejor_similitud,
                'nombre_buscado' => $nombre_completo
            ]
        ]);
    }
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
}