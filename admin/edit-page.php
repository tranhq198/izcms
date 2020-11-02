<?php include('../includes/header.php');?>
<?php include('../includes/mysqli-connect.php');?>
<?php include('../includes/functions.php');?>

<?php
// Kiem tra xem nguoi dung co duoc vao trang admin hay khong?
admin_access();

// Kiem tra gia tri cua bien pid tu $_GET
if(isset($_GET['pid']) && filter_var($_GET['pid'], FILTER_VALIDATE_INT, array('min_range' =>1))) {
    $pid = $_GET['pid'];
    // Neu pid ton tai, bat dau xu ly form
    if($_SERVER['REQUEST_METHOD'] == 'POST') { // Gia tri tri ton tai, xu ly form.
        $errors = array();
        if(empty($_POST['page_name'])) {
            $errors[] = 'page_name';
        } else {
            $page_name = mysqli_real_escape_string($dbc,strip_tags($_POST['page_name']));
        }

        if(isset($_POST['category']) && filter_var($_POST['category'], FILTER_VALIDATE_INT, array('min_range' => 1))) {
            $cat_id= $_POST['category'];
        } else {
            $errors[] = "category";
        }

        if(isset($_POST['position']) && filter_var($_POST['position'], FILTER_VALIDATE_INT, array('min_range' => 1))) {
            $position = $_POST['position'];
        } else {
            $errors[] = "position";
        }

        if(empty($_POST['content'])) {
            $errors[] = 'content';
        } else {
            $content = mysqli_real_escape_string($dbc,$_POST['content']);
        }

        if(empty($errors)) {
            // Neu khong co loi xay ra, bat dau chen du lieu vao CSDL
            $query = "UPDATE pages SET ";
            $query .= " page_name = '{$page_name}', ";
            $query .= " cat_id = {$cat_id}, ";
            $query .= " position = {$position}, ";
            $query .= " content = '{$content}', ";
            $query .= " user_id = 1, ";
            $query .= " post_on = NOW() ";
            $query .= " WHERE page_id = {$pid} LIMIT 1";
            $result = mysqli_query($dbc,$query);
            confirm_query($result, $query);
            if(mysqli_affected_rows($dbc) == 1) {
                $messages = "<p class='success'>The page was edited successfully.</p>";
            } else {
                $messages = "<p class='warning'>The page could not be edited due to a system error</p>";
            }
        } else {
            $messages = "<p class='warning'>Please fill in all the required fields</p>";
        }
    } // END main IF submit condition
} else {
    // Neu pid khong ton tai, thi redirect nguoi dung ve trang admin
    redirect_to('admin/view_pages.php');
}
?>
    <div id="content">
        <?php
        // Chon page trong csdl de hien thi ra trinh duyet
        $query = "SELECT * FROM pages WHERE page_id = {$pid}";
        $result = mysqli_query($dbc, $query);
        confirm_query($result, $query);
        if(mysqli_num_rows($result) == 1) {
            // Neu co page tra ve
            $page = mysqli_fetch_array($result, MYSQLI_ASSOC);
        } else {
            // Neu khong co page tra ve
            $messages = "<p class='warning'>The page does not exist.</p>";
        }
        ?>
        <h2>Edit page: <?php if(isset($page['page_name'])) echo $page['page_name']; ?></h2>
        <?php if(!empty($messages)) echo $messages; ?>
        <form id="edit_page" action="" method="post">
            <fieldset>
                <legend>Add a Page</legend>
                <div>
                    <label for="page">Page Name: <span class="required">*</span>
                        <?php if(isset($errors) && in_array('page_name', $errors)) {echo "<p class='warning'>Please fill in the page name</p>";}?>
                    </label>
                    <input type="text" name="page_name" id="page_name" value="<?php if(isset($page['page_name'])) echo $page['page_name']; ?>" size="20" maxlength="80" tabindex="1" />
                </div>

                <div>
                    <label for="category">All categories: <span class="required">*</span>
                        <?php if(isset($errors) && in_array('category', $errors)) { echo "<p class='warning'>Please pick a category</p>"; }?>
                    </label>

                    <select name="category">
                        <option>Select Category</option>
                        <?php
                        $query = "SELECT cat_id, cat_name FROM categories ORDER BY position ASC";
                        $result = mysqli_query($dbc, $query);
                        if(mysqli_num_rows($result) > 0) {
                            while($cats = mysqli_fetch_array($result, MYSQLI_NUM)) {
                                echo "<option value='{$cats[0]}'";
                                if(isset($page['cat_id']) && ($page['cat_id'] == $cats[0])) echo "selected='selected'";
                                echo ">".$cats[1]."</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div>
                    <label for="position">Position: <span class="required">*</span>
                        <?php if(isset($errors) && in_array('position', $errors)) { echo "<p class='warning'>Please pick a position</p>";}?>
                    </label>
                    <select name="position">
                        <?php
                        $query = "SELECT count(page_id) AS count FROM pages";
                        $result = mysqli_query($dbc,$query); confirm_query($result, $query);
                        if(mysqli_num_rows($result) == 1) {
                            list($num) = mysqli_fetch_array($result, MYSQLI_NUM);
                            for($i=1; $i<=$num+1; $i++) { // Tao vong for de ra option, cong them 1 gia tri cho position
                                echo "<option value='{$i}'";
                                if(isset($page['position']) && $page['position'] == $i) echo "selected='selected'";
                                echo ">".$i."</otption>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div>
                    <label for="page-content">Page Content: <span class="required">*</span>
                        <?php if(isset($errors) && in_array('content', $errors)) {echo "<p class='warning'>Please fill in the content</p>";}?>
                    </label>
                    <textarea name="content" cols="50" rows="20"><?php if(isset($page['content'])) echo htmlentities($page['content'], ENT_COMPAT, 'UTF-8'); ?></textarea>
                </div>
            </fieldset>
            <p><input type="submit" name="submit" value="Save Changes" /></p>
        </form>

    </div><!--end content-->
<?php include('../includes/footer.php'); ?>