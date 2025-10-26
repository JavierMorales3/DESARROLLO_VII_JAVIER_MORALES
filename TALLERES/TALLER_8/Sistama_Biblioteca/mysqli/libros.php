<?php
require_once 'config.php';

function s($v){ return htmlspecialchars($v, ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8'); }

$action = $_GET['action'] ?? '';
$perPage = 10;
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $perPage;

$search = trim($_GET['search'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $act = $_POST['act'] ?? '';
    if ($act === 'create') {
        $titulo = trim($_POST['titulo'] ?? '');
        $autor = trim($_POST['autor'] ?? '');
        $isbn = trim($_POST['isbn'] ?? '');
        $anio = (int)($_POST['anio'] ?? 0);
        $cantidad = max(0, (int)($_POST['cantidad'] ?? 0));
        if ($titulo === '' || $autor === '' || $isbn === '') {
            $error = 'Título, autor e ISBN son obligatorios.';
        } else {
            $stmt = $mysqli->prepare('INSERT INTO libros (titulo, autor, isbn, anio_publicacion, cantidad) VALUES (?, ?, ?, ?, ?)');
            $stmt->bind_param('sssii', $titulo, $autor, $isbn, $anio, $cantidad);
            if (!$stmt->execute()) {
                $error = 'Error al crear libro: ' . $mysqli->error;
            } else {
                header('Location: libros.php');
                exit;
            }
        }
    } elseif ($act === 'update') {
        $id = (int)($_POST['id'] ?? 0);
        $titulo = trim($_POST['titulo'] ?? '');
        $autor = trim($_POST['autor'] ?? '');
        $isbn = trim($_POST['isbn'] ?? '');
        $anio = (int)($_POST['anio'] ?? 0);
        $cantidad = max(0, (int)($_POST['cantidad'] ?? 0));
        if ($id <= 0) $error = 'ID inválido';
        else {
            $stmt = $mysqli->prepare('UPDATE libros SET titulo=?, autor=?, isbn=?, anio_publicacion=?, cantidad=? WHERE id=?');
            $stmt->bind_param('sssiii', $titulo, $autor, $isbn, $anio, $cantidad, $id);
            if (!$stmt->execute()) $error = 'Error al actualizar: ' . $mysqli->error;
            else { header('Location: libros.php'); exit; }
        }
    } elseif ($act === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = $mysqli->prepare('DELETE FROM libros WHERE id = ?');
            $stmt->bind_param('i', $id);
            $stmt->execute();
            header('Location: libros.php');
            exit;
        }
    }
}

$params = [];
$sqlCount = 'SELECT COUNT(*) as cnt FROM libros';
$sqlWhere = '';
if ($search !== '') {
    $sqlWhere = ' WHERE titulo LIKE ? OR autor LIKE ? OR isbn LIKE ?';
    $like = "%{$search}%";
}
$stmt = $mysqli->prepare($sqlCount . $sqlWhere);
if ($search !== '') {
    $stmt->bind_param('sss', $like, $like, $like);
}
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();
$total = (int)$res['cnt'];
$pages = max(1, ceil($total / $perPage));

$sql = 'SELECT * FROM libros' . $sqlWhere . ' ORDER BY id DESC LIMIT ? OFFSET ?';
if ($search !== '') {
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('ssii', $like, $like, $perPage, $offset);
    $stmt = $mysqli->prepare('SELECT * FROM libros' . $sqlWhere . ' ORDER BY id DESC LIMIT ? OFFSET ?');
    $stmt->bind_param('ssii', $like, $like, $perPage, $offset);
} else {
    $stmt = $mysqli->prepare('SELECT * FROM libros ORDER BY id DESC LIMIT ? OFFSET ?');
    $stmt->bind_param('ii', $perPage, $offset);
}
$stmt->execute();
$result = $stmt->get_result();
$libros = $result->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Libros - MySQLi</title></head><body>
<h1>Libros (MySQL)</h1>
<p><a href="index.php">Menú</a></p>

<form method="get" action="libros.php">
  <input type="text" name="search" value="<?= s($search) ?>" placeholder="Buscar por título, autor o ISBN">
  <button>Buscar</button>
</form>

<h2>Agregar libro</h2>
<?php if (!empty($error)): ?><div style="color:red"><?= s($error) ?></div><?php endif; ?>
<form method="post" action="libros.php">
  <input type="hidden" name="act" value="create">
  Título: <input name="titulo"><br>
  Autor: <input name="autor"><br>
  ISBN: <input name="isbn"><br>
  Año: <input name="anio" type="number"><br>
  Cantidad: <input name="cantidad" type="number" value="1"><br>
  <button>Agregar</button>
</form>

<h2>Listado (<?= $total ?>)</h2>
<table border="1" cellpadding="4">
<tr><th>ID</th><th>Título</th><th>Autor</th><th>ISBN</th><th>Año</th><th>Cantidad</th><th>Acciones</th></tr>
<?php foreach($libros as $l): ?>
<tr>
  <td><?= s($l['id']) ?></td>
  <td><?= s($l['titulo']) ?></td>
  <td><?= s($l['autor']) ?></td>
  <td><?= s($l['isbn']) ?></td>
  <td><?= s($l['anio_publicacion']) ?></td>
  <td><?= s($l['cantidad']) ?></td>
  <td>
    <form method="post" style="display:inline">
      <input type="hidden" name="act" value="delete">
      <input type="hidden" name="id" value="<?= s($l['id']) ?>">
      <button onclick="return confirm('Eliminar?')">Eliminar</button>
    </form>

    <form method="post" style="display:inline">
      <input type="hidden" name="act" value="update">
      <input type="hidden" name="id" value="<?= s($l['id']) ?>">
      Título:<input name="titulo" value="<?= s($l['titulo']) ?>">
      Autor:<input name="autor" value="<?= s($l['autor']) ?>">
      ISBN:<input name="isbn" value="<?= s($l['isbn']) ?>">
      Año:<input name="anio" value="<?= s($l['anio_publicacion']) ?>" type="number" style="width:80px">
      Cant:<input name="cantidad" value="<?= s($l['cantidad']) ?>" type="number" style="width:60px">
      <button>Guardar</button>
    </form>
  </td>
</tr>
<?php endforeach; ?>
</table>

<p>Pagina <?= $page ?> de <?= $pages ?></p>
<?php if ($page>1): ?><a href="?page=<?= $page-1 ?>&search=<?= urlencode($search) ?>">&lt; anterior</a><?php endif; ?>
<?php if ($page<$pages): ?><a href="?page=<?= $page+1 ?>&search=<?= urlencode($search) ?>">siguiente &gt;</a><?php endif; ?>

</body></html>
