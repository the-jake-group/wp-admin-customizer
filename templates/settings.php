<div class="wrap">
	<h2>Custom Admin Theme Settings</h2>
	<?php $settings->update_message(); ?>
	<?php $settings->inactive_scheme_message(); ?>
	<form method="post" enctype="multipart/form-data" class="compiler-form">
		<div class="section color-section">
			<?php $settings->render_group("colors"); ?>
			<?php if ($settings->color_scheme_exists()) : ?>
				<div class="form-row">
					<div class="label-column">
						<span class="label">Color Scheme</span>
					</div>
					<div class="form-column">
						<?php $settings->render_group("color-schemes"); ?>
					</div>
				</div>
			<?php endif; ?>
		</div>

		<div class="section">
			<?php $settings->render_group("login"); ?>
		</div>

		<div class="section checkbox-group clearfix">
			<div class="form-row">
				<div class="label-column">
					<span class="label">Disable Dashboard Widgets</span>
				</div>
				<div class="form-column">
					<?php $settings->render_group("dashboard"); ?>
				</div>
			</div>
		</div>

		<div class="form-bottom">		
			<?php wp_nonce_field(WPAC::SLUG); ?>
			<input name="save" type="submit" id="submit" value="Save Changes" class="button button-primary">
		</div>
	</form>
</div>