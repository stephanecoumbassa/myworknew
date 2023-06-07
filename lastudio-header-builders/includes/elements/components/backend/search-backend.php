<!-- modal search edit -->
<div class="lahb-modal-wrap lahb-modal-edit" data-element-target="search">

	<div class="lahb-modal-header">
		<h4><?php esc_html_e( 'Search Settings', 'lastudio-header-builder' ); ?></h4>
		<i class="fa fa-times"></i>
	</div>

	<div class="lahb-modal-contents-wrap">
		<div class="lahb-modal-contents w-row">

			<ul class="lahb-tabs-list lahb-element-groups wp-clearfix">
				<li class="lahb-tab w-active">
					<a href="#general">
						<span><?php esc_html_e( 'General', 'lastudio-header-builder' ); ?></span>
					</a>
				</li>
				<li class="lahb-tab">
					<a href="#styling">
						<span><?php esc_html_e( 'Styling', 'lastudio-header-builder' ); ?></span>
					</a>
				</li>
				<li class="lahb-tab">
					<a href="#extra-class">
						<span><?php esc_html_e( 'Extra Class', 'lastudio-header-builder' ); ?></span>
					</a>
				</li>
			</ul> <!-- end .lahb-tabs-list -->

			<!-- general -->
			<div class="lahb-tab-panel lahb-group-panel" data-id="#general">

				<?php

					lahb_select( array(
						'title'			=> esc_html__( 'Select Type', 'lastudio-header-builder' ),
						'id'			=> 'type',
						'default'		=> 'simple',
						'options'		=> array(
							'simple' => esc_html__( 'Simple', 'lastudio-header-builder' ),
							'toggle' => esc_html__( 'Toggle', 'lastudio-header-builder' ),
							'slide'  => esc_html__( 'Slide (Vertical)', 'lastudio-header-builder' ),
							'full' 	 => esc_html__( 'Full', 'lastudio-header-builder' ),
						),
						'dependency'	=> array(
							'toggle'  => array( 'text_beside_icon' ),
							'full'  => array( 'text_before_form' ),
						),
					));

                    lahb_switcher( array(
                        'title'         => esc_html__( 'Is Product Search?', 'lastudio-header-builder' ),
                        'id'            => 'is_product_search',
                        'default'       => 'false'
                    ));

					// Placeholder Text
					lahb_textfield( array(
						'title'			=> esc_html__( 'Placeholder Text', 'lastudio-header-builder' ),
						'id'			=> 'placeholder_text',
						'default'		=> '',
					));

					// Tooltip Text
					lahb_switcher( array(
						'title'         => esc_html__( 'Show Tooltip Text ?', 'lastudio-header-builder' ),
						'id'            => 'show_tooltip',
						'default'       => 'false',
						'dependency'	=> array(
							'true'  => array( 'tooltip_text', 'tooltip_position' ),
						),
					));

					lahb_textfield( array(
						'title'			=> esc_html__( 'Tooltip Text', 'lastudio-header-builder' ),
						'id'			=> 'tooltip_text',
						'default'		=> 'Tooltip Text',
					));

					lahb_select( array(
						'title'			=> esc_html__( 'Select Tooltip Position', 'lastudio-header-builder' ),
						'id'			=> 'tooltip_position',
						'default'		=> 'tooltip-on-bottom',
						'options'		=> array(
							'tooltip-on-top'	=> esc_html__( 'Top', 'lastudio-header-builder' ),
							'tooltip-on-bottom' => esc_html__( 'Bottom', 'lastudio-header-builder' ),
						),
					));

					lahb_switcher( array(
						'title'			=> esc_html__( 'Hide top arrow?', 'lastudio-header-builder' ),
						'id'			=> 'top_arrow',
						'default'		=> 'false',
					));

					lahb_switcher( array(
						'title'			=> esc_html__( 'Hide Search box icon?', 'lastudio-header-builder' ),
						'id'			=> 'searchbox_icon',
						'default'		=> 'false',
					));

					lahb_textfield( array(
						'title'			=> esc_html__( 'Add custom text besides search icon', 'lastudio-header-builder' ),
						'id'			=> 'text_beside_icon',
						'default'		=> 'Search',
					));

					lahb_textfield( array(
						'title'			=> esc_html__( 'Add custom text before search form', 'lastudio-header-builder' ),
						'id'			=> 'text_before_form',
						'default'		=> 'Start typing and press Enter to search',
					));

                    lahb_icon( array(
                        'title'			=> esc_html__( 'Select your design icon', 'lastudio-header-builder' ),
                        'id'			=> 'search_icon'
                    ));
				?>

			</div> <!-- end general -->

			

			<!-- styling -->
			<div class="lahb-tab-panel lahb-group-panel" data-id="#styling">
				
				<?php
					lahb_styling_tab( array(
						'Icon' => array(
							array( 'property' => 'color' ),
							array( 'property' => 'color_hover' ),
							array( 'property' => 'font_size' ),
							array( 'property' => 'position' ),
						),
						'Custom Text' => array(
							array( 'property' => 'color' ),
							array( 'property' => 'color_hover' ),
							array( 'property' => 'font_size' ),
							array( 'property' => 'float' ),
							array( 'property' => 'margin' ),
							array( 'property' => 'padding' ),
							array( 'property' => 'border' ),
						),
						'Background' => array(
							array( 'property' => 'background_color' ),
							array( 'property' => 'background_color_hover' ),
							array( 'property' => 'background_image' ),
							array( 'property' => 'background_position' ),
							array( 'property' => 'background_repeat' ),
							array( 'property' => 'background_cover' ),
						),
						'Box' => array(
							array( 'property' => 'width' ),
							array( 'property' => 'height' ),
							array( 'property' => 'margin' ),
							array( 'property' => 'padding' ),
							array( 'property' => 'border' ),
							array( 'property' => 'border_radius' ),
						),
						'Search Form' => array(
							array( 'property' => 'width' ),
							array( 'property' => 'height' ),
							array( 'property' => 'background_color' ),
							array( 'property' => 'background_color_hover' ),
							array( 'property' => 'background_image' ),
							array( 'property' => 'background_position' ),
							array( 'property' => 'background_repeat' ),
							array( 'property' => 'background_cover' ),
							array( 'property' => 'margin' ),
							array( 'property' => 'padding' ),
							array( 'property' => 'border' ),
							array( 'property' => 'border_radius' ),
							array( 'property' => 'position' ),
							array( 'property' => 'box_shadow' ),
						),
						'Search Form Input' => array(
							array( 'property' => 'width' ),
							array( 'property' => 'height' ),
							array( 'property' => 'color' ),
							array( 'property' => 'background_color' ),
							array( 'property' => 'background_color_hover' ),
							array( 'property' => 'background_image' ),
							array( 'property' => 'background_position' ),
							array( 'property' => 'background_repeat' ),
							array( 'property' => 'background_cover' ),
							array( 'property' => 'margin' ),
							array( 'property' => 'padding' ),
							array( 'property' => 'border' ),
							array( 'property' => 'border_radius' ),
						),
						'Arrow' => array(
							array( 'property' => 'background_color' ),
							array( 'property' => 'background_color_hover' ),
							array( 'property' => 'position' ),
						),
						'Full Page Search' => array(
							array( 'property' => 'background_color' ),
							array( 'property' => 'background_color_hover' ),
							array( 'property' => 'background_image' ),
							array( 'property' => 'background_position' ),
							array( 'property' => 'background_repeat' ),
							array( 'property' => 'background_cover' ),
						),
						'Search Box Icon' => array(
							array( 'property' => 'color' ),
							array( 'property' => 'color_hover' ),
							array( 'property' => 'font_size' ),
							array( 'property' => 'width' ),
							array( 'property' => 'height' ),
							array( 'property' => 'background_color' ),
							array( 'property' => 'background_color_hover' ),
							array( 'property' => 'margin' ),
							array( 'property' => 'padding' ),
							array( 'property' => 'border' ),
							array( 'property' => 'border_radius' ),
							array( 'property' => 'position' ),
						),
						'Tooltip' => array(
							array( 'property' => 'color' ),
							array( 'property' => 'color_hover' ),
							array( 'property' => 'font_size' ),
							array( 'property' => 'font_weight' ),
							array( 'property' => 'font_style' ),
							array( 'property' => 'letter_spacing' ),
							array( 'property' => 'background_color' ),
							array( 'property' => 'background_color_hover' ),
							array( 'property' => 'overflow' ),
							array( 'property' => 'word_break' ),
						),
					) );
				?>

			</div> <!-- end #styling -->

			<!-- extra-class -->
			<div class="lahb-tab-panel lahb-group-panel" data-id="#extra-class">

				<?php

					lahb_textfield( array(
						'title'			=> esc_html__( 'Extra class', 'lastudio-header-builder' ),
						'id'			=> 'extra_class',
					));

				?>

			</div> <!-- end #extra-class -->

		</div>
	</div> <!-- end lahb-modal-contents-wrap -->

	<div class="lahb-modal-footer">
		<input type="button" class="lahb_close button" value="<?php esc_html_e( 'Close', 'lastudio-header-builder' ); ?>">
		<input type="button" class="lahb_save button button-primary" value="<?php esc_html_e( 'Save Changes', 'lastudio-header-builder' ); ?>">
	</div>

</div> <!-- end lahb-elements -->