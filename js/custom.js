var baseurl = 'http://localhost/mail_desk/';
//var baseurl = 'http://localhost/miscout/';
var d = new Date();
var last;// = d.getMilliseconds();

//var last = timeStamp;
var diff;


window.onclick = function (event) {
	if (last) {
		diff = event.timeStamp - last;
		//alert(event.timeStamp);
		if (diff > 900000) {
			location.reload();
		}
	}
	else { last = event.timeStamp; }
}








/*USER LOGIN AUTHOR GIRISH 28 OCT 2018*/
$("#do_loginbtn").attr("disabled", "disabled");
//$("#txt_email").blur(check_loginemail)

function check_loginemail() {
	var re = /[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}/igm;
	var email = $("#txt_email").val();
	if (email == "") {
		$("#err_txt_email").text("Please enter your email id").fadeIn().delay('slow').fadeOut(3000);
		return false;
	}
	/*else if (!re.test(email)) {
		$("#err_txt_email").text("Invalid email id").fadeIn().delay('slow').fadeOut(3000);
		return false;
	}*/
	else {
		$("#err_txt_email").text("");
		return true;
	}
}
$("#txt_pass").blur(check_password);
function check_password() {
	if ($("#txt_pass").val() == "") {
		//$("#do_loginbtn").attr("disabled", "disabled");
		$('#err_txt_pass').text("Please Enter Password!").fadeIn().delay('slow').fadeOut(5000);
		return false;
	}
	else {
		$("#err_txt_pass").text("");
		return true;
	}

}
/*Check captcha AUTHOR GIRISH 28 Oct 2018*/
$("#total_email").blur(check_emailcaptcha);
//$("#total_email").keyup(check_captcha);
function check_emailcaptcha() {
	var first = $("#first").val();
	var second = $("#second").val();
	var total = $("#total_email").val();
	var ans = parseInt(first) + parseInt(second);
	//alert(ans);
	if ($("#total_email").val() == "") {
		$('#err_txt_captcha').text("Please Enter Answer!").fadeIn().delay('slow').fadeOut(3000);
		return false;
	}
	else if (ans != total) {
		$('#err_txt_captcha').text("Please Enter Correct Answer!").fadeIn().delay('slow').fadeOut(3000);
		return false;
	}
	else {
		$('#err_txt_captcha').text("");
		if (check_password()) {

			$("#do_loginbtn").removeAttr("disabled");
			//$("#admlogin").removeAttr("disabled");

		}
		return true;
	}
}
function do_login() {

	var errflag = 0;
	var csrf_token = $('#csrf_token').val();
	var csrf_token_hash = $('#csrf_hash').val();

	//var str=$("#frm_login").serialize();
	if (check_password() && check_emailcaptcha()) {


		//alert(baseurl);
		$.ajax({
			url: baseurl + 'login/do_login/',
			type: 'POST',
			data: { user_name: $('#txt_email').val(), user_password: $('#txt_pass').val() },
			success: function (data) {
				//alert(data);
				if (data == 'error') {
					//alert("error");
					$('#txt_pass').val('');
					$('#err_lgn').text("Oops something's wrong. Please Retry!").fadeIn().delay('slow').fadeOut(3000);
				}
				else if (data == 'invalid') {
					//alert("invalid");
					$('#txt_pass').val('');
					$('#err_lgn').text("Invalid Email/Password!").fadeIn().delay('slow').fadeOut(3000);
				}
				else if (data == '2') {
					//alert("invalid");
					$('#txt_pass').val('');
					$('#err_lgn').text("You already loged in").fadeIn().delay('slow').fadeOut(3000);
				}
				else if (data == 'Admin' || data == 'logged') {
					window.location.href = baseurl + 'Request/requestcourier';
				}
				else if (data == 'Employee' || data == 'logged') {
					window.location.href = baseurl + 'Request/requestcourier';
				}
				else if (data == 'DeskUser' || data == 'logged') {
					window.location.href = baseurl + 'Request/requestcourier';
				}
				else if (data == '1') {
					alert("Please Check Your Email for OTP");
					window.location.href = baseurl + 'login/otp';
				}
				/* else if(data=='success')
				{
					window.location.href=baseurl+'routes/home';
				} */
				else {
					$('#txt_pass').val('');
					$('#err_lgn').text(data).fadeIn().delay('slow').fadeOut(3000);
					/*setTimeout(function(){
					   window.location.reload(1);
					},1000);*/
				}
			}
		});

	}


	return false;
}
function otp_verification() {
	var errflag = 0;
	//var csrf_token = $('#csrf_token').val();
	//var csrf_token_hash = $('#csrf_hash').val();


	if (!$('#txt_otp').val()) {
		errflag = 1;
		$('#err_txt_otp').text("Please enter OTP!");
	}
	else {
		$('#err_txt_otp').text('');
	}



	//var str=$("#frm_login").serialize();

	if (errflag == 0) {
		//alert(baseurl);
		$.ajax({
			url: baseurl + 'login/otp_verified/',
			type: 'POST',
			data: { emp_otp: $('#txt_otp').val() },
			success: function (data) {
				//alert(data);
				if (data == '3') {
					//alert("error");
					$('#txt_pass').val('');
					$('#err_lgn').text("Invalid OTP enter please try again");
					$('#txt_otp').val('');
				}
				else if (data == '2') {
					window.location.href = baseurl + 'login/';
				}

				else {
					$('#txt_pass').val('');
					$('#err_lgn').text(data);
					setTimeout(function () {
						window.location.reload(1);
					}, 1000);
				}
			}
		});
	}

	return false;
}
function resentotp() {
	//alert(2323);
	$.ajax({
		url: baseurl + 'login/otp_reset/',
		type: 'POST',
		success: function (data) {
			//alert(data);
			if (data == '2') {
				//alert("error");
				$('#txt_otp').val('');
				$('#err_txt_otp').text("Oops something's wrong. Please Retry!");
			}
			else {
				$('#err_lgn').text("Please check your email for OTP.");

			}
		}
	});
}

function callLogOut() {

	if (window.location.href.indexOf('login') >= 0 || $('.login-box').length) return;

	var validNavigation = false;

	(function ($) {
		/**
		  * This javascript file checks for the brower/browser tab action.
		  * It is based on the file menstioned by Daniel Melo.
		  * Reference: http://stackoverflow.com/questions/1921941/close-kill-the-session-when-the-browser-or-tab-is-closed
		  */

		//var validNavigation = false;
		function endSession() {
			// Browser or broswer tab is closed
			// Do sth here ...
			//alert("bye");
			return "Session will be clear now";
		}

		function wireUpEvents() {
			/*
			* For a list of events that triggers onbeforeunload on IE
			* check http://msdn.microsoft.com/en-us/library/ms536907(VS.85).aspx
			*/
			if (localStorage.getItem("reloadPage")) {
				localStorage.removeItem("reloadPage");
				// alert("again redirect....++===")
				//window.location.href = "";
			}

			// Attach the event keypress to exclude the F5 refresh
			$(document).on('keypress', function (e) {
				//console.log(e.keyCode,"e.keyCode")
				if ((e.which || e.keyCode) == 116) {
					validNavigation = false;
					window.onunload = window.onbeforeunload = null;
				}
			});

			// Attach the event click for all links in the page
			$(document).on("click", "a", function () {
				validNavigation = true;
				window.onunload = window.onbeforeunload = null;
			});

			// Attach the event submit for all forms in the page
			$("form").on("submit", function () {
				validNavigation = true;
				window.onunload = window.onbeforeunload = null;
			});

			// Attach the event click for all inputs in the page
			$("input[type=submit], #do_loginbtn, #admlogin, input[type=button], button").on("click", function () {
				validNavigation = true;
			});

			// Attach the event submit for all cancel button in the page
			$("input[type=button][name='cancel']").on("click", function(){
				validNavigation = true;
				window.onunload = window.onbeforeunload = null;
			});
		}

		// Wire up the events as soon as the DOM tree is ready
		$(document).ready(function () {
			//alert("calling"+ new Date() + "==" + validNavigation);
			validNavigation = false;
			wireUpEvents();
		});

		// A jQuery event (I think), which is triggered after "onbeforeunload"
		$(window).on("unload", function (e) {
			//console.log("validNavigation", validNavigation)
			if (!validNavigation) {
				console.log(logoutUrl, "Xhr onload", event);
				var logoutUrl = jQuery("#aLogOut").attr("href");
				localStorage.setItem("reloadPage", true);
				$.post(logoutUrl, function (data) {
					//jQuery.get("https://forum.jquery.com/topic/how-to-invoke-jquery-ajax-call-on-tab-browser-close", function() {
					//debugger;
					//window.location.href = "";
					//validNavigation = false;
				}, "json");
			}
			else {
				validNavigation = true;
			}
		});

	})(jQuery);

	///*
	window.onunload = window.onbeforeunload = function (event) {
		//console.log("validNavigation custom.js", validNavigation);

		if (!validNavigation) {
			//console.log(logoutUrl, "Xhr onload", event);

			var logoutUrl = jQuery("#aLogOut").attr("href");
			localStorage.setItem("reloadPage", true);
			jQuery.post(logoutUrl, function (data) {
				//jQuery.get("https://forum.jquery.com/topic/how-to-invoke-jquery-ajax-call-on-tab-browser-close", function() {
				//debugger;
				//window.location.href = "";
				validNavigation = false;
			}, "json");
		}
		else {
			validNavigation = true;
		}

		return "User state cleared.Relogin to perform actions on load .";
	}

	//*/

}

//callLogOut();