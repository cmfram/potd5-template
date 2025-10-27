<?php
// request-db.php — uses the template's field/button names

require_once __DIR__ . '/connect-db.php';

/* ---------- helpers ---------- */
function pick($src, $key, $default='') { return isset($src[$key]) ? trim((string)$src[$key]) : $default; }
function valid_priority($p) {
  $p = strtolower($p);
  return in_array($p, ['low','medium','high'], true) ? $p : '';
}

/* ---------- page state ---------- */
$alert = null;    // success/info message
$error = null;    // error message
$edit  = null;    // row being edited (prefill form)

/* Determine action based on the template's controls */
if (isset($_POST['addBtn'])) {
  $action = 'add';
} elseif (isset($_POST['cofmBtn'])) {
  $action = 'confirm_update';
} else {
  $action = pick($_GET, 'action', '');
}

/* ---------- actions ---------- */
// ADD
if ($action === 'add') {
  $req_date     = pick($_POST, 'requestedDate');
  $room         = pick($_POST, 'roomNo');
  $requested_by = pick($_POST, 'requestedBy');
  $description  = pick($_POST, 'requestDesc');
  $priority     = valid_priority(pick($_POST, 'priority_option'));

  if (!$req_date || !$room || !$requested_by || !$description || !$priority) {
    $error = "All fields (including priority) are required.";
  } else {
    $stmt = $db->prepare(
      "INSERT INTO requests (req_date, room, requested_by, description, priority)
       VALUES (?,?,?,?,?)"
    );
    $stmt->execute([$req_date, $room, $requested_by, $description, $priority]);
    $alert = "Request added.";
  }
}

// START UPDATE — load row by id for prefill
if ($action === 'start_update') {
  $id = (int) pick($_GET, 'id', 0);
  if ($id > 0) {
    $stmt = $db->prepare("SELECT * FROM requests WHERE id = ?");
    $stmt->execute([$id]);
    $edit = $stmt->fetch();
    if (!$edit) $error = "Row not found.";
  }
}

// CONFIRM UPDATE — write back form values
if ($action === 'confirm_update') {
  $id           = (int) pick($_POST, 'id', 0);
  $req_date     = pick($_POST, 'requestedDate');
  $room         = pick($_POST, 'roomNo');
  $requested_by = pick($_POST, 'requestedBy');
  $description  = pick($_POST, 'requestDesc');
  $priority     = valid_priority(pick($_POST, 'priority_option'));

  if ($id <= 0) {
    $error = "Bad request id.";
  } elseif (!$req_date || !$room || !$requested_by || !$description || !$priority) {
    $error = "All fields (including priority) are required.";
  } else {
    $stmt = $db->prepare(
      "UPDATE requests
         SET req_date=?, room=?, requested_by=?, description=?, priority=?
       WHERE id=?"
    );
    $stmt->execute([$req_date, $room, $requested_by, $description, $priority, $id]);
    $alert = "Request #$id updated.";
  }
}

// DELETE
if ($action === 'delete') {
  $id = (int) pick($_GET, 'id', 0);
  if ($id > 0) {
    $stmt = $db->prepare("DELETE FROM requests WHERE id = ?");
    $stmt->execute([$id]);
    $alert = "Request #$id deleted.";
  }
}

/* ---------- data for rendering ---------- */
$rows = $db->query("SELECT * FROM requests ORDER BY id ASC")->fetchAll();

$val = [
  'id'              => $edit['id'] ?? '',
  'requestedDate'   => $edit['req_date'] ?? '',
  'roomNo'          => $edit['room'] ?? '',
  'requestedBy'     => $edit['requested_by'] ?? '',
  'requestDesc'     => $edit['description'] ?? '',
  'priority_option' => $edit['priority'] ?? '',
];
