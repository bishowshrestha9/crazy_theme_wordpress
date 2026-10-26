<?php
/**
 * Safora Email Storage Driver
 *
 * @package Safora
 */

if (!defined('ABSPATH')) die('No direct access allowed');

class Safora_Backup_Module_Email extends Safora_Backup_Module {

	public function get_id() {
		return 'email';
	}

	public function get_label() {
		return 'Email Delivery';
	}

	public function backup($backup_files) {
		global $safora_core;
		$opts = $this->get_config();
		$to = !empty($opts['email']) ? $opts['email'] : get_option('admin_email');
		$backup_dir = $safora_core->backups_dir_location();

		$max_email_size = 20 * 1024 * 1024; // 20 MB safe threshold for email attachments

		foreach ($backup_files as $file) {
			Safora_Job_Scheduler::record_still_alive();
			if ($safora_core->is_uploaded($file)) continue;

			$local_path = $backup_dir . '/' . $file;
			if (!file_exists($local_path)) continue;

			$filesize = filesize($local_path);
			if ($filesize > $max_email_size) {
				$this->log("Archive $file (" . Safora_Manipulation_Functions::format_bytes($filesize) . ") exceeds 20MB email threshold. Skipping attachment.", 'warning');
				continue;
			}

			$subject = sprintf('[%s] Backup Archive: %s', get_bloginfo('name'), $file);
			$message = "Your automated site backup archive is attached.\n\nFile: $file\nSize: " . Safora_Manipulation_Functions::format_bytes($filesize) . "\nDate: " . current_time('mysql');

			$this->log("Sending $file via email to $to...");
			$sent = wp_mail($to, $subject, $message, array(), array($local_path));

			if ($sent) {
				$safora_core->uploaded_file($file);
				$this->log("Email successfully sent to $to with attachment $file.");
			} else {
				$this->log("Failed sending email for $file", 'error');
			}
		}

		return true;
	}

	public function download($file) {
		return false;
	}

	public function delete($files, $backup_history = array()) {
		return true;
	}

	public function listfiles() {
		return array();
	}

	public function credentials_test() {
		$opts = $this->get_config();
		$to = !empty($opts['email']) ? $opts['email'] : get_option('admin_email');
		$sent = wp_mail($to, '[' . get_bloginfo('name') . '] Safora Email Test', 'This is a test email sent from Safora Backup Plugin.');
		if ($sent) {
			return array('result' => true, 'message' => "Test email successfully sent to $to.");
		}
		return array('result' => false, 'message' => "Failed to send email to $to. Please verify your WordPress mail settings or SMTP plugin.");
	}

	public function render_config_fields() {
		$opts = $this->get_config();
		$email = isset($opts['email']) ? esc_attr($opts['email']) : get_option('admin_email');

		ob_start();
		?>
		<div class="safora-storage-subform" id="safora-storage-email-form">
			<table class="form-table">
				<tr>
					<th><label for="safora_email_dest">Recipient Email Address</label></th>
					<td>
						<input type="email" id="safora_email_dest" name="safora_email[email]" value="<?php echo $email; ?>" class="regular-text">
						<p class="description">Note: Most mail providers reject attachments larger than 20MB. Archives above this size will be logged and skipped.</p>
					</td>
				</tr>
			</table>
			<button type="button" class="button safora-test-storage-btn" data-service="email">Send Test Email</button>
		</div>
		<?php
		return ob_get_clean();
	}
}
