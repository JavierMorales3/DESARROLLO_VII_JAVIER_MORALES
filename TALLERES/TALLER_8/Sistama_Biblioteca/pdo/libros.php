<?php
require_once 'config.php';
function s($v){ return htmlspecialchars($v, ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8'); }
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
        if ($titulo==='' || $autor==='' || $isbn==='') $error='Campos obligatorios';
        else {
            $stmt = $pdo->prepare('INSERT INTO libros (titulo, autor, isbn, anio_publicacion, cantidad) VALUES (?, ?, ?, ?, ?)');
            try {
                $stmt->execute([$titulo,$autor,$isbn,$anio,$cantidad]);
                header('Location: libros.php'); exit;
            } catch (Exception $e) { $error = $e->getMessage(); }
        }
    } elseif ($act === 'update') {
        $id = (int)($_POST['id'] ?? 0);
        $titulo = trim($_POST['titulo'] ?? '');
        $autor = trim($_POST['autor'] ?? '');
        $isbn = trim($_POST['isbn'] ?? '');
        $anio = (int)($_POST['anio'] ?? 0);
        $cantidad = max(0, (int)($_POST['cantidad'] ?? 0));
        $stmt = $pdo->prepare('UPDATE libros SET titulo=?, autor=?, isbn=?, anio_publicacion=?, cantidad=? WHERE id=?');
        $stmt->execute([$titulo,$autor,$isbn,$anio,$cantidad,$id]);
        header('Location: libros.php'); exit;
    } elseif ($act === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        $stmt = $pdo->prepare('DELETE FROM libros WHERE id=?');
        $stmt->execute([$id]);
        header('Location: libros.php'); exit;
    }
}

$sqlWhere = '';
$params = [];
if ($search !== '') {
    $sqlWhere = ' WHERE titulo LIKE ? OR autor LIKE ? OR isbn LIKE ?';
    $like = "%{$search}%";
    $params = [$like,$like,$like];
}
$total = $pdo->prepare('SELECT COUNT(*) as cnt FROM libros' . $sqlWhere);
$total->execute($params);
$totalCount = (int)$total->fetchColumn();
$pages = max(1, ceil($totalCount / $perPage));

$sql = 'SELECT * FROM libros' . $sqlWhere . ' ORDER BY id DESC LIMIT ? OFFSET ?';
$params2 = $params + [$perPage, $offset];
$stmt = $pdo->prepare($sql);
$stmt->execute($params2);
$libros = $stmt->fetchAll();
?>
<!doctype html><html><head><meta charset="utf-8"><title>Libros - PDO</title></head><body>
<h1>Libros (PDO)</h1>
<p><a href="index.php">Menú</a></p>
<form method="get">
  <input name="search" placeholder="Buscar" value="<?= s($search) ?>">
  <button>Buscar</button>
</form>

<h2>Agregar libro</h2>
<?php if(!empty($error)): ?><div style="color:red"><?= s($error) ?></div><?php endif; ?>
<form method="post">
  <input type="hidden" name="act" value="create">
  Titulo: <input name="titulo"><br>
  Autor: <input name="autor"><br>
  ISBN: <input name="isbn"><br>
  Año: <input name="anio" type="number"><br>
  Cantidad: <input name="cantidad" type="number" value="1"><br>
  <button>Agregar</button>
</form>

<h2>Listado (<?= $totalCount ?>)</h2>
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
      Titulo:<input name="titulo" value="<?= s($l['titulo']) ?>">
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
