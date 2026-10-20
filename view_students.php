<?php
require_once "pdo.php";
require_once "check_login.php";

$per_page = 10;

$search = isset($_GET['q']) ? trim($_GET['q']) : "";

$allowed = array('id', 'name', 'email', 'phone', 'created_at');
$sort  = (isset($_GET['sort']) && in_array($_GET['sort'], $allowed)) ? $_GET['sort'] : 'id';
$order = (isset($_GET['order']) && $_GET['order'] == 'asc') ? 'ASC' : 'DESC';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$where  = "";
$params = array();
if ($search != "") {
    $where = "WHERE name LIKE :search_name OR email LIKE :search_email OR phone LIKE :search_phone";
    $params = array(':search_name' => "%$search%", ':search_email' => "%$search%", ':search_phone' => "%$search%");
}

$stmt = $pdo->prepare("SELECT COUNT(*) FROM students $where");
$stmt->execute($params);
$total = $stmt->fetchColumn();
$pages = max(1, ceil($total / $per_page));
if ($page > $pages) $page = $pages;
$offset = ($page - 1) * $per_page;

$stmt = $pdo->prepare("SELECT * FROM students $where ORDER BY $sort $order LIMIT :limit OFFSET :offset");
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

function link_to($page, $sort, $order, $search)
{
    return "view_students.php?" . http_build_query(array(
        'page' => $page, 'sort' => $sort, 'order' => strtolower($order), 'q' => $search
    ));
}

function sort_link($col, $label, $sort, $order, $search)
{
    $new_order = ($sort == $col && $order == 'ASC') ? 'DESC' : 'ASC';
    $arrow = "";
    if ($sort == $col) $arrow = ($order == 'ASC') ? ' <i class="bi bi-caret-up-fill"></i>' : ' <i class="bi bi-caret-down-fill"></i>';
    return '<a href="' . htmlentities(link_to(1, $col, $new_order, $search)) . '">' . $label . $arrow . '</a>';
}

$title = "Students";
require_once "header.php";

$from = ($total == 0) ? 0 : $offset + 1;
$to   = min($offset + $per_page, $total);
?>
<div class="card">
    <div class="card-head">
        <div>
            <h5 class="card-title-icon"><i class="bi bi-person-lines-fill"></i> Students List</h5>
            <div class="card-subtitle">Use this page to search, edit and delete <span class="text-accent">students</span></div>
        </div>
        <div class="toolbar">
            <form method="get">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="q" class="form-control" placeholder="Name, email or phone..." value="<?= htmlentities($search) ?>">
                </div>
                <button class="btn btn-primary">Search</button>
                <?php if ($search != "") { ?>
                    <a href="view_students.php" class="btn btn-light" title="Clear search"><i class="bi bi-x-lg"></i></a>
                <?php } ?>
            </form>
            <a href="add_student.php" class="btn btn-primary btn-round" title="Add Student"><i class="bi bi-plus-lg"></i></a>
        </div>
    </div>

    <div class="table-responsive">
    <table class="table modern-table">
        <thead>
            <tr>
                <th class="col-no"><?= sort_link('id', 'No', $sort, $order, $search) ?></th>
                <th><?= sort_link('name', 'Student', $sort, $order, $search) ?></th>
                <th><?= sort_link('phone', 'Phone', $sort, $order, $search) ?></th>
                <th class="text-center">Address</th>
                <th><?= sort_link('created_at', 'Added', $sort, $order, $search) ?></th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if (count($students) == 0) { ?>
            <tr><td colspan="6" class="text-center text-muted py-5">
                <i class="bi bi-inbox fs-1 d-block mb-2"></i>No students found
            </td></tr>
        <?php } ?>
        <?php foreach ($students as $student) { ?>
            <tr>
                <td class="col-no"><?= $student['id'] ?></td>
                <td>
                    <div class="student-cell">
                        <?php if ($student['profile_picture'] != "" && file_exists($student['profile_picture'])) { ?>
                            <img src="<?= htmlentities($student['profile_picture']) ?>" class="student-img" alt="photo">
                        <?php } else { ?>
                            <div class="avatar avatar-soft"><?= strtoupper(substr($student['name'], 0, 1)) ?></div>
                        <?php } ?>
                        <div>
                            <div class="fw-semibold"><?= htmlentities($student['name']) ?></div>
                            <small class="text-muted"><?= htmlentities($student['email']) ?></small>
                        </div>
                    </div>
                </td>
                <td class="text-nowrap"><span class="pill pill-outline"><i class="bi bi-telephone-fill"></i> <?= htmlentities($student['phone']) ?></span></td>
                <td class="text-center"><span class="pill pill-gradient"><?= htmlentities($student['address']) ?></span></td>
                <td class="text-nowrap"><?= date("d-m-Y", strtotime($student['created_at'])) ?></td>
                <td class="text-end text-nowrap">
                    <a href="update_student.php?id=<?= $student['id'] ?>" class="btn-action edit" title="Edit"><i class="bi bi-pencil"></i></a>
                    <form method="post" action="delete_student.php" class="delete-form d-inline" data-name="<?= htmlentities($student['name']) ?>">
                        <input type="hidden" name="id" value="<?= $student['id'] ?>">
                        <input type="hidden" name="delete" value="Delete">
                        <button type="submit" class="btn-action delete" title="Delete"><i class="bi bi-trash3"></i></button>
                    </form>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    </div>

    <div class="table-footer">
        <span class="showing">Showing <?= $from ?> to <?= $to ?> of <?= $total ?> entries</span>
        <ul class="pagination round-pagination mb-0 flex-wrap">
            <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                <a class="page-link" href="<?= htmlentities(link_to($page - 1, $sort, $order, $search)) ?>" title="Previous"><i class="bi bi-chevron-left"></i></a>
            </li>
            <?php for ($i = 1; $i <= $pages; $i++) { ?>
                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                    <a class="page-link" href="<?= htmlentities(link_to($i, $sort, $order, $search)) ?>"><?= $i ?></a>
                </li>
            <?php } ?>
            <li class="page-item <?= ($page >= $pages) ? 'disabled' : '' ?>">
                <a class="page-link" href="<?= htmlentities(link_to($page + 1, $sort, $order, $search)) ?>" title="Next"><i class="bi bi-chevron-right"></i></a>
            </li>
        </ul>
    </div>
</div>
<?php require_once "footer.php"; ?>
