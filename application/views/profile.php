<section class="registration-container">
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="row">

                    <div class="col-sm-6 registration-left">
                        <?php
                        if(isset($user[0]['image_path']) && !empty($user[0]['image_path'])){
                            $path = base_url() . base64_decode($user[0]['image_path']);
                            
                        }else{
                            $path =  base_url('assets/images/man.png');
                        }
                        ?>
                        <div class="user-left-img"  style="background:url(<?php echo $path ?>)">
                        </div>
                    </div>
                    <div class="col-sm-6 p-4">
                        <h4>Update Profile</h4>
                        <form method="POST" enctype="multipart/form-data" action="<?php echo base_url('Main/updateUser')?>">

                            <div class="form-group">
                                <label for="ename">Name</label>
                                <input type="text" class="form-control" name="ename" id="ename" required value="<?php echo isset($user[0]['name']) ? base64_decode($user[0]['name']) :'' ?>">
                            </div>                           

                            <div class="form-group">
                                <label for="password">User Image</label>
                                <input type="file" class="form-control" name="file" id="img">
                            </div>


                            <button type="submit" class="btn btn-primary" style="display:block;margin:0 auto">Submit</button>

                        </form>
                    </div>

                </div>
            </div>
        </div>
</section>