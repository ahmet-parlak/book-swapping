/* Remove Favorites */
Array.from(document.getElementsByClassName("remove-book-btn")).forEach(element => {
    element.addEventListener("click", function removeFavorites() {
      data = {
        _token: token, //token blade sayfasında belirleniyor
        request: "removeFavorites",
        book: this.getAttribute("book"),
      }
      request(JSON.stringify(data), function () {
        const response = JSON.parse(this.responseText);
  
        if (response.state == "success") {
          //Remove Tooltip  
          Array.from(document.getElementsByClassName("tooltip")).forEach(element => {
            element.remove();
          });
          // Remove Book
          location.reload();
        } else {
          location.reload();
        }
      })
    });
  });
  
  
  
  function request(data, onloadFunction) {
    const xmlhttp = new XMLHttpRequest();
    xmlhttp.onload = onloadFunction;
    xmlhttp.open("POST", removeFavoritesUrl);
    xmlhttp.setRequestHeader("X-CSRF-TOKEN", token);
    xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlhttp.send(data);
  }