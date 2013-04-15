// JavaScript Document
function passwordStrength(password)

{

	var desc = new Array();

	desc[0] = "Très faible";

	desc[1] = "Faible";

	desc[2] = "Passable";

	desc[3] = "Moyenne";

	desc[4] = "Forte";

	desc[5] = "Très forte";



	var score   = 0;



	//if password bigger than 6 give 1 point

	if (password.length > 4) score++;



	//if password has both lower and uppercase characters give 1 point	

	if ( ( password.match(/[a-z]/) ) && ( password.match(/[A-Z]/) ) ) score++;



	//if password has at least one number give 1 point

	if (password.match(/\d+/)) score++;



	//if password has at least one special caracther give 1 point

	if ( password.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/) )	score++;



	//if password bigger than 12 give another 1 point

	if (password.length > 8) score++;



	 document.getElementById("passwordDescription").innerHTML = desc[score];

	 document.getElementById("passwordStrength").className = "strength" + score;

}