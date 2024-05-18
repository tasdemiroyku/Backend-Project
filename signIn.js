const signUpButton = document.getElementById('signUp');
const signInButton = document.getElementById('signIn');
const container = document.getElementById('container');

signUpButton.addEventListener('click', () => {
	container.classList.add("right-panel-active");
});

signInButton.addEventListener('click', () => {
	container.classList.remove("right-panel-active");
});

$(function(){
	$("#switchBtn").on("click", function(){
		let title = $("#createTitle").text()
		if(title == "Create Consumer"){
			title = "Create Market";
			$("#nameType").attr("placeholder", "Market Name")
			$("#type").attr("value", "M")
		}
		else{
			title = "Create Consumer";
			$("#nameType").attr("placeholder", "Consumer Name")
			$("#type").attr("value", "C")
		}
		$("#createTitle").text(title);
	})
})