<?php
/**
 * @file
 * Default theme implementation to display a single Drupal page.
 *
 * Available variables:
 *
 * General utility variables:
 * - $base_path: The base URL path of the Drupal installation. At the very
 *   least, this will always default to /.
 * - $directory: The directory the template is located in, e.g. modules/system
 *   or themes/garland.
 * - $is_front: TRUE if the current page is the front page.
 * - $logged_in: TRUE if the user is registered and signed in.
 * - $is_admin: TRUE if the user has permission to access administration pages.
 *
 * Site identity:
 * - $front_page: The URL of the front page. Use this instead of $base_path,
 *   when linking to the front page. This includes the language domain or
 *   prefix.
 * - $logo: The path to the logo image, as defined in theme configuration.
 * - $site_name: The name of the site, empty when display has been disabled
 *   in theme settings.
 * - $site_slogan: The slogan of the site, empty when display has been disabled
 *   in theme settings.
 *
 * Navigation:
 * - $main_menu (array): An array containing the Main menu links for the
 *   site, if they have been configured.
 * - $secondary_menu (array): An array containing the Secondary menu links for
 *   the site, if they have been configured.
 * - $breadcrumb: The breadcrumb trail for the current page.
 *
 * Page content (in order of occurrence in the default page.tpl.php):
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title: The page title, for use in the actual HTML content.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 * - $messages: HTML for status and error messages. Should be displayed
 *   prominently.
 * - $tabs (array): Tabs linking to any sub-pages beneath the current page
 *   (e.g., the view and edit tabs when displaying a node).
 * - $action_links (array): Actions local to the page, such as 'Add menu' on the
 *   menu administration interface.
 * - $feed_icons: A string of all feed icons for the current page.
 * - $node: The node object, if there is an automatically-loaded node
 *   associated with the page, and the node ID is the second argument
 *   in the page's path (e.g. node/12345 and node/12345/revisions, but not
 *   comment/reply/12345).
 *
 * Regions:
 * - $page['help']: Dynamic help text, mostly for admin pages.
 * - $page['content']: The main content of the current page.
 * - $page['sidebar_first']: Items for the first sidebar.
 * - $page['sidebar_second']: Items for the second sidebar.
 * - $page['header']: Items for the header region.
 * - $page['footer']: Items for the footer region.
 *
 * @see template_preprocess()
 * @see template_preprocess_page()
 * @see template_process()
 */
?>

<div id="pr_work_list_page" class="pr_page">
    <?php require_once 'header.tpl.php'; ?>
    <div id="pr_worl_list_content" class="pr_content">
        <div id="pr_worl_list_content_left" class="pr_content_left">
            <?php
            $navigation_tree = menu_tree(variable_get('menu_main_links_source', 'navigation'));
            print drupal_render($navigation_tree);
            $review = load_editing_review();
            ?>
        </div>
        <div id="pr_worl_list_content_right" class="pr_content_right">
            <div class="pr_workingstage_connent">
                <div id="pr_right_content">
                    <?php if (!is_null($review)): ?>
                        <div>
                            <label>Description:</label>
                            <input id="edit_review_description" style="width: 250px;" name="<?php print $review->active ?>" value="<?php print $review->description ?>" />
        <!--                        <input type="button" id="update_description" name="<?php print $review->prid ?>" class="form-submit" onClick="updateStatus()" value ="Change Description" />-->
                            <input type="button" id="change_status" style="display: none" class="form-submit" onClick="changeStatus()" value ="Start Review" />
                            <img class="loading_img" id="status_loading_img_desc" title="loading..." style="width: 25px; height: 25px" src="<?php print base_path() . drupal_get_path('theme', 'performancereview') . '/images/loading.gif' ?>" />
                            <input type="hidden" id="base_path" value="<?php print base_path(); ?>" />
                        </div>
                    <?php endif; ?>
                    <?php
                        $tabs = menu_local_tasks();
                        if ($tabs['tabs']['count'] >= 1):
                    ?><ul class="pr360-tabs"><?php print render($tabs['tabs']['output']); ?></ul><?php endif; ?>
                            <div>
                        <?php
//                    $review_id = isset($_SESSION['pr_list_id']) ? $_SESSION['pr_list_id'] : (variable_get('editing_review_id') ? variable_get('editing_review_id') : 0);
//                    $flag = is_preparation_done($review_id);
//                    if ($flag):
                        ?>
                            <input type="hidden" id="base_path" value="<?php print base_path(); ?>" />
                            <input type="button" id="change_status" style="display: none" class="form-submit" onClick="changeStatus()" value ="Start Review">
                        <?php // endif; ?>
                        </div>
                    <?php print_r($messages); ?>
                            <div>
                        <?php
//                    if ($page['content'])
//                        print drupal_render($page['content']);
                            if ($page['new_review'])
                                print drupal_render($page['new_review']);
                        ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hidden_box">
                <div id="start_review">
                    <div id="error_message" class="messages error"></div>
                    <div id="status_message" class="messages status"></div>
                    <div>
                        <label>Do you want to start this review? Once the review has been started, you couldnot configure the timeslot and participants any more.</label>
                        <input type="button" id="start_btn" class="form-submit" onclick="start_review()" value="Yes" />
                        <input type="button" id="cancel_btn" class="form-submit" onclick="start_review_cancel()" value="No" />
                        <img class="loading_img" id="status_loading_img" title="loading..." style="width: 25px; height: 25px" src="<?php print base_path() . drupal_get_path('theme', 'performancereview') . '/images/loading.gif' ?>">
                        <input type="hidden" id="base_path" value="<?php print base_path(); ?>" />
                    </div>
                </div>
            </div>
            <div id="pr_work_list_footer" class="pr_footer">
            <?php require_once 'footer.tpl.php'; ?>
        </div>
    </div>
</div>
