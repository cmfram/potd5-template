<?php
// request-db.php — CRUD + page state for the professor's schema
require_once __DIR__ . '/connect-db.php';

/* ---------- helpers ---------- */
function pick($src, $key, $default='') { return isset($src[$key]) ? trim((string)$src[$key]) : $default; }
function valid_priority($p) { $p = strtolower($p); return in_array($p, ['low','medium','high'], true) ? $p : ''; }

/* ---------- page state ---------- */
$alert = null;    // success/info message for UI
$error = null;    // error message for UI
$edit  = null;    // row being edited (for prefill)

/* Determine action from your button names or query */
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
  $reqDate     = pick($_POST, 'requestedDate');
  $roomNumber  = pick($_POST, 'roomNo');
  $reqBy       = pick($_POST, 'requestedBy');
  $repairDesc  = pick($_POST, 'requestDesc');
  $reqPriority = valid_priority(pick($_POST, 'priority_option'));

  if (!$reqDate || !$roomNumber || !$reqBy || !$repairDesc || !$reqPriority) {
    $error = "All fields (including priority) are required.";
  } else {
    $stmt = $db->prepare(
      "INSERT INTO requests (reqDate, roomNumber, reqBy, repairDesc, reqPriority)
       VALUES (?,?,?,?,?)"
    );
    $stmt->execute([$reqDate, $roomNumber, $reqBy, $repairDesc, $reqPriority]);
    $alert = "Request added.";
  }
}

// START UPDATE — load a row by reqId for prefill
if ($action === 'start_update') {
  $reqId = (int) pick($_GET, 'id', 0);
  if ($reqId > 0) {
    $stmt = $db->prepare("SELECT * FROM requests WHERE reqId = ?");
    $stmt->execute([$reqId]);
    $edit = $stmt->fetch();
    if (!$edit) $error = "Row not found.";
  }
}

// CONFIRM UPDATE — write form back to DB
if ($action === 'confirm_update') {
  // accept either 'reqId' (new) or 'id' (if any old hidden remains)
  $reqId       = (int) (pick($_POST, 'reqId', pick($_POST, 'id', 0)));
  $reqDate     = pick($_POST, 'requestedDate');
  $roomNumber  = pick($_POST, 'roomNo');
  $reqBy       = pick($_POST, 'requestedBy');
  $repairDesc  = pick($_POST, 'requestDesc');
  $reqPriority = valid_priority(pick($_POST, 'priority_option'));

  if ($reqId <= 0) {
    $error = "Bad request id.";
  } elseif (!$reqDate || !$roomNumber || !$reqBy || !$repairDesc || !$reqPriority) {
    $error = "All fields (including priority) are required.";
  } else {
    $stmt = $db->prepare(
      "UPDATE requests
         SET reqDate = ?, roomNumber = ?, reqBy = ?, repairDesc = ?, reqPriority = ?
       WHERE reqId = ?"
    );
    $stmt->execute([$reqDate, $roomNumber, $reqBy, $repairDesc, $reqPriority, $reqId]);
    $alert = "Request #$reqId updated.";
  }
}

// DELETE
if ($action === 'delete') {
  $reqId = (int) pick($_GET, 'id', 0);
  if ($reqId > 0) {
    $stmt = $db->prepare("DELETE FROM requests WHERE reqId = ?");
    $stmt->execute([$reqId]);
    $alert = "Request #$reqId deleted.";
  }
}

/* ---------- data for rendering ---------- */
$rows = $db->query("SELECT * FROM requests ORDER BY reqId ASC")->fetchAll();

/* Prefill values for the form (keeps your original input names) */
$val = [
  'reqId'            => $edit['reqId']       ?? '',
  'requestedDate'    => $edit['reqDate']     ?? '',
  'roomNo'           => $edit['roomNumber']  ?? '',
  'requestedBy'      => $edit['reqBy']       ?? '',
  'requestDesc'      => $edit['repairDesc']  ?? '',
  'priority_option'  => $edit['reqPriority'] ?? '',
];
