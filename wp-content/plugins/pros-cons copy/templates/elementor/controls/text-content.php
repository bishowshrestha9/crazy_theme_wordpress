<?php
defined( 'ABSPATH' ) || exit;
?>
<div class="affx-elementor-control affx-elementor-text-control" data-setting="{{ data.name }}" data-repeater-name="{{ data.repeater_name }}"  data-inner-repeater-name="{{ data.inner_repeater_name }}">
	<div class="elementor-control-field">
		<# if ( data.label ) {#>
			<label for="<?php $this->print_control_uid(); ?>" class="elementor-control-title">{{{ data.label }}}</label>
		<# } #>
	</div>
	<div class="elementor-control-input-wrapper elementor-control-unit-5 elementor-control-dynamic-switcher-wrapper <# if ( data.disabled ) { #>elementor-control-disabled<# } #>">
		<input id="<?php $this->print_control_uid(); ?>" type="{{ data.input_type }}" class="tooltip-target elementor-control-tag-area <# if ( data.disabled ) { #>disabled<# } #>" data-tooltip="{{ data.title }}" data-setting="{{ data.name }}" placeholder="{{ view.getControlPlaceholder() }}" <# if ( data.disabled ) { #>disabled<# } #> />
		<# if (data.search_button) { #>
			<?php echo \ProsCons\Helpers\Elementor\WidgetHelper::get_search_button_html(); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<# } else if (data.amazon_button) { #>
			<?php echo \ProsCons\Helpers\Elementor\WidgetHelper::get_amazon_button_html(); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<# } #>
	</div>
	<# if ( data.description ) { #>
		<div class="elementor-control-field-description">{{{ data.description }}}</div>
	<# } #>
</div>
