                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

                    <!-- Table content Menu Management -->
                    <div class="row justify-content-center">
                        <div class="col-lg-7">
                            <form method="post" action="<?= base_url('menu/edit/'.$user_menu_edit->id); ?>">
                                <label for="menu">Menu:</label>
                                <input type="text" name="menu_edit" id="menu_edit" value="<?php echo $user_menu_edit->menu; ?>" required>
        
                                <button href="" type="submit" class="btn btn-success mr-1">Save</button>
                                <button href="<?= base_url('menu'); ?>" class="btn btn-danger">Cancel</button>
                            </form>
                            
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Menu</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    <?php foreach ($menu as $m) : ?>
                                    <tr>
                                        <th scope="row"><?= $i; ?></th>
                                        <td><?= $m['menu']; ?></td>
                                        <td>
                                            <a href="<?= base_url('menu/edit/'.$m['id']); ?>" class="badge badge-success">EDIT</a>
                                            <a href="" class="badge badge-danger">DELETE</a>
                                        </td>
                                    </tr>
                                    <?php $i++; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- End of Table content Menu Management -->
                    
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->