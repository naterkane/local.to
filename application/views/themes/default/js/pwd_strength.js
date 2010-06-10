// Password strength meter v1.0
// Matthew R. Miller - 2007
// www.codeandcoffee.com
// Based off of code from  http://www.intelligent-web.co.uk

// Settings
// -- Toggle to true or false, if you want to change what is checked in the password
var bCheckNumbers = true;
var bCheckUpperCase = true;
var bCheckLowerCase = true;
var bCheckPunctuation = true;
var nPasswordLifetime = 365;

// Check password
function checkPassword(strPassword)
{
	// Reset combination count
	nCombinations = 0;
	
	// Check numbers
	if (bCheckNumbers)
	{
		strCheck = "0123456789";
		if (doesContain(strPassword, strCheck) > 0) 
		{ 
        		nCombinations += strCheck.length; 
    		}
	}
	
	// Check upper case
	if (bCheckUpperCase)
	{
		strCheck = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		if (doesContain(strPassword, strCheck) > 0) 
		{ 
        		nCombinations += strCheck.length; 
    		}
	}
	
	// Check lower case
	if (bCheckLowerCase)
	{
		strCheck = "abcdefghijklmnopqrstuvwxyz";
		if (doesContain(strPassword, strCheck) > 0) 
		{ 
        		nCombinations += strCheck.length; 
    		}
	}
	
	// Check punctuation
	if (bCheckPunctuation)
	{
		strCheck = ";:-_=+\|//?^&!.@$£#*()%~<>{}[]";
		if (doesContain(strPassword, strCheck) > 0) 
		{ 
        		nCombinations += strCheck.length; 
    		}
	}
	
	// Calculate
	// -- 500 tries per second => minutes 
    	var nDays = ((Math.pow(nCombinations, strPassword.length) / 500) / 2) / 86400;
 
	// Number of days out of password lifetime setting
	var nPerc = nDays / nPasswordLifetime;
	
	return nPerc;
}
 
// Runs password through check and then updates GUI 
function runPassword(strPassword, strFieldID) 
{
	// Check password
	nPerc = checkPassword(strPassword);
	 // Get controls
    	var ctlBar = document.getElementById(strFieldID + "_bar"); 
    	var ctlText = document.getElementById(strFieldID + "_text");
    	if (!ctlBar || !ctlText)
    		return;

    	// Set new width
    	var nRound = Math.round(nPerc * 100);
	if (nRound < (strPassword.length * 5)) 
	{ 
		nRound += strPassword.length * 5; 
	}
	if (nRound > 100)
		nRound = 100;
    	ctlBar.style.width = nRound + "%";
 
 	// Color and text
 	if (nRound > 95)
 	{
 		strText = "Very Secure";
 		strColor = "#3bce08";
 	}
 	else if (nRound > 65)
 	{
 		strText = "Secure";
 		strColor = "orange";
	}
 	else if (nRound > 40)
 	{
 		strText = "Mediocre";
 		strColor = "#ffd801";
 	}
 	else
 	{
 		strColor = "red";
 		strText = "Insecure";
 	}
	ctlBar.style.backgroundColor = strColor;
	ctlText.innerHTML = "<span style='color: " + strColor + ";'>" + strText + "</span>";
}
 
// Checks a string for a list of characters
function doesContain(strPassword, strCheck)
 {
    	nCount = 0; 
 
	for (i = 0; i < strPassword.length; i++) 
	{
		if (strCheck.indexOf(strPassword.charAt(i)) > -1) 
		{ 
	        	nCount++; 
		} 
	} 
 
	return nCount; 
}