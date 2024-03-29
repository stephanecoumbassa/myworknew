<!-- modal header area -->
<div class="lahb-modal-wrap lahb-modal-edit" data-element-target="header-column">

	<div class="lahb-modal-header">
		<h4><?php esc_html_e( 'Column Area', 'lastudio-header-builder' ); ?></h4>
		<i class="fa fa-times"></i>
	</div>

	<div class="lahb-modal-contents-wrap">
		<div class="lahb-modal-contents w-row">

			<ul class="lahb-tabs-list lahb-element-groups wp-clearfix">
                <li class="lahb-tab w-active">
                    <a href="#classID">
                        <span><?php esc_html_e( 'Class & ID', 'lastudio-header-builder' ); ?></span>
                    </a>
                </li>
				<li class="lahb-tab">
					<a href="#styling">
						<span><?php esc_html_e( 'Styling', 'lastudio-header-builder' ); ?></span>
					</a>
				</li>
			</ul> <!-- end .lahb-tabs-list -->

            <!-- classID -->
            <div class="lahb-tab-panel lahb-group-panel" data-id="#classID">

                <?php

                lahb_textfield( array(
                    'title'			=> esc_html__( 'Extra class', 'lastudio-header-builder' ),
                    'id'			=> 'extra_class',
                ));

                lahb_textfield( array(
                    'title'			=> esc_html__( 'Extra ID', 'lastudio-header-builder' ),
                    'id'			=> 'extra_id',
                ));

                ?>

            </div> <!-- end #classID -->

			<!-- styling -->
			<div class="lahb-tab-panel lahb-group-panel" data-id="#styling">

				<?php
					lahb_styling_tab( array(
						'Typography' => array(
							array( 'property' => 'color' ),
							array( 'property' => 'color_hover' ),
							array( 'property' => 'font_size' ),
							array( 'property' => 'font_weight' ),
							array( 'property' => 'font_style' ),
							array( 'property' => 'text_align' ),
							array( 'property' => 'text_transform' ),
							array( 'property' => 'text_decoration' ),
							array( 'property' => 'line_height' ),
							array( 'property' => 'letter_spacing' ),
							array( 'property' => 'overflow' ),
							array( 'property' => 'word_break' ),
						),
						'Background' => array(
							array( 'property' => 'background_color' ),
							array( 'property' => 'background_color_hover' ),
							array( 'property' => 'background_image' ),
							array( 'property' => 'background_position' ),
							array( 'property' => 'background_repeat' ),
							array( 'property' => 'background_cover' ),
							array( 'property' => 'gradient' ),
						),
						'Box' => array(
							array( 'property' => 'height' ),
							array( 'property' => 'width' ),
							array( 'property' => 'margin' ),
							array( 'property' => 'padding' ),
							array( 'property' => 'border' ),
							array( 'property' => 'border_radius' ),
							array( 'property' => 'box_shadow' ),
						),
					) );
				?>

			</div> <!-- end #styling -->



		</div>
	</div> <!-- end lahb-modal-contents-wrap -->

	<div class="lahb-modal-footer">
		<input type="button" class="lahb_close button" value="<?php esc_html_e( 'Close', 'lastudio-header-builder' ); ?>">
		<input type="button" class="lahb_save button button-primary" value="<?php esc_html_e( 'Save Changes', 'lastudio-header-builder' ); ?>">
	</div>

</div> <!-- end lahb-elements -->