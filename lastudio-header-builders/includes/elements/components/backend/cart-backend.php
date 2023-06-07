<!-- modal text edit -->
<div class="lahb-modal-wrap lahb-modal-edit" data-element-target="cart">

	<div class="lahb-modal-header">
		<h4><?php esc_html_e( 'Woocommerce Cart', 'lastudio-header-builder' ); ?></h4>
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
					<a href="#classID">
						<span><?php esc_html_e( 'Class & ID', 'lastudio-header-builder' ); ?></span>
					</a>
				</li>
			</ul> <!-- end .lahb-tabs-list -->

			<!-- general -->
			<div class="lahb-tab-panel lahb-group-panel" data-id="#general">

				<?php

                    lahb_icon( array(
                        'title'			=> esc_html__( 'Select Cart Icon', 'lastudio-header-builder' ),
                        'id'			=> 'cart_icon',
                        'default'		=> 'dl-icon-cart4',
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
						),
						'Counter' => array(
							array( 'property' => 'color' ),
							array( 'property' => 'color_hover' ),
							array( 'property' => 'font_size' ),
							array( 'property' => 'font_weight' ),
							array( 'property' => 'font_style' ),
							array( 'property' => 'letter_spacing' ),
							array( 'property' => 'line_height' ),
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
						'Box' => array(
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
						),
					) );
				?>

			</div> <!-- end #styling -->

			<!-- classID -->
			<div class="lahb-tab-panel lahb-group-panel" data-id="#classID">

				<?php

					lahb_textfield( array(
						'title'			=> esc_html__( 'Extra class', 'lastudio-header-builder' ),
						'id'			=> 'extra_class',
					));

				?>

			</div> <!-- end #classID -->

		</div>
	</div> <!-- end lahb-modal-contents-wrap -->

	<div class="lahb-modal-footer">
		<input type="button" class="lahb_close button" value="<?php esc_html_e( 'Close', 'lastudio-header-builder' ); ?>">
		<input type="button" class="lahb_save button button-primary" value="<?php esc_html_e( 'Save Changes', 'lastudio-header-builder' ); ?>">
	</div>

</div> <!-- end lahb-elements -->