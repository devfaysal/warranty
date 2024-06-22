<?php
if (isset($_POST) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $body = array(
        'name' => sanitize_text_field($_POST['w_name']),
        'serial' => sanitize_text_field($_POST['w_serial']),
        'mobile_number' => sanitize_text_field($_POST['w_mobile_number']),
        'mobile_operator' => sanitize_text_field($_POST['w_mobile_operator']),
        'connection_type' => sanitize_text_field($_POST['w_connection_type']),
    );
    $args = array(
        'body' => $body,
    );
    $response = wp_remote_post('https://office.surovigroup.net:99/api/product-submission', $args);
    $responseObject = json_decode($response['body']);
    $validation_errors = $responseObject->validation_errors;
    $data = $responseObject->data;
}
?>
<?php get_header(); ?>

<div id="main-content">
    <div class="container">
        <div id="content-area" class="clearfix">
            <div class="product-submission-card">
                <div class="product-submission-header">
                    <h1 class="product-submission-title">
                        সুরভী এন্টারপ্রাইজ অনলাইন ওয়ারেন্টি রেজিস্ট্রেশন
                    </h1>
                    <h2 class="product-submission-subtitle">
                        ওয়ারেন্টি রেজিস্ট্রেশন করে জিতে নিন আপনার মোবাইলে সারপ্রাইজ গিফট
                    </h2>
                </div>
                <form method="POST" action="<?php bloginfo('url'); ?>/product-submission/">
                    <div class="form-group">
                        <label class="form-label" for="name">আপনার নাম <span>*</span></label>
                        <input class="form-control p-2" type="text" id="name" value="<?php echo (isset($data) && $data->name) ? $data->name : '' ?>" name="w_name">
                        <?php if (isset($validation_errors) && $validation_errors->name) : ?>
                            <p class="validation-error"><?php echo $validation_errors->name[0] ?></p>
                        <?php endif ?>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="serial">সিরিয়াল নম্বর <a target="_blank" href="https://surovienterprise.net/where-to-find-serial-number/">(কোথায় পাবেন)</a> <span>*</span></label>
                        <input class="form-control" type="number" id="serial" value="<?php echo (isset($data) && $data->serial) ? $data->serial : '' ?>" name="w_serial">
                        <?php if (isset($validation_errors) && $validation_errors->serial) : ?>
                            <p class="validation-error"><?php echo $validation_errors->serial[0] ?></p>
                        <?php endif ?>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="mobile_number">মোবাইল নম্বর <span>*</span></label>
                        <input class="form-control" type="number" id="mobile_number" value="<?php echo (isset($data) && $data->mobile_number) ? $data->mobile_number : '' ?>" name="w_mobile_number">
                        <?php if (isset($validation_errors) && $validation_errors->mobile_number) : ?>
                            <p class="validation-error"><?php echo $validation_errors->mobile_number[0] ?></p>
                        <?php endif ?>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="mobile_operator">অপারেটর <span>*</span></label>
                        <select class="form-control" id="mobile_operator" name="w_mobile_operator">
                            <option value="">অপারেটর নির্বাচন করুন</option>
                            <option value="GP" <?php if (isset($data) && $data->mobile_operator == 'GP') : ?> selected <?php endif ?>>গ্রামীনফোন</option>
                            <option value="BL" <?php if (isset($data) && $data->mobile_operator == 'BL') : ?> selected <?php endif ?>>বাংলালিংক</option>
                            <option value="AT" <?php if (isset($data) && $data->mobile_operator == 'AT') : ?> selected <?php endif ?>>এয়ারটেল</option>
                            <option value="RB" <?php if (isset($data) && $data->mobile_operator == 'RB') : ?> selected <?php endif ?>>রবি</option>
                            <option value="TT" <?php if (isset($data) && $data->mobile_operator == 'TT') : ?> selected <?php endif ?>>টেলিটক</option>
                            <option value="ST" <?php if (isset($data) && $data->mobile_operator == 'ST') : ?> selected <?php endif ?>>স্কিটো</option>
                        </select>
                        <?php if (isset($validation_errors) && $validation_errors->mobile_operator) : ?>
                            <p class="validation-error"><?php echo $validation_errors->mobile_operator[0] ?></p>
                        <?php endif ?>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="connection_type">সিমের ধরণ <span>*</span></label>
                        <select class="form-control" id="connection_type" name="w_connection_type">
                            <option value="prepaid" <?php if (isset($data) && $data->connection_type == 'prepaid') : ?> selected <?php endif ?>>প্রিপেইড</option>
                            <option value="postpaid" <?php if (isset($data) && $data->connection_type == 'postpaid') : ?> selected <?php endif ?>>পোস্টপেইড</option>
                        </select>
                        <?php if (isset($validation_errors) && $validation_errors->connection_type) : ?>
                            <p class="validation-error"><?php echo $validation_errors->connection_type[0] ?></p>
                        <?php endif ?>
                    </div>
                    <div>
                        <button class="product-submission-button" type="submit">
                            Submit
                        </button>
                    </div>
                </form>
                <p class="product-submission-disclaimer">
                    মোবাইলে সারপ্রাইজ গিফট পেতে হলে অবশ্যই সঠিক ভাবে আপনার মোবাইল নম্বর দিতে হবে। সেই সাথে মোবাইল অপারেটর ও প্রিপেইড/পোস্টপেইড সঠিকভাবে নির্বাচন করতে হবে। আপনি এই তিনটি জিনিস যদি কোনো ভাবে ভুল করেন আর সারপ্রাইজ গিফট না পান, তার জন্য সুরভী এন্টারপ্রাইজ দায়ী থাকবে না।
                </p>
            </div>
        </div>
    </div>
</div>

<?php
get_footer();
