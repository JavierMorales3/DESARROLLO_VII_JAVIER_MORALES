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
        $nombre = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        if ($nombre===''||$email===''||$password==='') $error='Todos los campos son obligatorios';
        else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO usuarios (nombre,email,password) VALUES (?, ?, ?)');
            try { $stmt->execute([$nombre,$email,$hash]); header('Location: usuarios.php'); exit; } catch (Exception $e){ $error=$e->getMessage(); }
        }
    } elseif ($act === 'update') {
        $id = (int)($_POST['id'] ?? 0);
        $nombre = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $pass = $_POST['password'] ?? '';
        if ($pass!=='') {
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('UPDATE usuarios SET nombre=?, email=?, password=? WHERE id=?');
            $stmt->execute([$nombre,$email,$hash,$id]);
        } else {
            $stmt = $pdo->prepare('UPDATE usuarios SET nombre=?, email=? WHERE id=?');
            $stmt->execute([$nombre,$email,$id]);
        }
        header('Location: usuarios.php'); exit;
    } elseif ($act === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        $stmt = $pdo->prepare('DELETE FROM usuarios WHERE id=?');
        $stmt->execute([$id]);
        header('Location: usuarios.php'); exit;
    }
}

$sqlWhere = '';
$params = [];
if ($search!=='') { $sqlWhere = ' WHERE nombre LIKE ? OR email LIKE ?'; $like = "%{$search}%"; $params = [$like,$like]; }
$total = $pdo->prepare('SELECT COUNT(*) as cnt FROM usuarios' . $sqlWhere);
$total->execute($params);
$totalCount = (int)$total->fetchColumn();
$pages = max(1, ceil($totalCount / $perPage));

$sql = 'SELECT id,nombre,email,created_at FROM usuarios' . $sqlWhere . ' ORDER BY id DESC LIMIT ? OFFSET ?';
$params2 = $params + [$perPage, $offset];
$stmt = $pdo->prepare($sql);
$stmt->execute($params2);
$users = $stmt->fetchAll();
?>
<!doctype html><html><head><meta charset="utf-8"><title>Usuarios - PDO</title></head><body>
<h1>Usuarios (PDO)</h1>
<p><a href="index.php">Menu</a></p>

<form method="get"><input name="search" placeholder="Buscar" value="<?= s($search) ?>"><button>Buscar</button></form>

<h2>Registrar usuario</h2>
<?php if(!empty($error)): ?><div style="color:red"><?= s($error) ?></div><?php endif; ?>
<form method="post">
  <input type="hidden" name="act" value="create">
  Nombre:<input name="nombre"><br>
  Email:<input name="email" type="email"><br>
  Password:<input name="password" type="password"><br>
  <button>Registrar</button>
</form>

<h2>Listado (<?= $totalCount ?>)</h2>
<table border="1" cellpadding="4">
<tr><th>ID</th><th>Nombre</th><th>Email</th><th>Creado</th><th>Acciones</th></tr>
<?php foreach($users as $u): ?>
<tr>
  <td><?= s($u['id']) ?></td>
  <td><?= s($u['nombre']) ?></td>
  <td><?= s($u['email']) ?></td>
  <td><?= s($u['created_at']) ?></td>
  <td>
    <form method="post" style="display:inline">
      <input type="hidden" name="act" value="delete">
      <input type="hidden" name="id" value="<?= s($u['id']) ?>">
      <button onclick="return confirm('Eliminar?')">Eliminar</button>
    </form>
    <form method="post" style="display:inline">
      <input type="hidden" name="act" value="update">
      <input type="hidden" name="id" value="<?= s($u['id']) ?>">
      Nombre:<input name="nombre" value="<?= s($u['nombre']) ?>">
      Email:<input name="email" value="<?= s($u['email']) ?>">
      Pass:<input name="password" type="password">
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
