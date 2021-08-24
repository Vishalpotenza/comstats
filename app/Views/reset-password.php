<?php
$view = array('title' => "Forgot Password");
?>
<?= view('_partials/head',$view); ?>

<body>
  <div class="loader"></div>
  <div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
            <div class="card card-primary">
              <div class="card-header">
                <h4>Reset Password</h4>
              </div>
              <div class="card-body">
                <p class="text-muted">Enter Your New Password</p>
                <form method="POST" id="forgot_reset_password_form">
                  <div class="form-group">
                    <label for="Email">Email</label>
					<input type="hidden" name="email" id="email" value="<?php if(isset($_GET['email'])){ echo $_GET['email']; } ?>" />
                    <input id="email1" type="email" class="form-control" name="email1" tabindex="1" value="<?php if(isset($_GET['email'])){ echo $_GET['email']; } ?>" required disabled>
					
                  </div>
                  <div class="form-group">
                    <label for="password">New Password</label>
                    <input id="password" type="password" class="form-control pwstrength" data-indicator="pwindicator"
                      name="password" tabindex="2" required>
                    <div id="pwindicator" class="pwindicator">
                      <div class="bar"></div>
                      <div class="label"></div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="confirm-password">Confirm Password</label>
                    <input id="confirm-password" type="password" class="form-control" name="confirm-password"
                      tabindex="2" required>
                  </div>
                  <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                      Reset Password
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  <?= view('_partials/footer_js'); ?>
</body>