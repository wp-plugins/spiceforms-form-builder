

function disconnect_link()
{
	xmlhttp=new XMLHttpRequest();
	xmlhttp.open("GET","?page=spiceforms&disconnect=true",true);
	xmlhttp.send();
	document.getElementById("connect").style.display = "inline-block";
	document.getElementById("disconnect").style.display = "none";
	document.getElementById("connect-lbl").style.display = "none";
}
function submit_btn_func()
{
	document.getElementById("connect").disabled = true;
	document.getElementById("img").style.display ='inline-block';
	frm.submit();
}