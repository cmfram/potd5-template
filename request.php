<?php require_once __DIR__ . '/request-db.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">    
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="Upsorn Praphamontripong">
  <meta name="description" content="Maintenance request form, a small/toy web app for ISP homework assignment, used by CS 3250 (Software Testing)">
  <meta name="keywords" content="CS 3250, Upsorn, Praphamontripong, Software Testing">
  <link rel="icon" href="https://www.cs.virginia.edu/~up3f/cs3250/images/st-icon.png" type="image/png" />  
  
  <title>Maintenance Services</title>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">  
  <link rel="stylesheet" href="maintenance-system.css">  
</head>

<body>  
<div class="container">

  <!-- Page header -->
  <div class="row g-3 mt-2">
    <div class="col">
      <h2>Maintenance Request</h2>
    </div>  
  </div>

  <!-- Flash messages -->
  <?php if (!empty($alert)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($alert) ?></div>
  <?php endif; ?>
  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>
  
  <!-- FORM -->
  <form method="post" action="request.php" onsubmit="return validateInput()">
    <input type="hidden" name="id" value="<?= htmlspecialchars($val['id']) ?>">

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
              <option value='high'   <?= $val['priority_option']==='high'?'selected':'' ?>>
                High - Must be done within 24 hours</option>
              <option value='medium' <?= $val['priority_option']==='medium'?'selected':'' ?>>
                Medium - Within a week</option>
              <option value='low'    <?= $val['priority_option']==='low'?'selected':'' ?>>
                Low - When you get a chance</option>
            </select>
          </div>
        </td>
      </tr>
    </table>

    <div class="row g-3 mx-auto">    
      <div class="col-4 d-grid">
        <input type="submit" value="Add" id="addBtn" name="addBtn" class="btn btn-dark"
               title="Submit a maintenance request" />                  
      </div>	    
      <div class="col-4 d-grid">
        <input type="submit" value="Confirm update" id="cofmBtn" name="cofmBtn" class="btn btn-primary"
               title="Update a maintenance request" />                  
      </div>	    
      <div class="col-4 d-grid">
        <input type="reset" value="Clear form" name="clearBtn" id="clearBtn" class="btn btn-secondary" />
      </div>      
    </div>  
  </form>

</div>

<hr/>

<!-- LIST -->
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
            <td><?= htmlspecialchars($r['id']) ?></td>
            <td><?= htmlspecialchars($r['req_date']) ?></td>
            <td><?= htmlspecialchars($r['room']) ?></td>
            <td><?= htmlspecialchars($r['requested_by']) ?></td>
            <td><?= htmlspecialchars($r['description']) ?></td>
            <td><?= htmlspecialchars($r['priority']) ?></td>
            <td>
              <a class="btn btn-primary btn-sm"
                 href="request.php?action=start_update&id=<?= urlencode($r['id']) ?>">Update</a>
            </td>
            <td>
              <a class="btn btn-danger btn-sm"
                 href="request.php?action=delete&id=<?= urlencode($r['id']) ?>"
                 onclick="return confirm('Delete request #<?= htmlspecialchars($r['id']) ?>?');">Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>

    </table>
  </div>   
</div>

<br/><br/>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>
