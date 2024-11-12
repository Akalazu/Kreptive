// Google Translator Utilities
function googleTranslateElementInit() {
  new google.translate.TranslateElement(
    {
      pageLanguage: "en",
    },
    "google_translate_element"
  );
}


// Get the current theme preference from localStorage
const currentTheme = localStorage.getItem("data-theme");

// console.log(currentTheme);
if (currentTheme && !document.body.classList.contains(currentTheme)) {
  document.body.classList.add(currentTheme);
  // console.log("doesn!t exist");
}

// Use icon button in homepage to toggle theme
// Use icon button in homepage to toggle theme
var icon = document.querySelector("#iconn");
if (icon) {
  icon.addEventListener("click", function () {
    document.body.classList.toggle("dark");

    if (document.body.classList.contains("dark")) {
      window.localStorage.setItem("data-theme", "dark");
      // document.body.classList.remove("light");
      icon.src = "img/sun_3.png";
    } else {
      icon.src = "img/moon_2.png";
      document.body.classList.remove("dark");
      window.localStorage.setItem("data-theme", "light");
    }
  });
}

// Add event listener to theme toggle buttons
// document.addEventListener("DOMContentLoaded", function () {
//   if (document.body.classList.contains("dark")) {
//     document.body.classList.add("dark");
//     icon.src = "img/sun_3.png";
//   } else {
//     icon.src = "img/moon_2.png";
//   }
// });

var preview_page = document.querySelector(".image__preview");

function previewImage(event) {
  var image_SRC = URL.createObjectURL(event.target.files[0]);
  var image_created = document.createElement("img");
  image_created.src = image_SRC;
  image_created.attr = "Uploaded Image";
  image_created.classList.add("image_preview");

  preview_page.innerHTML = "";
  preview_page.innerHTML = "<h5 class='my-3'>Upload Preview</h5>";
  preview_page.appendChild(image_created);
}

(function () {
  $("#fname_error_message").hide();
  $("#lname_error_message").hide();
  $("#username_error_message").hide();
  $("#email_error_message").hide();
  $("#password_error_message").hide();

  $("#form_uname").focusout(function () {
    check_uname();
  });
  $("#form_fname").focusout(function () {
    check_name("#form_fname");
  });
  $("#form_lname").focusout(function () {
    check_name("#form_lname");
  });
  $("#form_email").focusout(function () {
    check_email();
  });
  $("#form_password").focusout(function () {
    check_password();
  });

  function check_name(input) {
    var e_message = input + "_message";
    var err = input + "_error";

    var pattern = /^[a-z A-Z]*$/;
    var name = $(input).val();
    if (pattern.test(name) && name !== "") {
      $(e_message).val("");
      $(input).css("border", "2px solid #e6e8ec");
      $(err).val("false");
    } else {
      $(e_message).html("Should contain only Characters");
      $(e_message).show();
      $(input).css("border", "1px solid #F90A0A");
    }
  }

  function check_uname() {
    var pattern = /^[a-zA-Z0-9]*$/;
    var username = $("#form_uname").val();

    if (pattern.test(username) && username !== "") {
      $("#username_error_message").hide();
      $("#form_uname").css("border", "2px solid #e6e8ec");
      $("#form_uname_error").val("false");
    } else {
      $("#username_error_message").html("Username cannot contain space");
      $("#form_uname_error").val("");
      $("#username_error_message").show();
      $("#form_uname").css("border", "1px solid #F90A0A");
    }
  }

  function check_password() {
    var password_length = $("#form_password").val().length;
    if (password_length > 1 && password_length < 6) {
      $("#password_error_message").html(
        "Password must be at least 6 characters"
      );
      $("#form_password_error").val("");
      $("#password_error_message").show();
      $("#form_password").css("border", "1px solid #F90A0A");
    } else {
      $("#form_password").css("border", "2px solid #e6e8ec");
      $("#form_password_error").val("false");
      $("#password_error_message").hide();
    }
  }

  function check_email() {
    var pattern = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    var email = $("#form_email").val();
    if (pattern.test(email) && email !== "") {
      $("#form_email").css("border", "2px solid #e6e8ec");
      $("#email_error_message").hide();
      $("#form_email_error").val("false");
    } else {
      $("#email_error_message").html("Invalid Email");
      $("#email_error_message").show();
      $("#form_email").css("border", "1px solid #F90A0A");
    }
  }
});
