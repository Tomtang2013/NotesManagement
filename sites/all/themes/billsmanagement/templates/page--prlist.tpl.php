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
            ?>
        </div>
        <div id="pr_worl_list_content_right" class="pr_content_right">
            <div class="pr_workingstage_connent">
                <div id="pr_right_content">
                    <?php
                    $tabs = menu_local_tasks();
                    if ($tabs['tabs']['count'] >= 1):
                    ?><ul class="pr360-tabs"><?php print render($tabs['tabs']['output']); ?></ul><?php endif; ?>
                    <?php print_r($messages); ?>
                    <?php if ($page['dashboard_content'])
                            print render($page['dashboard_content']) ?>
<?php if ($page['content'])
                                print render($page['content']) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hidden_box">
                    <div id="review_description">
                        <div id="error_message" class="messages error"></div>
                        <div id="status_message" class="messages status"></div>
                        <div>
                            <label>Description:</label>
                            <textarea id="description" class="description" name="description"></textarea>
                            <div>Please enter the description for this reveiw.</div>
                            <input type="button" id="add_btn" class="form-submit" onclick="add_new_review()" value="Add Review" />
                            <input type="button" id="cancel_btn" class="form-submit" onclick="add_new_review_cancel()" value="Cancel" />
                            <img class="loading_img" title="loading..." style="width: 25px; height: 25px" src="<?php print base_path() . drupal_get_path('theme', 'performancereview') . '/images/loading.gif' ?>">
                            <input type="hidden" id="base_path" value="<?php print base_path(); ?>" />
                        </div>
                    </div>
                </div>
                <div id="pr_work_list_footer" class="pr_footer">
<?php require_once 'footer.tpl.php'; ?>
    </div>
</div>
