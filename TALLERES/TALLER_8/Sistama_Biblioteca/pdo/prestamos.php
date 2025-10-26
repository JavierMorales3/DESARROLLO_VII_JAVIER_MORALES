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
        if ($usuario_id<=0 || $libro_id<=0) $error='Usuario o libro invalido';
        else {
            try {
                $pdo->beginTransaction();
                $stmt = $pdo->prepare('SELECT cantidad FROM libros WHERE id=? FOR UPDATE');
                $stmt->execute([$libro_id]);
                $row = $stmt->fetch();
                if (!$row || $row['cantidad'] <= 0) throw new Exception('No hay ejemplares disponibles');
                $stmt = $pdo->prepare('INSERT INTO prestamos (usuario_id, libro_id, fecha_prestamo) VALUES (?, ?, ?)');
                $stmt->execute([$usuario_id, $libro_id, $fecha]);
                $stmt = $pdo->prepare('UPDATE libros SET cantidad = cantidad - 1 WHERE id = ?');
                $stmt->execute([$libro_id]);
                $pdo->commit();
                header('Location: prestamos.php'); exit;
            } catch (Exception $e) {
                $pdo->rollBack();
                $error = $e->getMessage();
            }
        }
    } elseif ($act === 'devolver') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id>0) {
            try {
                $pdo->beginTransaction();
                $stmt = $pdo->prepare('SELECT libro_id, fecha_devolucion FROM prestamos WHERE id=? FOR UPDATE');
                $stmt->execute([$id]);
                $p = $stmt->fetch();
                if (!$p) throw new Exception('Prestamo no encontrado');
                if (!empty($p['fecha_devolucion'])) throw new Exception('Ya devuelto');
                $fecha = date('Y-m-d');
                $stmt = $pdo->prepare('UPDATE prestamos SET fecha_devolucion = ? WHERE id = ?');
                $stmt->execute([$fecha, $id]);
                $stmt = $pdo->prepare('UPDATE libros SET cantidad = cantidad + 1 WHERE id = ?');
                $stmt->execute([$p['libro_id']]);
                $pdo->commit();
                header('Location: prestamos.php'); exit;
            } catch (Exception $e) {
                $pdo->rollBack();
                $error = $e->getMessage();
            }
        }
    }
}

$total = $pdo->prepare('SELECT COUNT(*) FROM prestamos WHERE fecha_devolucion IS NULL');
$total->execute();
$totalCount = (int)$total->fetchColumn();
$pages = max(1, ceil($totalCount / $perPage));

$stmt = $pdo->prepare('SELECT p.id, p.usuario_id, p.libro_id, p.fecha_prestamo, u.nombre AS usuario, l.titulo AS libro
  FROM prestamos p
  JOIN usuarios u ON u.id = p.usuario_id
  JOIN libros l ON l.id = p.libro_id
  WHERE p.fecha_devolucion IS NULL
  ORDER BY p.id DESC
  LIMIT ? OFFSET ?');
$stmt->execute([$perPage, $offset]);
$prestamos = $stmt->fetchAll();

$users = $pdo->query('SELECT id, nombre FROM usuarios ORDER BY nombre LIMIT 100')->fetchAll();
$books = $pdo->query('SELECT id, titulo, cantidad FROM libros WHERE cantidad>0 ORDER BY titulo LIMIT 100')->fetchAll();
?>
<!doctype html><html><head><meta charset="utf-8"><title>Préstamos - PDO</title></head><body>
<h1>Prestamos (PDO)</h1>
<p><a href="index.php">Menú</a></p>
<?php if(!empty($error)): ?><div style="color:red"><?= s($error) ?></div><?php endif; ?>

<h2>Registrar prestamo</h2>
<form method="post">
  <input type="hidden" name="act" value="prestar">
  Usuario:
  <select name="usuario_id"><?php foreach($users as $u): ?><option value="<?= s($u['id']) ?>"><?= s($u['nombre']) ?></option><?php endforeach; ?></select>
  Libro:
  <select name="libro_id"><?php foreach($books as $b): ?><option value="<?= s($b['id']) ?>"><?= s($b['titulo']) ?> (<?= s($b['cantidad']) ?>)</option><?php endforeach; ?></select>
  Fecha: <input type="date" name="fecha_prestamo" value="<?= date('Y-m-d') ?>">
  <button>Prestar</button>
</form>

<h2>Prestamos activos (<?= $totalCount ?>)</h2>
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
<form method="get"><input type="hidden" name="action" value="historial">Usuario ID: <input name="user_id"><button>Ver historial</button></form>
<?php
if ($action === 'historial' && !empty($_GET['user_id'])):
  $uid = (int)$_GET['user_id'];
  $stmt = $pdo->prepare('SELECT p.*, l.titulo FROM prestamos p JOIN libros l ON l.id = p.libro_id WHERE p.usuario_id = ? ORDER BY p.id DESC');
  $stmt->execute([$uid]);
  $hist = $stmt->fetchAll();
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
