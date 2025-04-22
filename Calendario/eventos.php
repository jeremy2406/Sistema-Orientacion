<?php 
 ini_set('display_errors', 0);               
 error_reporting(E_ALL & ~E_NOTICE); 
 
 header('Content-Type: application/json'); 
 session_start(); 
 include '../Componentes/conexion.php'; 
 
 function obtenerEventos() { 
     $pdo = conectarDB(); 
     $eventos = []; 
     if (! $pdo) return $eventos; 
 
     
     $sql = ' 
       SELECT 
         "id", 
         "Titulo"       AS title, 
         "Descripcion"  AS description, 
         "fecha_inicio" AS start, 
         "fecha_fin"    AS end, 
         "color_fondo"  AS backgroundColor 
       FROM "Eventos" 
     '; 
     $stmt = $pdo->query($sql); 
     while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
         
         $start = (new DateTime($row['start']))->format('c'); 
         $end   = (new DateTime($row['end']))->format('c'); 
 
         $eventos[] = [ 
             'id'              => (int)$row['id'], 
             'title'           => $row['title'], 
             'description'     => $row['description'], 
             'start'           => $start, 
             'end'             => $end, 
             'backgroundColor' => $row['backgroundColor'], 
             'borderColor'     => $row['backgroundColor'],   
         ]; 
     } 
 
     return $eventos; 
 } 
 
 if ($_SERVER['REQUEST_METHOD'] === 'GET') { 
     header('Content-Type: application/json'); 
     die(json_encode(obtenerEventos())); 
 } 
 

 if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
    header('Content-Type: application/json'); 

    $accion = $_POST['accion'] ?? ''; 
    $pdo = conectarDB(); 

    if (!$pdo) { 
        echo json_encode(['success' => false, 'error' => 'Error de conexión a la base de datos']); 
        exit; 
    } 

    
    switch ($accion) { 
        case 'agregar': 
            $titulo      = strtoupper($_POST['titulo']      ?? ''); 
            $descripcion = strtoupper($_POST['descripcion'] ?? '');
            $fecha_inicio = $_POST['fecha_inicio'] ?? ''; 
            $fecha_fin = $_POST['fecha_fin'] ?? ''; 
            $color_fondo = $_POST['color_fondo'] ?? '#3788d8'; 
        
            try { 
                $stmt = $pdo->prepare('INSERT INTO "Eventos" ("Titulo", "Descripcion", "fecha_inicio", "fecha_fin", "color_fondo") 
                                       VALUES (?, ?, ?, ?, ?) RETURNING "id"'); 
                $stmt->execute([$titulo, $descripcion, $fecha_inicio, $fecha_fin, $color_fondo]); 
        
                $id = $stmt->fetchColumn(); 
                echo json_encode(['success' => (bool)$id, 'id' => $id]); 
            } catch (PDOException $e) { 
                echo json_encode(['success' => false, 'error' => $e->getMessage()]); 
            } 
            break; 

        case 'actualizar': 
            try { 
                $stmt = $pdo->prepare( 
                    'UPDATE "Eventos" 
                        SET "Titulo"=?, "Descripcion"=?, "fecha_inicio"=?, "fecha_fin"=?, "color_fondo"=? 
                    WHERE "id"=?' 
                ); 
                $resultado = $stmt->execute([ 
                    $_POST['titulo'], 
                    $_POST['descripcion'], 
                    $_POST['fecha_inicio'], 
                    $_POST['fecha_fin'], 
                    $_POST['color_fondo'], 
                    (int)$_POST['id'] 
                ]); 
                header('Content-Type: application/json'); 
                die(json_encode(['success' => (bool)$resultado]));
            } catch (PDOException $e) { 
                header('Content-Type: application/json'); 
                die(json_encode(['success' => false, 'error' => $e->getMessage()])); 
            } 
            break; 
            
        case 'eliminar': 
            $id = (int)($_POST['id'] ?? 0); 
            try { 
                $stmt = $pdo->prepare('DELETE FROM "Eventos" WHERE "id" = ?'); 
                $resultado = $stmt->execute([$id]); 

                echo json_encode(['success' => $resultado]); 
            } catch (PDOException $e) { 
                echo json_encode(['success' => false, 'error' => $e->getMessage()]); 
            } 
            break; 

        default: 
            echo json_encode(['success' => false, 'error' => 'Acción no válida']); 
    } 

    exit; 
}
