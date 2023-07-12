    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

        <div class="container">
            <div class="row">
                <div class="col-lg-10">
                    <form method="post" action="<?= base_url('admin/roleedit/'.$user_submenu_edit->id); ?>" class="lg-10">
                        <div class="row mb-3">
                            <label for="title" class="col-sm-2 col-form-label">Title</label>
                            <div class="col-sm-10">
                                <input class="form-control" type="text" name="title" id="title" value="<?php echo $user_submenu_edit->title; ?>" required>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="menu_id" class="col-sm-2 col-form-label">Menu</label>
                            <div class="col-sm-10">
                                <select name="menu_id" id="menu_id" class="form-control">
                                    <option value="">Select Menu</option>
                                    <?php foreach ($menu as $m) : ?>
                                        <option value="<?= $m['id']; ?>"><?= $m['menu']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                            <div class="row mb-3">
                                <label for="url" class="col-sm-2 col-form-label">URL</label>
                                <div class="col-sm-10">
                                <input class="form-control" type="text" name="url" id="url" value="<?php echo $user_submenu_edit->url; ?>" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="icon" class="col-sm-2 col-form-label">Icon</label>
                                <div class="col-sm-10">
                                <input class="form-control" type="text" name="icon" id="icon" value="<?php echo $user_submenu_edit->icon; ?>" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-10 offset-sm-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_active" value="1" name="is_active" checked>
                                    <label class="form-check-label" for="is_active">
                                    Active
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <a href="<?= base_url('menu/submenu'); ?>" class="btn btn-danger">Cancel</a>
                        </div>
                    </form>
                </div>

            </div>
        </div>
            


    </div>
    <!-- /.container-fluid -->

</div>
<!-- End of Main Content -->