var items_cargar = document.getElementsByTagName("li") //Seleccionas las imagenes a cargar 
        var moverse = document.getElementById("contendor-barra").offsetWidth / items_cargar.length;
       
       for(var x=0; x<items_cargar.length;x++){
            var pic=new Image();
            pic.src= items_cargar[x].src;
            if(pic.complete) cargada(); else {pic.onload= cargada; pic.onError = cargada };
        }
        
        function cargada(){ // Funcion que se ejecuta cada vez que carga una imagen
       
               var barra = document.getElementById("barra");
             var total_move = barra.offsetLeft+moverse
//             alert(total_move)

             barra.style.left = total_move + "px"
             
             
             if(barra.style.left.replace("px","") >= 0){                 
                document.getElementById("pantalla_blanca").style.display = "none";
             }
             
            
        }