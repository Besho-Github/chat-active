$(function(){
  
  var smilies = { /*
    smiley     image_url          title_text              alt_smilies           */
    ":)":    [ "smile.gif",       "happy",                ":-)"                 ],
	":))":    [ "csmile.gif",      "smile",                ";>"                  ],
    ":(":    [ "sad.gif",         "sad",                  ":-("                 ],
	":o":	 [ "shock.gif", 	  "shocked",			  ":0"					],
	":D":	 [ "grin.gif", 	  	  "grin",			      "xD"					],
	":P":	 [ "tongue.gif", 	  "tongue",			      ";P"					],
	"~:)":	 [ "chicken.gif", 	  "chicken",			  "~;>"					]
  };
  
  emotify.emoticons( 'template/smilies/', smilies );
  
});