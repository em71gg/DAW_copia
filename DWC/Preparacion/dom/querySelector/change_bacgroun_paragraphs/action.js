function changeBackGroundOfPs(id) {
    var paragraphs = document.querySelectorAll("#" + id + " p");
  
    // Another way to iterate on all elements in a collection
    for (var i = 0; i < paragraphs.length; i++ ) {
      paragraphs[i].style.backgroundColor = "lightGreen";
    }

    /*es lo mismo que esto

      paragraphs.forEach(function(el){
        el.style.backgroundColor = "pink";
      
    });*/
  }
  