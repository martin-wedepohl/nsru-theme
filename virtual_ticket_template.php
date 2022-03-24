<?php
/**
 * Template Name: Virtual Ticket
 * Template Post Type: post, page
 * The template for displaying a form for entering virtual ticket information.
 *
 * @package Layers
 * @since Layers 1.0.0
 */

global $wpdb;

get_header();

date_default_timezone_set( get_option( 'timezone_string' ) );
$now = date( 'Y-m-d H:i:s' );

$display_form   = true;
$display_ticket = false;

if ( isset( $_POST['form-submitted'] ) ) {
	$display_form = false;

	if ( ! isset( $_POST['virtual_ticket_nonce'] ) || ! wp_verify_nonce( $_POST['virtual_ticket_nonce'], 'virtual_ticket_form' ) ) {
		exit( 'INVALID NONCE' );  // Invalid nonce.
	}

	$name     = substr( $_POST['guest-name'], 0, 200 );
	$email    = strtolower( substr( $_POST['guest-email'], 0, 200 ) );
	$sobriety = substr( $_POST['guest-sobriety'], 0, 10 );
	$_POST    = array();

	$data     = array(
		'name'       => $name,
		'email'      => $email,
		'sobriety'   => $sobriety,
		'created_on' => $now,
	);
	$table = $wpdb->prefix . 'countdown';

	$result = $wpdb->insert( $table, $data, $format = NULL );

	if ( 1 === $result ) {
		echo( "<script>alert('Sobriety Information Entered ... Thank You');</script>" );
		$display_ticket = true;
	} else {
		echo( "<script>alert('ERROR: Unable to enter sobriety information');</script>" );
	}

}
?>
<style>
h1 {
	text-align: center;
}

.virtual-ticket {
	display: flex;
	flex-direction: column;
	justify-content: center;
	max-width: 43em;
	margin: 1em auto;
	padding: 1em;
}

.virtual-ticket h2,
.virtual-ticket-button h2 {
	text-align: center;
	margin-bottom: 1.5em;
}

.virtual-ticket p,
.virtual-ticket-button p {
	text-align: center;
	margin-bottom: 1em;
}

.virtual-ticket-button {
	display: flex;
	flex-direction: column;
	justify-content: center;
	max-width: 43em;
	margin: 1em auto;
	padding: 1em;
	gap: 1em;
}

.ticket-left,
.ticket-right {
	display: flex;
	flex-direction: column;
	align-items: center;
	padding: 1em;
	border: 1px solid gray;
}

.inner {
	display: grid;
	grid-auto-flow: row;
}

.inner input[type="submit"],
.inner input[type="email"],
.inner input[type="text"],
.inner input[type="date"] {
	padding: .5em .75em;
	font-size: 2.5rem;
	height: 4rem;
	margin-bottom: 1em;
	max-width: unset;
}

.inner input[type="submit"] {
	height: 5rem;
}


.extra {
	font-size: 80%;
	font-weight: 700;
}

.required {
	font-size: 80%;
	font-weight: 700;
	color: red;
}

@media (min-width: 700px) {
	.virtual-ticket-button {
		flex-direction: row;
	}
}

@media (min-width: 45em) {
	.inner {
		grid-template-areas: 
			"label-name input-name"
			"label-email input-email"
			"label-cemail input-cemail"
			"label-sobriety input-sobriety"
			"input-submit input-submit";
		grid-template-columns: 17em 25em;
		justify-content: center;
	}
	label[for="guest-name"] {
		grid-area: label-name
	}
	label[for="guest-email"] {
		grid-area: label-email
	}
	label[for="guest-cemail"] {
		grid-area: label-cemail
	}
	label[for="guest-sobriety"] {
		grid-area: label-sobriety
	}
	input#guest-name {
		grid-area: input-name
	}
	input#guest-email {
		grid-area: input-email
	}
	input#guest-cemail {
		grid-area: input-cemail
	}
	input#guest-sobriety {
		grid-area: input-sobriety;
	}
	input#guest-submit {
		grid-area: input-submit;
	}
}
</style>
<?php

if ( $display_form ) { ?>
<section class="virtual-ticket">
	<h2>Please enter Virtual Ticket information</h2>
	<p>Please note that your name and email will only be used for issuing the virtual ticket, while your sobriety date if entered will be used for the Friday night sobriety countdown.</p>
	<p>Once entered you will be taken to the link to purchase a virtual ticket.</p>
	<form role="form" name="virtual-ticket-form" id="virtual-ticket-form" method="post">
		<div class="inner">
		<label for="guest-name">Name: <span class="extra">First Name, Last Initial - </span><span class="required">(required)</span></label>
		<input type="text" name="guest-name" id="guest-name" maxlength="200">
		<label for="guest-email">Email: <span class="required">(required)</span></label>
		<input type="email" name="guest-email" id="guest-email" maxlength="200">
		<label for="guest-cemail">Confirmation Email: <span class="required">(required)</span></label>
		<input type="email" name="guest-cemail" id="guest-cemail" maxlength="200">
		<label for="guest-sobriety">Sobriety Date: <span class="required">(YYYY-MM-DD)</span></span></label>
		<input type="date" name="guest-sobriety" id="guest-sobriety" maxlength="20">
		<input type="submit" name="guest-submit" id="guest-submit" value="Submit" />
		<?php wp_nonce_field( 'virtual_ticket_form', 'virtual_ticket_nonce' ); ?>
		</div>
	</form>
</section>
<?php } // if ($display_form)

if ( $display_ticket ) { ?>
<h1>Purchase virtual ticket through either PayPal or Square</h1>
<section class="virtual-ticket-button">
	<div class="ticket-left">
		<h2>Purchase Virtual Ticket through PayPal</h2>
		<p>Purchase price $20<br>plus $0.88 service charge</p>
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="hosted_button_id" value="ZZY77CBHBDZQ8">
			<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
			<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
		</form>
	</div>
	<div class="ticket-right">
		<h2>Purchase Virtual Ticket through Square</h2>
		<p>Purchase price $20<br>plus $0.88 service charge</p>
		<div style="overflow: auto;display: flex;flex-direction: column;justify-content: flex-end;align-items: center;width: 259px;background: #FFFFFF;font-family: SQ Market, SQ Market, Helvetica, Arial, sans-serif;">
			<div style="padding: 20px;">
				<a target="_blank" href="https://square.link/u/6arzfuuS?src=embed" 
					style="display: inline-block;font-size: 18px;line-height: 48px;height: 48px;color: #ffffff;min-width: 212px;background-color: #006aff;text-align: center;box-shadow: 0 0 0 1px rgba(0,0,0,.1) inset;border-radius: 0px;">Buy now</a>
			</div>
		</div>
	</div>
</section>
<?php } // if ($display_ticket) ?>

<script>

	const validEmail = (email) => {
		return String(email).toLowerCase().match(/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/);
	};

	const validDate = (date) => {
		if (!date.match(/^\d{4}-\d{2}-\d{2}$/)) {
			return false;  // Invalid format
		}
		const d = new Date(date);
		const dNum = d.getTime();
		if (!dNum && dNum !== 0) {
			return false; // NaN value, Invalid date
		}
		return d.toISOString().slice(0,10) === date;
	};

	const form = document.querySelector('#virtual-ticket-form');
	if (form) {
		form.addEventListener('submit', (e) => {
			e.preventDefault();

			const name = document.querySelector('#guest-name');
			const email = document.querySelector('#guest-email');
			const cemail = document.querySelector('#guest-cemail');
			const sobriety = document.querySelector('#guest-sobriety');

			if ('' === name.value) {
				alert('A name is required');
				name.focus();
				return false;
			}

			if ('' === email.value) {
				alert('An email is required');
				email.focus();
				return false;
			}

			if (!validEmail(email.value)) {
				alert('Email address is invalid');
				email.focus();
				return false;
			}

			if (email.value !== cemail.value) {
				alert('Emails don\'t match');
				email.focus();
				return false;
			}

			if (sobriety.value && !validDate(sobriety.value)) {
				alert('Invalid sobriety date');
				sobriety.focus();
				return false;
			}

			const input = document.createElement('input');
			input.type = 'hidden';
			input.name = 'form-submitted';
			input.value = 'Form submitted';
			form.append(input);

			form.submit();
		});
	}
</script>

<?php get_footer();
