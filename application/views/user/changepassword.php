                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

                    <div class="row">
                        <div class="col-lg-8">
                            <?= $this->session->flashdata('message'); ?>
                        </div>
                    </div>

                    <!-- Content pada Halaman->Begin -->

                    <div class="row">
                        <div class="col-lg-8">

                            <form action="<?= base_url('user/changepassword'); ?>" method="post">
                                
                                <div class="form-group row">
                                    <label for="current_password" class="col-sm-3 col-form-label">Current Password</label>
                                    <div class="col-sm-8">
                                        <input type="password" class="form-control" id="current_password" name="current_password">
                                        <?= form_error('current_password', '<small class="text-danger pl-3">', '</small>');?>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="password1" class="col-sm-3 col-form-label">New Password</label>
                                    <div class="col-sm-8">
                                        <input type="password" class="form-control" id="password1" name="password1">
                                        <?= form_error('password1', '<small class="text-danger pl-3">', '</small>');?>
                                    </div>
                                </div>
                        
                                <div class="form-group row">
                                    <label for="password2" class="col-sm-3 col-form-label">Retype Password</label>
                                    <div class="col-sm-8">
                                        <input type="password" class="form-control" id="password2" name="password2">
                                        <?= form_error('password2', '<small class="text-danger pl-3">', '</small>');?>
                                    </div>
                                </div>
                    
                                <div class="form-group row justify-content-end">
                                    <div class="col-sm-9">
                                       <button type="submit" class="btn btn-primary">Change Password</button>
                                    </div>
                                </div>

                            </form>

                        </div>
                    </div>

                    <!-- Content pada Halaman->End -->

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->