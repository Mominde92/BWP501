<?php
require_once "pdo.php";
require_once "check_login.php";

$per_page = 10;

$q = isset($_GET['q']) ? trim($_GET['q']) : "";

$allowed = array('id', 'name', 'email', 'phone', 'created_at');
$sort  = (isset($_GET['sort']) && in_array($_GET['sort'], $allowed)) ? $_GET['sort'] : 'id';
$order = (isset($_GET['order']) && $_GET['order'] == 'asc') ? 'ASC' : 'DESC';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$where  = "";
$params = array();
if ($q != "") {
    $where = "WHERE name LIKE :q1 OR email LIKE :q2 OR phone LIKE :q3";
    $params = array(':q1' => "%$q%", ':q2' => "%$q%", ':q3' => "%$q%");
}

$stmt = $pdo->prepare("SELECT COUNT(*) FROM students $where");
$stmt->execute($params);
$total = $stmt->fetchColumn();
$pages = max(1, ceil($total / $per_page));
if ($page > $pages) $page = $pages;
$offset = ($page - 1) * $per_page;

$stmt = $pdo->prepare("SELECT * FROM students $where ORDER BY $sort $order LIMIT :lim OFFSET :off");
foreach ($params as $key => $val) {
    $stmt->bindValue($key, $val);
}
$stmt->bindValue(':lim', $per_page, PDO::PARAM_INT);
$stmt->bindValue(':off', $offset, PDO::PARAM_INT);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

function link_to($page, $sort, $order, $q)
{
    return "view_students.php?" . http_build_query(array(
        'page' => $page, 'sort' => $sort, 'order' => strtolower($order), 'q' => $q
    ));
}

function sort_link($col, $label, $sort, $order, $q)
{
    $new_order = ($sort == $col && $order == 'ASC') ? 'DESC' : 'ASC';
    $arrow = "";
    if ($sort == $col) $arrow = ($order == 'ASC') ? ' <i class="bi bi-caret-up-fill"></i>' : ' <i class="bi bi-caret-down-fill"></i>';
    return '<a href="' . htmlentities(link_to(1, $col, $new_order, $q)) . '">' . $label . $arrow . '</a>';
}

$title = "Students";
require_once "header.php";

$from = ($total == 0) ? 0 : $offset + 1;
$to   = min($offset + $per_page, $total);
?>
<div class="card">
    <div class="card-head">
        <div>
            <h5>All Students</h5>
            <small class="text-muted"><?= $total ?> student<?= $total == 1 ? '' : 's' ?> found</small>
        </div>
        <div class="toolbar">
            <form method="get">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="q" class="form-control" placeholder="Name, email or phone..." value="<?= htmlentities($q) ?>">
                </div>
                <button class="btn btn-dark">Search</button>
                <?php if ($q != "") { ?>
                    <a href="view_students.php" class="btn btn-light" title="Clear search"><i class="bi bi-x-lg"></i></a>
                <?php } ?>
            </form>
            <a href="add_student.php" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add Student</a>
        </div>
    </div>

    <div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th><?= sort_link('id', '#', $sort, $order, $q) ?></th>
                <th><?= sort_link('name', 'Student', $sort, $order, $q) ?></th>
                <th><?= sort_link('phone', 'Phone', $sort, $order, $q) ?></th>
                <th>Address</th>
                <th><?= sort_link('created_at', 'Added', $sort, $order, $q) ?></th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if (count($rows) == 0) { ?>
            <tr><td colspan="6" class="text-center text-muted py-5">
                <i class="bi bi-inbox fs-1 d-block mb-2"></i>No students found
            </td></tr>
        <?php } ?>
        <?php foreach ($rows as $row) { ?>
            <tr>
                <td class="text-muted"><?= $row['id'] ?></td>
                <td>
                    <div class="student-cell">
                        <?php if ($row['profile_picture'] != "" && file_exists($row['profile_picture'])) { ?>
                            <img src="<?= htmlentities($row['profile_picture']) ?>" class="student-img" alt="photo">
                        <?php } else { ?>
                            <div class="avatar"><?= strtoupper(substr($row['name'], 0, 1)) ?></div>
                        <?php } ?>
                        <div>
                            <div class="fw-semibold"><?= htmlentities($row['name']) ?></div>
                            <small class="text-muted"><?= htmlentities($row['email']) ?></small>
                        </div>
                    </div>
                </td>
                <td class="text-nowrap"><?= htmlentities($row['phone']) ?></td>
                <td><?= htmlentities($row['address']) ?></td>
                <td class="text-muted small text-nowrap"><?= date("d M Y", strtotime($row['created_at'])) ?></td>
                <td class="text-end text-nowrap">
                    <a href="update_student.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-light btn-icon text-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                    <a href="delete_student.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-light btn-icon text-danger" title="Delete"><i class="bi bi-trash3"></i></a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    </div>

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 p-3 border-top">
        <small class="text-muted">Showing <?= $from ?> - <?= $to ?> of <?= $total ?></small>
        <ul class="pagination pagination-sm mb-0 flex-wrap">
            <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                <a class="page-link" href="<?= htmlentities(link_to($page - 1, $sort, $order, $q)) ?>"><i class="bi bi-chevron-left"></i> Previous</a>
            </li>
            <?php for ($i = 1; $i <= $pages; $i++) { ?>
                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                    <a class="page-link" href="<?= htmlentities(link_to($i, $sort, $order, $q)) ?>"><?= $i ?></a>
                </li>
            <?php } ?>
            <li class="page-item <?= ($page >= $pages) ? 'disabled' : '' ?>">
                <a class="page-link" href="<?= htmlentities(link_to($page + 1, $sort, $order, $q)) ?>">Next <i class="bi bi-chevron-right"></i></a>
            </li>
        </ul>
    </div>
</div>
<?php require_once "footer.php"; ?>
