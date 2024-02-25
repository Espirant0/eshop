lightbox.option({
	'resizeDuration': 10,
	'wrapAround': true,
	'albumLabel': "%1/%2",
});

function dropdown() {
	document.getElementById("dropdown").classList.toggle("show");
}

// Close the dropdown if the user clicks outside of it
window.onclick = function (event) {
	if (!event.target.matches('.account_img')) {

		var dropdowns = document.getElementsByClassName("account_links");
		var i;
		for (i = 0; i < dropdowns.length; i++) {
			var openDropdown = dropdowns[i];
			if (openDropdown.classList.contains('show')) {
				openDropdown.classList.remove('show');
			}
		}
	}
}