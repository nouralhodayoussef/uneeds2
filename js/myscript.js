function loading()
{
  const films = [
    'images/image.jpg', 
    'images/logo.jpg',
    'images/image.jpg',
    'images/logo.jpg']  ;

    for (var i = 0 ; i < films.length ; i++)
        {                             
            img = document.createElement("img");                   
            img.src = films[i];      
            img.setAttribute('id');
            document.querySelector("#gallery").appendChild(img);                                      
          }                           
}

 
