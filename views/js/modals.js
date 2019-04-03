/**
 * Placeholder for JS
 *
 * @author Payright
 * @copyright 2016-2019 https://www.payright.com.au
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

    var modal = document.getElementById('myModal');
    var opener = document.getElementById("opener");

    var modal1 = document.getElementById("PayrightHowitWorksmodalPopup");
    var modal2 = document.getElementById("PayrightHowitWorksmodalPopup2");
  
    var close = document.getElementById("close");
    // var modaltype = document.getElementById("opener").className;

    if( opener.className == "payright-modal-popup-trigger"){


      opener.onclick = function (event) {
       
        modal.style.display = "block";
        modal1.style.display = "none";

      }

      close.onclick = function (event) {
         modal.style.display = "none";
      
      }
      window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    }
else {
      
      opener.onclick = function (event) {
       
        modal.style.display = "block";
        modal2.style.display = "none";

      }

      close.onclick = function (event) {
         modal.style.display = "none";
      
      }
      window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
      }
    }


//   }

// });