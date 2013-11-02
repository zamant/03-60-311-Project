 /*function deletePost(postnum){
			document.getElementById(postnum).submit();
		}*/
		function changeCSS(sel,set){
		//CSS changing is completely unnecessary at this point
			 var value = sel.options[sel.selectedIndex].value;
			 document.getElementById('coloursheet').href = value;
			 if (set){
				setCookie('colorsheet',value,30);
			 }
		}
		function readCookie(name) {//Copied from stackoverflow
		//Saves trouble when reading cookies
			var nameEQ = name + "=";
			var ca = document.cookie.split(';');
			for(var i=0;i < ca.length;i++) {
				var c = ca[i];
				while (c.charAt(0)==' ') c = c.substring(1,c.length);
				if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
			}
			return null;
		}
		function setCookie(c_name,value,exdays)
		{//Copied from w3schools
		//Saves trouble when setting cookies
			var exdate=new Date();
			exdate.setDate(exdate.getDate() + exdays);
			var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
			document.cookie=c_name + "=" + c_value;
		}
		function checkColourCookie(){
			var cookie = readCookie('colorsheet');
			if (cookie != null){
				var sel = document.getElementById('colourselect');
				for(var i=0;i<sel.options.length;i++){
					if (sel.options[i].value == cookie) {
						sel.selectedIndex = i;
						break;
					}
				}
				changeCSS(sel,false);
			}
		}
		function deleteAd(redirect,id){ 
		  if(confirm("Are you sure you want to delete ad #"+id+"?")==true)
				   window.location=redirect+"del="+id;
			return false;
		}