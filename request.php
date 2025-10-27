<?php require_once __DIR__ . '/request-db.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">    
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="Upsorn Praphamontripong">
  <meta name="description" content="Maintenance request form">
  <meta name="keywords" content="CS 4750, Database">
  <link rel="icon" href="https://www.cs.virginia.edu/~up3f/cs3250/images/st-icon.png" type="image/png" />  
  
  <title>Maintenance Services</title>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">  
  <link rel="stylesheet" href="maintenance-system.css">  
</head>

<body>  
<div class="container">

  <div class="row g-3 mt-2">
    <div class="col">
      <h2>Maintenance Request</h2>
    </div>  
  </div>

  <?php if (!empty($alert)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($alert) ?></div>
  <?php endif; ?>
  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <!-- FORM -->
  <form method="post" action="request.php">
    <input type="hidden" name="reqId" value="<?= htmlspecialchars($val['reqId']) ?>">

    <table style="width:98%">
      <tr>
        <td width="50%">
          <div class='mb-3'>
            Requested date:
            <input type='text' class='form-control' 
                   id='requestedDate' name='requestedDate' 
                   placeholder='Format: yyyy-mm-dd' 
                   pattern="\d{4}-\d{1,2}-\d{1,2}" 
                   value="<?= htmlspecialchars($val['requestedDate']) ?>" />
          </div>
        </td>
        <td>
          <div class='mb-3'>
            Room Number:
            <input type='text' class='form-control' id='roomNo' name='roomNo' 
                   value="<?= htmlspecialchars($val['roomNo']) ?>" />
          </div>
        </td>
      </tr>

      <tr>
        <td colspan="2">
          <div class='mb-3'>
            Requested by: 
            <input type='text' class='form-control' id='requestedBy' name='requestedBy'
                   placeholder='Enter your name'
                   value="<?= htmlspecialchars($val['requestedBy']) ?>" />
          </div>
        </td>
      </tr>

      <tr>
        <td colspan="2">
          <div class="mb-3">
            Description of work/repair:
            <input type='text' class='form-control' id='requestDesc' name='requestDesc'
                   value="<?= htmlspecialchars($val['requestDesc']) ?>" />
          </div>
        </td>
      </tr>

      <tr>
        <td colspan="2">
          <div class='mb-3'>
            Requested Priority:
            <select class='form-select' id='priority_option' name='priority_option' required>
              <option value="" <?= $val['priority_option']===''?'selected':'' ?> disabled>Select priority</option>
              <option value='high'   <?= $val['priority_option']==='high'?'selected':'' ?>>High - Must be done within 24 hours</option>
              <option value='medium' <?= $val['priority_option']==='medium'?'selected':'' ?>>Medium - Within a week</option>
              <option value='low'    <?= $val['priority_option']==='low'?'selected':'' ?>>Low - When you get a chance</option>
            </select>
          </div>
        </td>
      </tr>
    </table>

    <div class="row g-3 mx-auto">    
      <div class="col-4 d-grid">
        <input type="submit" value="Add" id="addBtn" name="addBtn" class="btn btn-dark" />                  
      </div>	    
      <div class="col-4 d-grid">
        <input type="submit" value="Confirm update" id="cofmBtn" name="cofmBtn" class="btn btn-primary" />                  
      </div>	    
      <div class="col-4 d-grid">
        <input type="reset" value="Clear form" name="clearBtn" id="clearBtn" class="btn btn-secondary" />
      </div>      
    </div>  
  </form>

</div>

<hr/>

<div class="container">
  <h3>List of requests</h3>
  <div class="row justify-content-center">  
    <table class="w3-table w3-bordered w3-card-4 center" style="width:100%">
      <thead>
        <tr style="background-color:#B0B0B0">
          <th width="8%"><b>ReqID</b></th>
          <th width="12%"><b>Date</b></th>        
          <th width="10%"><b>Room#</b></th> 
          <th width="14%"><b>By</b></th>
          <th><b>Description</b></th>        
          <th width="10%"><b>Priority</b></th> 
          <th width="10%"><b>Update?</b></th>
          <th width="10%"><b>Delete?</b></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $r): ?>
          <tr>
            <td><?= htmlspecialchars($r['reqId']) ?></td>
            <td><?= htmlspecialchars($r['reqDate']) ?></td>
            <td><?= htmlspecialchars($r['roomNumber']) ?></td>
            <td><?= htmlspecialchars($r['reqBy']) ?></td>
            <td><?= htmlspecialchars($r['repairDesc']) ?></td>
            <td><?= htmlspecialchars($r['reqPriority']) ?></td>
            <td>
              <a class="btn btn-primary btn-sm"
                 href="request.php?action=start_update&id=<?= urlencode($r['reqId']) ?>">Update</a>
            </td>
            <td>
              <a class="btn btn-danger btn-sm"
                 href="request.php?action=delete&id=<?= urlencode($r['reqId']) ?>"
                 onclick="return confirm('Delete request #<?= htmlspecialchars($r['reqId']) ?>?');">Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>   
</div>

<br/><br/>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
