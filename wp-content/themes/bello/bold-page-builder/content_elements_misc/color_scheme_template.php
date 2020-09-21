<?php
$custom_css = "

	/* Icons */
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_icon.bt_bb_style_outline a.bt_bb_icon_holder:before {
		box-shadow: 0 0 0 2px {$color_scheme[1]} inset;
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_icon.bt_bb_style_filled:hover a.bt_bb_icon_holder:before {
		box-shadow: 0 0 0 2px {$color_scheme[2]} inset;
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_icon.bt_bb_style_outline a.bt_bb_icon_holder:before {
		box-shadow: 0 0 0 2px {$color_scheme[1]} inset;
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_icon.bt_bb_style_filled:hover a.bt_bb_icon_holder:before {
		box-shadow: 0 0 0 2px {$color_scheme[2]} inset;
	}
	.bt_bb_size_xsmall.bt_bb_color_scheme_{$scheme_id}.bt_bb_icon.bt_bb_style_outline a.bt_bb_icon_holder:before {
		box-shadow: 0 0 0 1px {$color_scheme[1]} inset;
	}
	.bt_bb_size_small.bt_bb_color_scheme_{$scheme_id}.bt_bb_icon.bt_bb_style_filled:hover a.bt_bb_icon_holder:before {
		box-shadow: 0 0 0 1px {$color_scheme[2]} inset;
	}
	
	/* Buttons */
	
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_button.bt_bb_style_outline a {
		box-shadow: 0 0 0 2px {$color_scheme[1]} inset;
		color: {$color_scheme[1]};
		background-color: transparent;
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_button.bt_bb_style_outline a:after {
		border-color: transparent;
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_button.bt_bb_style_outline a:hover {
		box-shadow: 0 0 0 2em {$color_scheme[1]} inset, 0 3px 10px rgba(24,24,24,0.3);
		color: {$color_scheme[2]};		
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_button.bt_bb_style_outline a:after {
		border-color: {$color_scheme[1]};
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_button.bt_bb_style_filled a {
		background: {$color_scheme[2]};
		box-shadow: none;
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_button.bt_bb_style_filled a:after {
		border-color: {$color_scheme[2]};
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_button.bt_bb_style_filled a:hover {
		color: {$color_scheme[1]};
		background: {$color_scheme[2]};
		box-shadow: 0 3px 10px rgba(24,24,24,0.3);
	}
	

	/* Services */
	
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_outline.bt_bb_service .bt_bb_icon_holder	{
		box-shadow: 0 0 0 2px {$color_scheme[1]} inset;
		color: {$color_scheme[1]};
		background-color: transparent;
	}
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_filled.bt_bb_service:hover .bt_bb_icon_holder	{
		box-shadow: 0 0 0 2px {$color_scheme[2]} inset;
		background-color: {$color_scheme[1]};
		color: {$color_scheme[2]};
	}
	
	/* Tabs */
	
	.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_simple .bt_bb_tabs_header li {
		color: {$color_scheme[2]};
	}
	
	.bt_bb_tabs.bt_bb_color_scheme_{$scheme_id}.bt_bb_style_simple .bt_bb_tabs_header li.on {
		color: {$color_scheme[1]};
		border-color: inherit;
	}
";