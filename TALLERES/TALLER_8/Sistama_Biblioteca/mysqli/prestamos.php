<?php
require_once 'config.php';
function s($v){ return htmlspecialchars($v, ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8'); }
$perPage = 10;
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $perPage;
$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $act = $_POST['act'] ?? '';
    if ($act === 'prestar') {
        $usuario_id = (int)($_POST['usuario_id'] ?? 0);
        $libro_id = (int)($_POST['libro_id'] ?? 0);
        $fecha = $_POST['fecha_prestamo'] ?? date('Y-m-d');
        if ($usuario_id<=0 || $libro_id<=0) $error='Usuario o libro inválido';
        else {
            $mysqli->begin_transaction();
            try {
                $stmt = $mysqli->prepare('SELECT cantidad FROM libros WHERE id=? FOR UPDATE');
                $stmt->bind_param('i',$libro_id);
                $stmt->execute();
                $row = $stmt->get_result()->fetch_assoc();
                if (!$row || $row['cantidad'] <= 0) throw new Exception('No hay ejemplares disponibles');
                $stmt = $mysqli->prepare('INSERT INTO prestamos (usuario_id, libro_id, fecha_prestamo) VALUES (?, ?, ?)');
                $stmt->bind_param('iis', $usuario_id, $libro_id, $fecha);
                $stmt->execute();
                $stmt = $mysqli->prepare('UPDATE libros SET cantidad = cantidad - 1 WHERE id = ?');
                $stmt->bind_param('i', $libro_id);
                $stmt->execute();
                $mysqli->commit();
                header('Location: prestamos.php');
                exit;
            } catch (Exception $e) {
                $mysqli->rollback();
                $error = 'Error: ' . $e->getMessage();
            }
        }
    } elseif ($act === 'devolver') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id>0) {
            $mysqli->begin_transaction();
            try {
                $stmt = $mysqli->prepare('SELECT libro_id, fecha_devolucion FROM prestamos WHERE id=? FOR UPDATE');
                $stmt->bind_param('i',$id);
                $stmt->execute();
                $p = $stmt->get_result()->fetch_assoc();
                if (!$p) throw new Exception('Prestamo no encontrado');
                if ($p['fecha_devolucion'] !== None && $p['fecha_devolucion'] !== null && $p['fecha_devolucion'] !== '') throw new Exception('Ya devuelto');
                $fecha = date('Y-m-d');
                $stmt = $mysqli->prepare('UPDATE prestamos SET fecha_devolucion = ? WHERE id = ?');
                $stmt->bind_param('si', $fecha, $id);
                $stmt->execute();
                $stmt = $mysqli->prepare('UPDATE libros SET cantidad = cantidad + 1 WHERE id = ?');
                $stmt->bind_param('i', $p['libro_id']);
                $stmt->execute();
                $mysqli->commit();
                header('Location: prestamos.php');
                exit;
            } catch (Exception $e) {
                $mysqli->rollback();
                $error = 'Error: ' . $e->getMessage();
            }
        }
    }
}

$stmt = $mysqli->prepare('SELECT COUNT(*) as cnt FROM prestamos WHERE fecha_devolucion IS NULL');
$stmt->execute();
$total = (int)$stmt->get_result()->fetch_assoc()['cnt'];
$pages = max(1, ceil($total / $perPage));

$stmt = $mysqli->prepare('SELECT p.id, p.usuario_id, p.libro_id, p.fecha_prestamo, u.nombre AS usuario, l.titulo AS libro
  FROM prestamos p
  JOIN usuarios u ON u.id = p.usuario_id
  JOIN libros l ON l.id = p.libro_id
  WHERE p.fecha_devolucion IS NULL
  ORDER BY p.id DESC
  LIMIT ? OFFSET ?');
$stmt->bind_param('ii', $perPage, $offset);
$stmt->execute();
$prestamos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$users = $mysqli->query('SELECT id, nombre FROM usuarios ORDER BY nombre LIMIT 100')->fetch_all(MYSQLI_ASSOC);
$books = $mysqli->query('SELECT id, titulo, cantidad FROM libros WHERE cantidad>0 ORDER BY titulo LIMIT 100')->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html><html><head><meta charset="utf-8"><title>Préstamos - MySQLi</title></head><body>
<h1>Prestamos (MySQL)</h1>
<p><a href="index.php">Menú</a></p>
<?php if(!empty($error)): ?><div style="color:red"><?= s($error) ?></div><?php endif; ?>

<h2>Registrar prestamo</h2>
<form method="post" action="prestamos.php">
  <input type="hidden" name="act" value="prestar">
  Usuario:
  <select name="usuario_id">
    <?php foreach($users as $u): ?><option value="<?= s($u['id']) ?>"><?= s($u['nombre']) ?></option><?php endforeach; ?>
  </select>
  Libro:
  <select name="libro_id">
    <?php foreach($books as $b): ?><option value="<?= s($b['id']) ?>"><?= s($b['titulo']) ?> (<?= s($b['cantidad']) ?>)</option><?php endforeach; ?>
  </select>
  Fecha: <input type="date" name="fecha_prestamo" value="<?= date('Y-m-d') ?>">
  <button>Prestar</button>
</form>

<h2>Prestamos activos (<?= $total ?>)</h2>
<table border="1" cellpadding="4">
<tr><th>ID</th><th>Usuario</th><th>Libro</th><th>Fecha prestamo</th><th>Acciones</th></tr>
<?php foreach($prestamos as $p): ?>
<tr>
  <td><?= s($p['id']) ?></td>
  <td><?= s($p['usuario']) ?></td>
  <td><?= s($p['libro']) ?></td>
  <td><?= s($p['fecha_prestamo']) ?></td>
  <td>
    <form method="post" style="display:inline">
      <input type="hidden" name="act" value="devolver">
      <input type="hidden" name="id" value="<?= s($p['id']) ?>">
      <button>Registrar devolucion</button>
    </form>
  </td>
</tr>
<?php endforeach; ?>
</table>
<p>Pagina <?= $page ?> de <?= $pages ?></p>
<?php if ($page>1): ?><a href="?page=<?= $page-1 ?>">&lt; anterior</a><?php endif; ?>
<?php if ($page<$pages): ?><a href="?page=<?= $page+1 ?>">siguiente &gt;</a><?php endif; ?>

<h2>Historial por usuario</h2>
<form method="get" action="prestamos.php">
  <input type="hidden" name="action" value="historial">
  Usuario ID: <input name="user_id">
  <button>Ver historial</button>
</form>
<?php
if ($action === 'historial' && !empty($_GET['user_id'])):
  $uid = (int)$_GET['user_id'];
  $stmt = $mysqli->prepare('SELECT p.*, l.titulo FROM prestamos p JOIN libros l ON l.id = p.libro_id WHERE p.usuario_id = ? ORDER BY p.id DESC');
  $stmt->bind_param('i', $uid);
  $stmt->execute();
  $hist = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<table border="1" cellpadding="4">
<tr><th>ID</th><th>Libro</th><th>Fecha prestamo</th><th>Fecha devolucion</th></tr>
<?php foreach($hist as $h): ?>
<tr>
  <td><?= s($h['id']) ?></td>
  <td><?= s($h['titulo']) ?></td>
  <td><?= s($h['fecha_prestamo']) ?></td>
  <td><?= s($h['fecha_devolucion']) ?></td>
</tr>
<?php endforeach; ?>
</table>
<?php endif; ?>

</body></html>
