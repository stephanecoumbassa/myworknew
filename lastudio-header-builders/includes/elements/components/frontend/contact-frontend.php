<?php

function lahb_contact_f( $atts, $uniqid, $once_run_flag ) {

	extract( LAHB_Helper::component_atts( array(
		'contact_form'		=> '',
		'contact_type'		=> 'icon',
		'open_form'			=> 'modal',
		'contact_text'		=> 'CONTACT US',
		'contact_modal_text'=> 'CONTACT US',
		'show_tooltip'		=> 'false',
		'tooltip_text'		=> 'Contact',
		'tooltip_position'	=> 'tooltip-on-bottom',
		'extra_class'		=> '',
	), $atts ));

	$out = $data_tooltip = $contact_extra_class = $modal = '';

	// login
	$contact_type 		= $contact_type ? $contact_type : '' ;
	$contact_text 		= $contact_text ? $contact_text : '' ;
	$contact_modal_text = $contact_modal_text ? $contact_modal_text : '' ;
	$open_form 			= $open_form ? $open_form : '' ;
	
	// tooltip
	$tooltip_text	= ! empty( $tooltip_text ) ? $tooltip_text : '' ;
	$tooltip = $tooltip_class = '';
	if ( $show_tooltip == 'true' && $tooltip_text ) :
		
		$tooltip_position 	= ( isset( $tooltip_position ) && $tooltip_position ) ? $tooltip_position : 'tooltip-on-bottom';
		$tooltip_class		= ' lahb-tooltip ' . $tooltip_position;
		$tooltip			= ' data-tooltip=" ' . esc_attr( LAHB_Helper::render_string($tooltip_text) ) . ' "';

	endif;

	// styles
	if ( $once_run_flag ) :

		$dynamic_style = '';
        $dynamic_style .= lahb_styling_tab_output( $atts, 'Text', '#lastudio-header-builder .contact_' . esc_attr( $uniqid ) .' .lahb-contact-text','#lastudio-header-builder .contact_' . esc_attr( $uniqid ) .':hover .lahb-contact-text' );
        $dynamic_style .= lahb_styling_tab_output( $atts, 'Icon', '#lastudio-header-builder .contact_' . esc_attr( $uniqid ) . ' .lahb-icon-element i', '#lastudio-header-builder .contact_' . esc_attr( $uniqid ) . ':hover .lahb-icon-element i'  );
		$dynamic_style .= lahb_styling_tab_output( $atts, 'Box', '#lastudio-header-builder .contact_' . esc_attr( $uniqid ) .'' );
		$dynamic_style .= lahb_styling_tab_output( $atts, 'Form', '#lastudio-header-builder .contact_' . esc_attr( $uniqid ) .' .la-contact-form, .la-contact-popup.popup_id_'.esc_attr( $uniqid ).' #lightcase-case .lightcase-contentInner' );
		$dynamic_style .= lahb_styling_tab_output( $atts, 'Tooltip', '#lastudio-header-builder .contact_' . esc_attr( $uniqid ) .'.lahb-tooltip[data-tooltip]:before' );

        if ( $dynamic_style ) :
            LAHB_Helper::set_dynamic_styles( $dynamic_style );
        endif;

	endif;

	// extra class
	$extra_class = $extra_class ? ' ' . $extra_class : '' ;
	
	if ( ( $contact_type == 'text' || $contact_type == 'icon' ) && ( $open_form == 'modal' ) ) {
		$contact_extra_class = 'lahb-header-toggle';
	}
	elseif ( ( $contact_type == 'text' || $contact_type == 'icon' ) && ( $open_form == 'dropdown' ) ){
		$contact_extra_class = 'lahb-header-dropdown';
	}

	// render
	$out .= '<div class="lahb-element lahb-icon-wrap lahb-contact ' . esc_attr( $tooltip_class . $extra_class ) . ' ' . $contact_extra_class . ' contact_'.esc_attr( $uniqid ).'"' . $tooltip . '>';

		if ( ( $contact_type == 'text' || $contact_type == 'icon' ) && ( $open_form == 'modal' ) ) {
			$out .= '<a class="la-inline-popup js-contact_trigger_modal" href="#lastudio-contact-' . esc_attr( $uniqid ) . '" data-component_name="la-contact-popup popup_id_'.esc_attr( $uniqid ).'"></a>';
		}

		if ( $contact_type == 'text' || $contact_type == 'icon' ) {
			$out .= '<div class="lahb-icon-element hcolorf">';
                if ( $contact_type == 'text' ) {
                    $out .= '<span class="lahb-contact-text">' . LAHB_Helper::render_string($contact_text) . '</span>';
                }
                elseif ( $contact_type == 'icon' )  {
                    $out .= '<i class="fa fa-envelope-o"></i>';
                }
			$out .= '</div>';
			
		}

		if ( ( $contact_type == 'text' || $contact_type == 'icon' ) && ( $open_form == 'modal' ) ) {
			$out .= '<div id="lastudio-contact-' . esc_attr( $uniqid ) . '" class="la-modal modal-contact"><h3 class="modal-title"> ' . LAHB_Helper::render_string($contact_modal_text) . '</h3>';
		}
		if ( ( $contact_type == 'text' || $contact_type == 'icon' ) && ( $open_form == 'dropdown' ) ) { 
			$out .= '<a href="#" class="lahb-trigger-element js-contact_trigger_dropdown"></a><div class="la-contact-form la-element-dropdown">';
		}

		if ( ( $contact_type == 'form' ) ) { 
			$out .= '<div id="la-contact-form-' . esc_attr( $uniqid ) . '" class="la-contact-form">';
		}

		if ( ! empty( $contact_form ) ) {
			$out .=	do_shortcode( '[contact-form-7 id="' . $contact_form . '" title="' . esc_attr( 'Contact' ) . '"]' );
		}
		else {
			$out .=	esc_html__( 'Please select a from in element setting.', 'lastudio-header-builder' );
		}

			$out .= '</div>';
	$out .= '</div>';
	return $out;

}

LAHB_Helper::add_element( 'contact', 'lahb_contact_f' );
