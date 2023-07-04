/* Bootstrap Tooltips */
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
})

/* Bootstrap Popover */
var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
  return new bootstrap.Popover(popoverTriggerEl)
})


/* Notifications */

if (document.querySelectorAll("a.notification-link")) {
  document.querySelectorAll("a.notification-link").forEach(element => {
    element.addEventListener('click', function (e) {
      e.preventDefault();
      markAsRead(element);
    });
  });
}


if (document.querySelector(".clear-notifications")) {
  document.querySelector(".clear-notifications").addEventListener("click", function () {
    data = {
      request: "markAsReadAll",
    }

    request(JSON.stringify(data), "http://localhost:8000/markasreadall", function () {

      const response = JSON.parse(this.responseText);
      if (response.state == "success") {
        if (document.querySelectorAll(".notification-item")) {
          document.querySelectorAll(".notification-item").forEach(element => {
            element.remove();
          });
        }
        document.querySelector(".bell-badge").style.display = "none";
        document.querySelector("div.clear-notifications").style.display = "none";
        document.querySelector("div.notifications-empty").style.display = "block";
      } else {
        alert(response.error)
      }

    })
  });
}
function markAsRead(element) {
  data = {
    request: "markAsRead",
    notification: element.getAttribute("notification"),
  }

  request(JSON.stringify(data), "http://localhost:8000/markasread", function () {

    const response = JSON.parse(this.responseText);
    if (response.state == "success") {
      location.href = element.getAttribute("href");
    }

  })
}



/* Add Favorites */
Array.from(document.getElementsByClassName("add-favorites-icon")).forEach(element => {
  element.addEventListener("click", function addFavorites() {
    data = {
      _token: token, //token blade sayfasında belirleniyor
      request: "addFavorites",
      book: this.getAttribute("book"),
    }
    request(JSON.stringify(data), addFavoritesUrl, function () {
      const response = JSON.parse(this.responseText);

      if (response.state == "success") {
        //Remove Tooltip  
        Array.from(document.getElementsByClassName("tooltip")).forEach(element => {
          element.remove();
        });
        // Remove AddFavorite Btn & InFavorites Icon
        const addedIcon = document.getElementById(element.getAttribute("book") + "-star");
        element.remove();
        addedIcon.classList.remove("d-none");
      } else {
        location.reload();
      }
    })
  });
});

/* Add Bookshelf */
Array.from(document.getElementsByClassName("add-bookshelf-icon")).forEach(element => {
  element.addEventListener("click", function addBookshelf() {
    data = {
      _token: token, //token blade sayfasında belirleniyor
      request: "addBookshelf",
      book: this.getAttribute("book"),
    }
    request(JSON.stringify(data), addBookshelfUrl, function () {
      const response = JSON.parse(this.responseText);

      if (response.state == "success") {
        //Remove Tooltip  
        Array.from(document.getElementsByClassName("tooltip")).forEach(element => {
          element.remove();
        });

        const addedIcon = document.getElementById(element.getAttribute("book") + "-inshelf");
        addedIcon.classList.remove("d-none");
        element.remove(); //Remove add-bookshelf btn
      } else {
        location.reload();
      }
    })
  });
});


function request(data, url, onloadFunction) {
  const xmlhttp = new XMLHttpRequest();
  xmlhttp.onload = onloadFunction;
  xmlhttp.open("POST", url);
  xmlhttp.setRequestHeader("X-CSRF-TOKEN", token);
  xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xmlhttp.send(data);
}

