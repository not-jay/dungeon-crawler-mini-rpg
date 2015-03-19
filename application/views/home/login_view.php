<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Dungeon Crawler Mini-RPG</title>
	<?= link_tag("css/misc.css"); ?>
	<?= link_tag("css/rpg-ui.css"); ?>
	<?= link_tag("css/bootstrap.min.css"); ?>
	<?= link_tag("css/bootstrap-theme.min.css"); ?>
</head>
<body>
	<div class="center-outer"><div class="center-middle">
		<div class="center-content center-block ff7 pad-me">
			<div class="form-group form-inline">
				<label for="username" class="control-label">Username</label>
				<input type="text" id="username" name="username">
			</div>
			<div class="form-group">
				<label for="username" class="control-label">Password</label>
				<input type="password" id="password" name="password">
			</div>
			<div class="form-group center-text">
				<p id="errors"></p>
			</div>
			<div class="form-group center-text">
				<button id="login" class="btn btn-default">Login</button>
				<button id="register" class="btn btn-default">Register</button>
			</div>
		</div>
	</div></div>
</body>
<script type="text/javascript" src="<?= base_url('js/jquery.min.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('js/bootstrap.min.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('js/base.url.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('js/string.extended.js'); ?>"></script>
<script type="text/javascript">
$(document).ready(function() {
	$("#login").click(function() {
		controller("login");
	});

	$("#register").click(function() {
		controller("register");
	});
})
function controller(action) {
	var link = (action === "login")?"home/login":"home/register";

	$("#errors").hide();
	$.ajax(link, {
		type: "POST",
		dataType: "json",
		data: {
			"username": $("#username").val(),
			"password": $("#password").val()
		},
		success: function(data) {
			if(data.status === "okay") {
				if(action === "login") {
					$(location).attr("href", "town");
				} else {
					$("#errors").html(data.info).show();
				}
			} else {
				$("#errors").html(data.error).show();
				$("#username").val("");
				$("#password").val("");
			}
		}
	});
}
</script>
</html>