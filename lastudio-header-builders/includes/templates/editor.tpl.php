<?php

/**
 * Header Builder - Editor Template.
 *
 * @author LaStudio
 */

// don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit;
}

$frontend_components = LAHB_Helper::get_data_frontend_components();
$editor_components = LAHB_Helper::get_only_panels_from_settings($frontend_components);

// Desktop: Topbar settings
$desktopTopbarHidden = $editor_components['desktop-view']['topbar']['settings']['hidden_element'] ? 'true': 'false';
$desktopTopbarUniqueID = $editor_components['desktop-view']['topbar']['settings']['uniqueId'];
// Desktop: Row1 settings
$desktopRow1Hidden = $editor_components['desktop-view']['row1']['settings']['hidden_element'] ? 'true': 'false';
$desktopRow1UniqueID = $editor_components['desktop-view']['row1']['settings']['uniqueId'];
// Desktop: Row2 settings
$desktopRow2Hidden = $editor_components['desktop-view']['row2']['settings']['hidden_element'] ? 'true': 'false';
$desktopRow2UniqueID = $editor_components['desktop-view']['row2']['settings']['uniqueId'];
// Desktop: Row3 settings
$desktopRow3Hidden = $editor_components['desktop-view']['row3']['settings']['hidden_element'] ? 'true': 'false';
$desktopRow3UniqueID = $editor_components['desktop-view']['row3']['settings']['uniqueId'];

// Tablets: Topbar settings
$tabletsTopbarHidden = $editor_components['tablets-view']['topbar']['settings']['hidden_element'] ? 'true': 'false';
$tabletsTopbarUniqueID = $editor_components['tablets-view']['topbar']['settings']['uniqueId'];
// Tablets: Row1 settings
$tabletsRow1Hidden = $editor_components['tablets-view']['row1']['settings']['hidden_element'] ? 'true': 'false';
$tabletsRow1UniqueID = $editor_components['tablets-view']['row1']['settings']['uniqueId'];
// Tablets: Row2 settings
$tabletsRow2Hidden = $editor_components['tablets-view']['row2']['settings']['hidden_element'] ? 'true': 'false';
$tabletsRow2UniqueID = $editor_components['tablets-view']['row2']['settings']['uniqueId'];
// Tablets: Row3 settings
$tabletsRow3Hidden = $editor_components['tablets-view']['row3']['settings']['hidden_element'] ? 'true': 'false';
$tabletsRow3UniqueID = $editor_components['tablets-view']['row3']['settings']['uniqueId'];

// Mobiles: Topbar settings
$mobilesTopbarHidden = $editor_components['mobiles-view']['topbar']['settings']['hidden_element'] ? 'true': 'false';
$mobilesTopbarUniqueID = $editor_components['mobiles-view']['topbar']['settings']['uniqueId'];
// Mobiles: Row1 settings
$mobilesRow1Hidden = $editor_components['mobiles-view']['row1']['settings']['hidden_element'] ? 'true': 'false';
$mobilesRow1UniqueID = $editor_components['mobiles-view']['row1']['settings']['uniqueId'];
// Mobiles: Row2 settings
$mobilesRow2Hidden = $editor_components['mobiles-view']['row2']['settings']['hidden_element'] ? 'true': 'false';
$mobilesRow2UniqueID = $editor_components['mobiles-view']['row2']['settings']['uniqueId'];
// Mobiles: Row3 settings
$mobilesRow3Hidden = $editor_components['mobiles-view']['row3']['settings']['hidden_element'] ? 'true': 'false';
$mobilesRow3UniqueID = $editor_components['mobiles-view']['row3']['settings']['uniqueId'];

$class_frontend_builder = LAHB_Helper::is_frontend_builder() ? ' lahb-frontend-builder' : '';

?>

<!-- lastudio header builder wrap -->
<div class="lastudio-backend-header-builder-wrap wp-clearfix<?php echo esc_attr( $class_frontend_builder ); ?>" id="lastudio-backend-header-builder">
    <?php

    $export_current_header_link = add_query_arg(array(
        'action' => 'lahb_ajax_action',
        'router' => 'export_header',
        'nonce'  => wp_create_nonce( 'lahb-nonce' )
    ), admin_url( 'admin-ajax.php' ));



    if(is_admin() && isset($_REQUEST['prebuild_header'])){
        $prebuild_header_key = esc_attr($_REQUEST['prebuild_header']);
        $all_existing = LAHB_Helper::get_prebuild_headers();
        if(LAHB_Helper::is_prebuild_header_exists($prebuild_header_key) && !empty($all_existing[$prebuild_header_key]['name'])){

            $export_current_header_link = add_query_arg(array(
                'prebuild_header' => $prebuild_header_key
            ), $export_current_header_link);

            echo '<h4>'. esc_html__('You are editing ', 'lastudio-header-builder') .'<strong>'.$all_existing[$prebuild_header_key]['name'].'</strong> <a class="button button-primary" href="'.esc_url(admin_url( 'admin.php?page=lastudio_header_builder_setting' )).'">'. esc_html__('Complete edit') .'</a></h4>';
        }
    }
    ?>

    <div class="lahb-actions">

        <a href="#" id="lahb-publish" class="button button-primary"><i class="dashicons dashicons-external"></i><?php esc_html_e( 'Publish', 'lastudio-header-builder' ); ?></a>

        <?php if ( LAHB_Helper::is_frontend_builder() ) : ?>
            <?php
            $option_page_url = admin_url( 'admin.php?page=lastudio_header_builder_setting' );
            if(!empty($_GET['prebuild_header'])){
                $option_page_url = add_query_arg(array('prebuild_header' => esc_attr($_GET['prebuild_header'])), $option_page_url);
            }
            ?>
            <div class="lahb-action-collapse lahb-tooltip lahb-open" data-tooltip="<?php esc_html_e( 'Toggle', 'lastudio-header-builder' ); ?>"><i class="dashicons dashicons-download"></i></div>
            <a href="<?php echo esc_url($option_page_url) ?>" class="btob-button lahb-tooltip" data-tooltip="<?php esc_html_e( 'Backend editor', 'lastudio-header-builder' ) ?>"><i class="dashicons dashicons-arrow-left-alt"></i></a>
        <?php else : ?>
        <?php
            $option_page_url = admin_url( 'admin.php?page=lastudio_header_builder' );
            if(!empty($_GET['prebuild_header'])){
                $option_page_url = add_query_arg(array('prebuild_header' => esc_attr($_GET['prebuild_header'])), $option_page_url);
            }

        ?>
            <a href="<?php echo esc_url($option_page_url) ?>" id="lahb-f-editor" class="button button-primary"><i class="dashicons dashicons-arrow-right-alt"></i><?php esc_html_e( 'Front-end Header Builder', 'lastudio-header-builder' ); ?></a>
        <?php endif; ?>

        <a href="#" id="lahb-vertical-header" class="button" data-header_type="horizontal"><i class="fa fa-bars"></i><span><?php esc_html_e( 'Vertical Header', 'lastudio-header-builder' ); ?></span></a>
        <a href="#" id="lahb-predefined" class="button lahb-full-modal-btn" data-modal-target="prebuilds-modal-content"><i class="fa fa-hdd-o"></i><?php esc_html_e( 'Pre-defined Headers', 'lastudio-header-builder' ) ?></a>

        <div class="lahb-full-modal" data-modal="prebuilds-modal-content">
            <i class="lahb-full-modal-close dashicons dashicons-no-alt"></i>
            <h4><?php esc_html_e( 'Pre-defined Headers', 'lastudio-header-builder' ); ?></h4>
            <div class="lahb-predefined-modal-contents wp-clearfix">
                <?php include LAHB_Helper::get_file( 'includes/prebuilds/prebuilds.php' ); ?>
            </div>
        </div>

        <a href="#" id="lahb-cleardata" class="button la-warning-primary"><i class="fa fa-ban"></i><?php esc_html_e( 'Clear Data', 'lastudio-header-builder' ) ?></a>

        <!-- import export -->
        <div class="lahb-import-export">
            <a id="lahb-saveastpl" class="button button-primary" href="#"><i class="fa fa-floppy-o"></i><?php esc_html_e( 'Save as Header Template', 'lastudio-header-builder' ) ?></a>
            <a id="lahb-export" class="button" href="<?php echo esc_url( $export_current_header_link ); ?>"><i class="fa fa-cloud-upload"></i><?php esc_html_e( 'Export Header', 'lastudio-header-builder' ) ?></a>
            <div class="lahb-import-wrap">
                <a class="button lahb-full-modal-btn" href="#" data-modal-target="import-modal-content"><i class="fa fa-cloud-download"></i><?php esc_html_e( 'Import Header', 'lastudio-header-builder' ) ?></a>
                <input type="file" id="lahb-import">
            </div>

        </div> <!-- end .lahb-import-export -->

    </div><!-- .lahb-actions -->

    <!-- tabs -->
    <div class="lahb-tabs-wrap">

        <ul class="lahb-tabs-list wp-clearfix">
            <li class="lahb-tab w-active">
                <a href="#desktop-view" id="lahb-desktop-tab">
                    <i class="fa fa-desktop" aria-hidden="true"></i>
                    <span><?php esc_html_e( 'Desktop', 'lastudio-header-builder' ); ?></span>
                </a>
            </li>
            <li class="lahb-tab">
                <a href="#tablets-view" id="lahb-tablets-tab">
                    <i class="dashicons dashicons-tablet" aria-hidden="true"></i>
                    <span><?php esc_html_e( 'Tablets', 'lastudio-header-builder' ); ?></span>
                </a>
            </li>
            <li class="lahb-tab">
                <a href="#mobiles-view" id="lahb-mobiles-tab">
                    <i class="dashicons dashicons-smartphone" aria-hidden="true"></i>
                    <span><?php esc_html_e( 'Mobiles', 'lastudio-header-builder' ); ?></span>
                </a>
            </li>
        </ul> <!-- end .lahb-tabs-list -->

        <div class="lahb-tabs-panels">

            <!-- desktop panel -->
            <div class="lahb-tab-panel lahb-desktop-panel" id="desktop-view">

                <!-- topbar -->
                <div class="lahb-columns" data-columns="topbar">
                    <div class="lahb-elements-item" data-element="header-area" data-unique-id="<?php echo '' . $desktopTopbarUniqueID ?>" data-hidden_element="<?php echo '' . $desktopTopbarHidden; ?>">
                        <span class="lahb-tooltip tooltip-on-top" data-tooltip="Hide">
                            <i class="lahb-control lahb-hide-btn fa fa-eye"></i>
                        </span>
                        <span class="lahb-tooltip tooltip-on-top" data-tooltip="Settings">
                            <i class="lahb-control lahb-edit-btn fa fa-cog"></i>
                        </span>
                    </div>
                    <span class="lahb-element-name"><?php esc_html_e( 'Topbar Area', 'lastudio-header-builder' ); ?></span>
                    <div class="lahb-col col-left wp-clearfix" data-align-col="left">
                        <div class="lahb-elements-place wp-clearfix">
                            <?php echo LAHB_Helper::getCellComponents($editor_components, 'desktop-view', 'topbar', 'left'); ?>
                        </div>
                        <a href="#" class="w-add-element lahb-tooltip tooltip-on-top" data-align-col="left" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $desktopTopbarUniqueID ?>left" class="w-edit-column lahb-tooltip tooltip-on-top" data-align-col="left" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                    <div class="lahb-col col-center wp-clearfix" data-align-col="center">
                        <div class="lahb-elements-place wp-clearfix">
                            <?php echo LAHB_Helper::getCellComponents($editor_components, 'desktop-view', 'topbar', 'center'); ?>
                        </div>
                        <a href="#" class="w-add-element lahb-tooltip tooltip-on-top" data-align-col="center" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $desktopTopbarUniqueID ?>center" class="w-edit-column lahb-tooltip tooltip-on-top" data-align-col="center" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                    <div class="lahb-col col-right wp-clearfix" data-align-col="right">
                        <div class="lahb-elements-place wp-clearfix">
                            <?php echo LAHB_Helper::getCellComponents($editor_components, 'desktop-view', 'topbar', 'right'); ?>
                        </div>
                        <a href="#" class="w-add-element lahb-tooltip tooltip-on-top" data-align-col="right" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $desktopTopbarUniqueID ?>right" class="w-edit-column lahb-tooltip tooltip-on-top" data-align-col="right" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                </div> <!-- end lahb-columns -->

                <!-- header area row 1 -->
                <div class="lahb-columns" data-columns="row1">
                    <div class="lahb-elements-item" data-element="header-area" data-unique-id="<?php echo '' . $desktopRow1UniqueID ?>" data-hidden_element="<?php echo '' . $desktopRow1Hidden; ?>">
                        <span class="lahb-tooltip tooltip-on-top" data-tooltip="Hide">
                            <i class="lahb-control lahb-hide-btn fa fa-eye"></i>
                        </span>
                        <span class="lahb-tooltip tooltip-on-top" data-tooltip="Settings">
                            <i class="lahb-control lahb-edit-btn fa fa-cog"></i>
                        </span>
                    </div>
                    <span class="lahb-element-name"><?php esc_html_e( 'Header Area Row 1', 'lastudio-header-builder' ); ?></span>
                    <div class="lahb-col col-left wp-clearfix" data-align-col="left">
                        <div class="lahb-elements-place wp-clearfix">
                            <?php echo LAHB_Helper::getCellComponents($editor_components, 'desktop-view', 'row1', 'left');?>
                        </div>
                        <a href="#" class="w-add-element lahb-tooltip tooltip-on-top" data-align-col="left" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $desktopRow1UniqueID ?>left" class="w-edit-column lahb-tooltip tooltip-on-top" data-align-col="left" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                    <div class="lahb-col col-center wp-clearfix" data-align-col="center">
                        <div class="lahb-elements-place wp-clearfix">
                            <?php echo LAHB_Helper::getCellComponents($editor_components, 'desktop-view', 'row1', 'center'); ?>
                        </div>
                        <a href="#" class="w-add-element lahb-tooltip tooltip-on-top" data-align-col="center" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $desktopRow1UniqueID ?>center" class="w-edit-column lahb-tooltip tooltip-on-top" data-align-col="center" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                    <div class="lahb-col col-right wp-clearfix" data-align-col="right">
                        <div class="lahb-elements-place wp-clearfix">
                            <?php echo LAHB_Helper::getCellComponents($editor_components, 'desktop-view', 'row1', 'right'); ?>
                        </div>
                        <a href="#" class="w-add-element lahb-tooltip tooltip-on-top" data-align-col="right" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $desktopRow1UniqueID ?>right" class="w-edit-column lahb-tooltip tooltip-on-top" data-align-col="right" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                </div> <!-- end lahb-columns -->

                <!-- header area row 2 -->
                <div class="lahb-columns" data-columns="row2">
                    <div class="lahb-elements-item" data-element="header-area" data-unique-id="<?php echo '' . $desktopRow2UniqueID ?>" data-hidden_element="<?php echo '' . $desktopRow2Hidden; ?>">
                        <span class="lahb-tooltip tooltip-on-top" data-tooltip="Hide">
                            <i class="lahb-control lahb-hide-btn fa fa-eye"></i>
                        </span>
                        <span class="lahb-tooltip tooltip-on-top" data-tooltip="Settings">
                            <i class="lahb-control lahb-edit-btn fa fa-cog"></i>
                        </span>
                    </div>
                    <span class="lahb-element-name"><?php esc_html_e( 'Header Area Row 2', 'lastudio-header-builder' ); ?></span>
                    <div class="lahb-col col-left wp-clearfix" data-align-col="left">
                        <div class="lahb-elements-place wp-clearfix">
                            <?php echo LAHB_Helper::getCellComponents($editor_components, 'desktop-view', 'row2', 'left'); ?>
                        </div>
                        <a href="#" class="w-add-element lahb-tooltip tooltip-on-top" data-align-col="left" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $desktopRow2UniqueID ?>left" class="w-edit-column lahb-tooltip tooltip-on-top" data-align-col="left" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                    <div class="lahb-col col-center wp-clearfix" data-align-col="center">
                        <div class="lahb-elements-place wp-clearfix">
                            <?php echo LAHB_Helper::getCellComponents($editor_components, 'desktop-view', 'row2', 'center'); ?>
                        </div>
                        <a href="#" class="w-add-element lahb-tooltip tooltip-on-top" data-align-col="center" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $desktopRow2UniqueID ?>center" class="w-edit-column lahb-tooltip tooltip-on-top" data-align-col="center" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                    <div class="lahb-col col-right wp-clearfix" data-align-col="right">
                        <div class="lahb-elements-place wp-clearfix">
                            <?php echo LAHB_Helper::getCellComponents($editor_components, 'desktop-view', 'row2', 'right'); ?>
                        </div>
                        <a href="#" class="w-add-element lahb-tooltip tooltip-on-top" data-align-col="right" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $desktopRow2UniqueID ?>right" class="w-edit-column lahb-tooltip tooltip-on-top" data-align-col="right" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                </div> <!-- end lahb-columns -->

                <!-- header area row 3 -->
                <div class="lahb-columns" data-columns="row3">
                    <div class="lahb-elements-item" data-element="header-area" data-unique-id="<?php echo '' . $desktopRow3UniqueID ?>" data-hidden_element="<?php echo '' . $desktopRow3Hidden; ?>">
                        <span class="lahb-tooltip tooltip-on-top" data-tooltip="Hide">
                            <i class="lahb-control lahb-hide-btn fa fa-eye"></i>
                        </span>
                        <span class="lahb-tooltip tooltip-on-top" data-tooltip="Settings">
                            <i class="lahb-control lahb-edit-btn fa fa-cog"></i>
                        </span>
                    </div>
                    <span class="lahb-element-name"><?php esc_html_e( 'Header Area Row 3', 'lastudio-header-builder' ); ?></span>
                    <div class="lahb-col col-left wp-clearfix" data-align-col="left">
                        <div class="lahb-elements-place wp-clearfix">
                            <?php echo LAHB_Helper::getCellComponents($editor_components, 'desktop-view', 'row3', 'left'); ?>
                        </div>
                        <a href="#" class="w-add-element lahb-tooltip tooltip-on-top" data-align-col="left" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $desktopRow3UniqueID ?>left" class="w-edit-column lahb-tooltip tooltip-on-top" data-align-col="left" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                    <div class="lahb-col col-center wp-clearfix" data-align-col="center">
                        <div class="lahb-elements-place wp-clearfix">
                            <?php echo LAHB_Helper::getCellComponents($editor_components, 'desktop-view', 'row3', 'center'); ?>
                        </div>
                        <a href="#" class="w-add-element lahb-tooltip tooltip-on-top" data-align-col="center" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $desktopRow3UniqueID ?>center" class="w-edit-column lahb-tooltip tooltip-on-top" data-align-col="center" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                    <div class="lahb-col col-right wp-clearfix" data-align-col="right">
                        <div class="lahb-elements-place wp-clearfix">
                            <?php echo LAHB_Helper::getCellComponents($editor_components, 'desktop-view', 'row3', 'right'); ?>
                        </div>
                        <a href="#" class="w-add-element lahb-tooltip tooltip-on-top" data-align-col="right" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $desktopRow3UniqueID ?>right" class="w-edit-column lahb-tooltip tooltip-on-top" data-align-col="right" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                </div> <!-- end lahb-columns -->

            </div> <!-- end .lahb-desktop-panel -->

            <!-- tablets panel -->
            <div class="lahb-tab-panel lahb-tablets-panel" id="tablets-view">

                <!-- topbar -->
                <div class="lahb-columns" data-columns="topbar">
                    <div class="lahb-elements-item" data-element="header-area" data-unique-id="<?php echo '' . $tabletsTopbarUniqueID ?>" data-hidden_element="<?php echo '' . $tabletsTopbarHidden; ?>">
                        <span class="lahb-tooltip tooltip-on-top" data-tooltip="Hide">
                            <i class="lahb-control lahb-hide-btn fa fa-eye"></i>
                        </span>
                        <span class="lahb-tooltip tooltip-on-top" data-tooltip="Settings">
                            <i class="lahb-control lahb-edit-btn fa fa-cog"></i>
                        </span>
                    </div>
                    <span class="lahb-element-name"><?php esc_html_e( 'Topbar Area', 'lastudio-header-builder' ); ?></span>
                    <div class="lahb-col col-left wp-clearfix" data-align-col="left">
                        <div class="lahb-elements-place wp-clearfix">
                            <?php echo LAHB_Helper::getCellComponents($editor_components, 'tablets-view', 'topbar', 'left'); ?>
                        </div>
                        <a href="#" class="w-add-element lahb-tooltip tooltip-on-top" data-align-col="left" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $tabletsTopbarUniqueID ?>left" class="w-edit-column lahb-tooltip tooltip-on-top" data-align-col="left" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                    <div class="lahb-col col-center wp-clearfix" data-align-col="center">
                        <div class="lahb-elements-place wp-clearfix">
                            <?php echo LAHB_Helper::getCellComponents($editor_components, 'tablets-view', 'topbar', 'center'); ?>
                        </div>
                        <a href="#" class="w-add-element lahb-tooltip tooltip-on-top" data-align-col="center" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $tabletsTopbarUniqueID ?>center" class="w-edit-column lahb-tooltip tooltip-on-top" data-align-col="center" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                    <div class="lahb-col col-right wp-clearfix" data-align-col="right">
                        <div class="lahb-elements-place wp-clearfix">
                            <?php echo LAHB_Helper::getCellComponents($editor_components, 'tablets-view', 'topbar', 'right'); ?>
                        </div>
                        <a href="#" class="w-add-element lahb-tooltip tooltip-on-top" data-align-col="right" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $tabletsTopbarUniqueID ?>right" class="w-edit-column lahb-tooltip tooltip-on-top" data-align-col="right" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                </div> <!-- end lahb-columns -->

                <!-- header area row 1 -->
                <div class="lahb-columns" data-columns="row1">
                    <div class="lahb-elements-item" data-element="header-area" data-unique-id="<?php echo '' . $tabletsRow1UniqueID ?>" data-hidden_element="<?php echo '' . $tabletsRow1Hidden; ?>">
                        <span class="lahb-tooltip tooltip-on-top" data-tooltip="Hide">
                            <i class="lahb-control lahb-hide-btn fa fa-eye"></i>
                        </span>
                        <span class="lahb-tooltip tooltip-on-top" data-tooltip="Settings">
                            <i class="lahb-control lahb-edit-btn fa fa-cog"></i>
                        </span>
                    </div>
                    <span class="lahb-element-name"><?php esc_html_e( 'Header Area Row 1', 'lastudio-header-builder' ); ?></span>
                    <div class="lahb-col col-left wp-clearfix" data-align-col="left">
                        <div class="lahb-elements-place wp-clearfix">
                            <?php echo LAHB_Helper::getCellComponents($editor_components, 'tablets-view', 'row1', 'left'); ?>
                        </div>
                        <a href="#" class="w-add-element lahb-tooltip tooltip-on-top" data-align-col="left" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $tabletsRow1UniqueID ?>left" class="w-edit-column lahb-tooltip tooltip-on-top" data-align-col="left" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                    <div class="lahb-col col-center wp-clearfix" data-align-col="center">
                        <div class="lahb-elements-place wp-clearfix">
                            <?php echo LAHB_Helper::getCellComponents($editor_components, 'tablets-view', 'row1', 'center'); ?>
                        </div>
                        <a href="#" class="w-add-element lahb-tooltip tooltip-on-top" data-align-col="center" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $tabletsRow1UniqueID ?>center" class="w-edit-column lahb-tooltip tooltip-on-top" data-align-col="center" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                    <div class="lahb-col col-right wp-clearfix" data-align-col="right">
                        <div class="lahb-elements-place wp-clearfix">
                            <?php echo LAHB_Helper::getCellComponents($editor_components, 'tablets-view', 'row1', 'right'); ?>
                        </div>
                        <a href="#" class="w-add-element lahb-tooltip tooltip-on-top" data-align-col="right" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $tabletsRow1UniqueID ?>right" class="w-edit-column lahb-tooltip tooltip-on-top" data-align-col="right" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                </div> <!-- end lahb-columns -->

                <!-- header area row 2 -->
                <div class="lahb-columns" data-columns="row2">
                    <div class="lahb-elements-item" data-element="header-area" data-unique-id="<?php echo '' . $tabletsRow2UniqueID ?>" data-hidden_element="<?php echo '' . $tabletsRow2Hidden; ?>">
                        <span class="lahb-tooltip tooltip-on-top" data-tooltip="Hide">
                            <i class="lahb-control lahb-hide-btn fa fa-eye"></i>
                        </span>
                        <span class="lahb-tooltip tooltip-on-top" data-tooltip="Settings">
                            <i class="lahb-control lahb-edit-btn fa fa-cog"></i>
                        </span>
                    </div>
                    <span class="lahb-element-name"><?php esc_html_e( 'Header Area Row 2', 'lastudio-header-builder' ); ?></span>
                    <div class="lahb-col col-left wp-clearfix" data-align-col="left">
                        <div class="lahb-elements-place wp-clearfix">
                            <?php echo LAHB_Helper::getCellComponents($editor_components, 'tablets-view', 'row2', 'left'); ?>
                        </div>
                        <a href="#" class="w-add-element lahb-tooltip tooltip-on-top" data-align-col="left" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $tabletsRow1UniqueID ?>left" class="w-edit-column lahb-tooltip tooltip-on-top" data-align-col="left" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                    <div class="lahb-col col-center wp-clearfix" data-align-col="center">
                        <div class="lahb-elements-place wp-clearfix">
                            <?php echo LAHB_Helper::getCellComponents($editor_components, 'tablets-view', 'row2', 'center'); ?>
                        </div>
                        <a href="#" class="w-add-element lahb-tooltip tooltip-on-top" data-align-col="center" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                    </div>
                    <div class="lahb-col col-right wp-clearfix" data-align-col="right">
                        <div class="lahb-elements-place wp-clearfix">
                            <?php echo LAHB_Helper::getCellComponents($editor_components, 'tablets-view', 'row2', 'right'); ?>
                        </div>
                        <a href="#" class="w-add-element lahb-tooltip tooltip-on-top" data-align-col="right" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $tabletsRow1UniqueID ?>right" class="w-edit-column lahb-tooltip tooltip-on-top" data-align-col="right" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                </div> <!-- end lahb-columns -->

                <!-- header area row 3 -->
                <div class="lahb-columns" data-columns="row3">
                    <div class="lahb-elements-item" data-element="header-area" data-unique-id="<?php echo '' . $tabletsRow3UniqueID ?>" data-hidden_element="<?php echo '' . $tabletsRow3Hidden; ?>">
                        <span class="lahb-tooltip tooltip-on-top" data-tooltip="Hide">
                            <i class="lahb-control lahb-hide-btn fa fa-eye"></i>
                        </span>
                        <span class="lahb-tooltip tooltip-on-top" data-tooltip="Settings">
                            <i class="lahb-control lahb-edit-btn fa fa-cog"></i>
                        </span>
                    </div>
                    <span class="lahb-element-name"><?php esc_html_e( 'Header Area Row 3', 'lastudio-header-builder' ); ?></span>
                    <div class="lahb-col col-left wp-clearfix" data-align-col="left">
                        <div class="lahb-elements-place wp-clearfix">
                            <?php echo LAHB_Helper::getCellComponents($editor_components, 'tablets-view', 'row3', 'left'); ?>
                        </div>
                        <a href="#" class="w-add-element lahb-tooltip tooltip-on-top" data-align-col="left" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $tabletsRow3UniqueID ?>left" class="w-edit-column lahb-tooltip tooltip-on-top" data-align-col="left" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                    <div class="lahb-col col-center wp-clearfix" data-align-col="center">
                        <div class="lahb-elements-place wp-clearfix">
                            <?php echo LAHB_Helper::getCellComponents($editor_components, 'tablets-view', 'row3', 'center'); ?>
                        </div>
                        <a href="#" class="w-add-element lahb-tooltip tooltip-on-top" data-align-col="center" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $tabletsRow3UniqueID ?>center" class="w-edit-column lahb-tooltip tooltip-on-top" data-align-col="center" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                    <div class="lahb-col col-right wp-clearfix" data-align-col="right">
                        <div class="lahb-elements-place wp-clearfix">
                            <?php echo LAHB_Helper::getCellComponents($editor_components, 'tablets-view', 'row3', 'right'); ?>
                        </div>
                        <a href="#" class="w-add-element lahb-tooltip tooltip-on-top" data-align-col="right" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $tabletsRow3UniqueID ?>right" class="w-edit-column lahb-tooltip tooltip-on-top" data-align-col="right" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                </div> <!-- end lahb-columns -->

            </div> <!-- end .lahb-tablets-panel -->

            <!-- mobiles panel -->
            <div class="lahb-tab-panel lahb-mobiles-panel" id="mobiles-view">

                <!-- topbar -->
                <div class="lahb-columns" data-columns="topbar">
                    <div class="lahb-elements-item" data-element="header-area" data-unique-id="<?php echo '' . $mobilesTopbarUniqueID ?>" data-hidden_element="<?php echo '' . $mobilesTopbarHidden; ?>">
                        <span class="lahb-tooltip tooltip-on-top" data-tooltip="Hide">
                            <i class="lahb-control lahb-hide-btn fa fa-eye"></i>
                        </span>
                        <span class="lahb-tooltip tooltip-on-top" data-tooltip="Settings">
                            <i class="lahb-control lahb-edit-btn fa fa-cog"></i>
                        </span>
                    </div>
                    <span class="lahb-element-name"><?php esc_html_e( 'Topbar Area', 'lastudio-header-builder' ); ?></span>
                    <div class="lahb-col col-left wp-clearfix" data-align-col="left">
                        <div class="lahb-elements-place wp-clearfix">
                            <?php echo LAHB_Helper::getCellComponents($editor_components, 'mobiles-view', 'topbar', 'left'); ?>
                        </div>
                        <a href="#" class="w-add-element lahb-tooltip tooltip-on-top" data-align-col="left" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $mobilesTopbarUniqueID ?>left" class="w-edit-column lahb-tooltip tooltip-on-top" data-align-col="left" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                    <div class="lahb-col col-center wp-clearfix" data-align-col="center">
                        <div class="lahb-elements-place wp-clearfix">
                            <?php echo LAHB_Helper::getCellComponents($editor_components, 'mobiles-view', 'topbar', 'center'); ?>
                        </div>
                        <a href="#" class="w-add-element lahb-tooltip tooltip-on-top" data-align-col="center" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $mobilesTopbarUniqueID ?>center" class="w-edit-column lahb-tooltip tooltip-on-top" data-align-col="center" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                    <div class="lahb-col col-right wp-clearfix" data-align-col="right">
                        <div class="lahb-elements-place wp-clearfix">
                            <?php echo LAHB_Helper::getCellComponents($editor_components, 'mobiles-view', 'topbar', 'right'); ?>
                        </div>
                        <a href="#" class="w-add-element lahb-tooltip tooltip-on-top" data-align-col="right" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $mobilesTopbarUniqueID ?>right" class="w-edit-column lahb-tooltip tooltip-on-top" data-align-col="right" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                </div> <!-- end lahb-columns -->

                <!-- header area row 1 -->
                <div class="lahb-columns" data-columns="row1">
                    <div class="lahb-elements-item" data-element="header-area" data-unique-id="<?php echo '' . $mobilesRow1UniqueID ?>" data-hidden_element="<?php echo '' . $mobilesRow1Hidden; ?>">
                        <span class="lahb-tooltip tooltip-on-top" data-tooltip="Hide">
                            <i class="lahb-control lahb-hide-btn fa fa-eye"></i>
                        </span>
                        <span class="lahb-tooltip tooltip-on-top" data-tooltip="Settings">
                            <i class="lahb-control lahb-edit-btn fa fa-cog"></i>
                        </span>
                    </div>
                    <span class="lahb-element-name"><?php esc_html_e( 'Header Area Row 1', 'lastudio-header-builder' ); ?></span>
                    <div class="lahb-col col-left wp-clearfix" data-align-col="left">
                        <div class="lahb-elements-place wp-clearfix">
                            <?php echo LAHB_Helper::getCellComponents($editor_components, 'mobiles-view', 'row1', 'left'); ?>
                        </div>
                        <a href="#" class="w-add-element lahb-tooltip tooltip-on-top" data-align-col="left" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $mobilesRow1UniqueID ?>left" class="w-edit-column lahb-tooltip tooltip-on-top" data-align-col="left" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                    <div class="lahb-col col-center wp-clearfix" data-align-col="center">
                        <div class="lahb-elements-place wp-clearfix">
                            <?php echo LAHB_Helper::getCellComponents($editor_components, 'mobiles-view', 'row1', 'center'); ?>
                        </div>
                        <a href="#" class="w-add-element lahb-tooltip tooltip-on-top" data-align-col="center" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $mobilesRow1UniqueID ?>center" class="w-edit-column lahb-tooltip tooltip-on-top" data-align-col="center" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                    <div class="lahb-col col-right wp-clearfix" data-align-col="right">
                        <div class="lahb-elements-place wp-clearfix">
                            <?php echo LAHB_Helper::getCellComponents($editor_components, 'mobiles-view', 'row1', 'right'); ?>
                        </div>
                        <a href="#" class="w-add-element lahb-tooltip tooltip-on-top" data-align-col="right" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $mobilesRow1UniqueID ?>right" class="w-edit-column lahb-tooltip tooltip-on-top" data-align-col="right" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                </div> <!-- end lahb-columns -->

                <!-- header area row 2 -->
                <div class="lahb-columns" data-columns="row2">
                    <div class="lahb-elements-item" data-element="header-area" data-unique-id="<?php echo '' . $mobilesRow2UniqueID ?>" data-hidden_element="<?php echo '' . $mobilesRow2Hidden; ?>">
                        <span class="lahb-tooltip tooltip-on-top" data-tooltip="Hide">
                            <i class="lahb-control lahb-hide-btn fa fa-eye"></i>
                        </span>
                        <span class="lahb-tooltip tooltip-on-top" data-tooltip="Settings">
                            <i class="lahb-control lahb-edit-btn fa fa-cog"></i>
                        </span>
                    </div>
                    <span class="lahb-element-name"><?php esc_html_e( 'Header Area Row 2', 'lastudio-header-builder' ); ?></span>
                    <div class="lahb-col col-left wp-clearfix" data-align-col="left">
                        <div class="lahb-elements-place wp-clearfix">
                            <?php echo LAHB_Helper::getCellComponents($editor_components, 'mobiles-view', 'row2', 'left'); ?>
                        </div>
                        <a href="#" class="w-add-element lahb-tooltip tooltip-on-top" data-align-col="left" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $mobilesRow2UniqueID ?>left" class="w-edit-column lahb-tooltip tooltip-on-top" data-align-col="left" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                    <div class="lahb-col col-center wp-clearfix" data-align-col="center">
                        <div class="lahb-elements-place wp-clearfix">
                            <?php echo LAHB_Helper::getCellComponents($editor_components, 'mobiles-view', 'row2', 'center'); ?>
                        </div>
                        <a href="#" class="w-add-element lahb-tooltip tooltip-on-top" data-align-col="center" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $mobilesRow2UniqueID ?>center" class="w-edit-column lahb-tooltip tooltip-on-top" data-align-col="center" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                    <div class="lahb-col col-right wp-clearfix" data-align-col="right">
                        <div class="lahb-elements-place wp-clearfix">
                            <?php echo LAHB_Helper::getCellComponents($editor_components, 'mobiles-view', 'row2', 'right'); ?>
                        </div>
                        <a href="#" class="w-add-element lahb-tooltip tooltip-on-top" data-align-col="right" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $mobilesRow2UniqueID ?>right" class="w-edit-column lahb-tooltip tooltip-on-top" data-align-col="right" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                </div> <!-- end lahb-columns -->

                <!-- header area row 3 -->
                <div class="lahb-columns" data-columns="row3">
                    <div class="lahb-elements-item" data-element="header-area" data-unique-id="<?php echo '' . $mobilesRow3UniqueID ?>" data-hidden_element="<?php echo '' . $mobilesRow3Hidden; ?>">
                        <span class="lahb-tooltip tooltip-on-top" data-tooltip="Hide">
                            <i class="lahb-control lahb-hide-btn fa fa-eye"></i>
                        </span>
                        <span class="lahb-tooltip tooltip-on-top" data-tooltip="Settings">
                            <i class="lahb-control lahb-edit-btn fa fa-cog"></i>
                        </span>
                    </div>
                    <span class="lahb-element-name"><?php esc_html_e( 'Header Area Row 3', 'lastudio-header-builder' ); ?></span>
                    <div class="lahb-col col-left wp-clearfix" data-align-col="left">
                        <div class="lahb-elements-place wp-clearfix">
                            <?php echo LAHB_Helper::getCellComponents($editor_components, 'mobiles-view', 'row3', 'left'); ?>
                        </div>
                        <a href="#" class="w-add-element lahb-tooltip tooltip-on-top" data-align-col="left" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $mobilesRow3UniqueID ?>left" class="w-edit-column lahb-tooltip tooltip-on-top" data-align-col="left" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                    <div class="lahb-col col-center wp-clearfix" data-align-col="center">
                        <div class="lahb-elements-place wp-clearfix">
                            <?php echo LAHB_Helper::getCellComponents($editor_components, 'mobiles-view', 'row3', 'center'); ?>
                        </div>
                        <a href="#" class="w-add-element lahb-tooltip tooltip-on-top" data-align-col="center" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $mobilesRow3UniqueID ?>center" class="w-edit-column lahb-tooltip tooltip-on-top" data-align-col="center" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                    <div class="lahb-col col-right wp-clearfix" data-align-col="right">
                        <div class="lahb-elements-place wp-clearfix">
                            <?php echo LAHB_Helper::getCellComponents($editor_components, 'mobiles-view', 'row3', 'right'); ?>
                        </div>
                        <a href="#" class="w-add-element lahb-tooltip tooltip-on-top" data-align-col="right" data-tooltip="Add Element"><i class="fa fa-plus-square-o"></i></a>
                        <a href="#" data-element="header-column" data-unique-id="<?php echo '' . $mobilesRow3UniqueID ?>right" class="w-edit-column lahb-tooltip tooltip-on-top" data-align-col="right" data-tooltip="Settings"><i class="fa fa-cog"></i></a>
                    </div>
                </div> <!-- end lahb-columns -->

            </div> <!-- end .lahb-mobiles-panel -->

        </div> <!-- end .lahb-tabs-panels -->

    </div> <!-- end .lahb-tabs-wrap -->

    <?php
        // add element modal
        include LAHB_Helper::get_file( 'includes/elements/add-elements.php' );
    ?>

    <div class="lahb-modal-wrap lahb-modal-save-header">
        <div class="lahb-modal-header">
            <h4>Save as Pre-defined Headers</h4>
            <i class="dashicons dashicons-no-alt"></i>
        </div>
        <div class="lahb-modal-contents-wrap">
            <div class="lahb-modal-contents w-row">
                <?php

                $lahb_preheaders = LAHB_Helper::get_prebuild_headers();
                $lahb_preheader_opts = array();
                if(!empty($lahb_preheaders)){
                    foreach ($lahb_preheaders as $k => $v){
                        $lahb_preheader_opts[$k] = $v['name'];
                    }
                }
                else{
                    $lahb_preheader_opts = array(
                        '' => esc_html__( 'Default', 'lastudio-header-builder' ),
                    );
                }

                lahb_select( array(
                    'title'			=> esc_html__( 'Save Type', 'lastudio-header-builder' ),
                    'id'			=> 'lahb_save_header_type',
                    'options'		=> array(
                        'add_new' => 'Add New',
                        'update'  => 'Update Existing',
                    ),
                    'dependency'	=> array(
                        'add_new'	=> array( 'lahb_save_header_type_new' ),
                        'update'  => array( 'lahb_save_header_type_existing' ),
                    ),
                ));

                lahb_select( array(
                    'title'			=> esc_html__( 'Select Existing header', 'lastudio-header-builder' ),
                    'id'			=> 'lahb_save_header_type_existing',
                    'options'		=> $lahb_preheader_opts
                ));

                lahb_textfield( array(
                    'title'			=> esc_html__( 'Enter New Header Name', 'lastudio-header-builder' ),
                    'id'			=> 'lahb_save_header_type_new',
                ));

                lahb_image( array(
                    'title'			=> esc_html__( 'Custom Image', 'lastudio-header-builder' ),
                    'id'			=> 'lahb_save_header_custom_image',
                ));

                ?>
            </div>
        </div>
        <div class="lahb-modal-footer">
            <input type="button" class="lahb_close button" value="Close">
            <input type="button" class="lahb_save_as_template button button-primary" value="Save">
        </div>
    </div>

</div> <!-- end .wn-header-builder-wrap -->
