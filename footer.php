<?php if (isLocalhost()) {?>
<div class="modal fade" id="debugmodal" tabindex="-1" role="dialog" aria-labelledby="debugLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="debugLabel">Debug Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h5>Toggles</h5>
        <p>
          <button class="btn btn-sm btn-secondary" id="auto_open_debug" onclick="autoOpenToggle()">Auto-Open Debug On Page Load</button>
        </p>
        <h5>Session</h5>
        <p id="session_destroy_parent"><button class="btn btn-sm btn-danger" id="session_destroy" onclick="destroySession()">Destroy Session</button></p>
        <?php dump($_SESSION); ?>
        <h5>POST</h5>
        <?php dump($_POST); ?>
        <h5>User Data</h5>
        <?php
        if ($user->isLoggedIn()) {
            dump($user->data());
        } else {
            dump('Not Logged In');
        }
        ?>
        <h5>Misc</h5>
        <?php dump(['abs_us_root' => $abs_us_root, 'us_url_root' => $us_url_root]); ?>
      </div>
    </div>
  </div>
</div>
<script>
window.addEventListener('load', function () {
  document.querySelector('footer').innerHTML = document.querySelector('footer').innerHTML.replace("</p>"," | <a href='#' data-toggle='modal' data-target='#debugmodal'>Debug</a></p>");
  var autoOpen = localStorage.getItem('auto_open_debug');
  updateOpenToggleButton(autoOpen);
  if(autoOpen == 'enabled') {
    $("#debugmodal").modal()
  }
});

function autoOpenToggle() {
  var autoOpen = localStorage.getItem('auto_open_debug');
  if(!autoOpen) {
    localStorage.setItem('auto_open_debug', "disabled");
    autoOpen = localStorage.getItem('auto_open_debug');
  }

  if(autoOpen == "disabled") {
    autoOpen = "enabled";
  } else if(autoOpen == "enabled") {
    autoOpen = "disabled";
  }

  localStorage.setItem('auto_open_debug', autoOpen);
  autoOpen = localStorage.getItem('auto_open_debug');
  updateOpenToggleButton(autoOpen);
  return;
}

function updateOpenToggleButton(state) {
  if(state == 'enabled') {
    document.getElementById('auto_open_debug').classList.replace('btn-secondary', 'btn-success');
  }
  if(state == 'disabled') {
    document.getElementById('auto_open_debug').classList.replace('btn-success', 'btn-secondary');
  }

  return;
}

function destroySession() {
  fetch('//<?="{$_SERVER['HTTP_HOST']}{$us_url_root}usersc/plugins/debugmodal/files/destroy_session.php"; ?>')
  .then(response => response.json())
  .then(data => {
    if(data == 'success') {
      location.reload();
    } else {
      document.getElementById('session_destroy_parent').innerHTML = document.getElementById('session_destroy_parent').innerHTML + '<p id="session_destroy_warning_text">There was an error destroying the session</p>';
    }
  });
}
</script>
<?php } ?>
