<section class="registration-container">
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <?php if (!isset($_SESSION['userInfo']) && !isset($_SESSION['userInfo']['otp'])) { ?>
                        <div class="col-sm-6 registration-left">
                            <div class="registration-left-img" alt="Registration">
                            </div>
                        </div>
                        <div class="col-sm-6 p-4">
                            <h4>Create new account</h4>
                            <form method="POST" id='registrationForm' action="<?php echo isset($key) && $key == 'login' ? base_url('Login/verifyUser') : base_url('Login/addUser') ?>">
                                <?php if (isset($key) && $key != 'login') { ?>
                                    <div class="form-group">
                                        <label for="ename">Name</label>
                                        <input type="text" class="form-control" name="ename" id="ename" required aria-describedby="enameHelp">
                                    </div>
                                <?php } ?>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp">
                                    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" name="password" id="password">
                                </div>
                                <?php if (isset($key) && $key != 'login') { ?>
                                    <div class="form-group">
                                        <label for="cpassword">Confirm Password</label>
                                        <input type="password" class="form-control" name="cpassword" id="cpassword">
                                    </div>
                                <?php } ?>

                                <button type="submit" class="btn btn-primary" style="display:block;margin:0 auto">Submit</button>
                                <div class="col-sm-12 text-center">
                                    <?php if (isset($key) && $key != 'login') { ?>
                                        <a href="<?php echo base_url('Login') ?>" class="col-sm-12 text-center">Already have an account? Login</a>

                                    <?php } else { ?>
                                        <a href="<?php echo base_url('Login/register') ?>" class="col-sm-12 text-center">Register</a>
                                    <?php } ?>
                                </div>
                            </form>
                        </div>
                    <?php } else { ?>
                        <div class="col-sm-6 p-4 ml-auto mr-auto">
                            <h4 class="text-center">Enter OTP</h4>
                            <form method="POST" id='registrationForm' action="<?php echo base_url('Login/verifyOtp')  ?>">

                                <div class="form-group">
                                    <label for="otp">OTP</label>
                                    <input type="text" class="form-control" name="otp" id="otp" aria-describedby="emailHelp">
                                </div>


                                <button type="submit" class="btn btn-success" style="display:block;margin:0 auto">Verify</button>

                            </form>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
</section>