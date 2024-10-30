/*
+----------------------------------------------------------------+
|	WordPress Plugin: Collapsible Elements    								     |
|	http://deuced.net/wpress/collapsible-elements/						     |
|	File Information:																	             |
|	- Collapsible Elements Javascript  												     |
|	- wp-content/plugins/collapsible-elements/xcelements.js        |
| This code is based on Arvind Satyanarayan example which        |
| toggles the visibility of multiple  elements on a page         |
| http://blog.movalog.com/a/javascript-toggle-visibility/        |
+----------------------------------------------------------------+
*/
function xcollapse(id) 
{ 
      var ccommid = document.getElementById(id); 
      if(ccommid !== null) {
        if(ccommid.style.display == 'block') {
        ccommid.style.display = 'none';
        }
      else { ccommid.style.display = 'block';
        }
      }
}