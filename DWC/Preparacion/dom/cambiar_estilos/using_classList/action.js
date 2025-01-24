/**
 * displayListOfCheckedItems()
 * Obtiene los elementos seleccionados, muestra sus valores y marca 
 * los ítems seleccionados con un estilo especial.
 */
function displayListOfCheckedItems() {
    // all inputs that have been checked
    var listOfSelectedValues="";
    
    var list = document.querySelectorAll("#fruits input:checked");//obtiene todos los inputs marcados
    list.forEach(function(elm) {//itera sobre cada elemento de la list
      listOfSelectedValues += elm.value + " ";
      
      // get the li parent of the current selected input
      var liParent = elm.parentNode;//encuentra su elemento padre (padre de #fruits input:checked
                                    // -> son los li de fruits, pero sólo los marcados)
      // add the CSS class .checked
      liParent.classList.add("checked");//añade la clase css al elemento padre
    });
    document.body.append("You selected: " + listOfSelectedValues);//muestra los valores seleccionados en la página
  }
  
  /**
   * function reset()
   *  Restablece la lista desmarcando todos los checkboxes y quitando cualquier estilo aplicado.
   */
  function reset() {
    var list = document.querySelectorAll("#fruits input"); //obtinen todos los inpus de la lista fruit
    list.forEach(function(elm) {//itera sobre cada elemento de list
      // uncheck
      elm.checked = false;//para cada elemento, lo desmarca. Es una propiedad booleana del elemento input
                        //se usa para cambiar el estado del checkbox (marcado o desmarcado). No tiene nada 
                        //que ver con las clases CSS. Por lo tanto, aplicar classList.remove("checked") 
                        //sobre elm no tiene sentido, ya que el checkbox no tiene la clase "checked"; 
                        //esa clase pertenece al <li>.
      
      // remove CSS decoration
      var liParent = elm.parentNode;//Es el elemnto li que contiene el checkbox <input>
      liParent.classList.remove("checked");//elimina la calse css checked. Elimina la clse css de un elemto del DOM
      /*la clase checked está diseñada para aplicarse sobre el elemnto li, para estilar toda la fila del chekbox
      marcado, no sólo el checkbox en si.
      */
    });
  }
  