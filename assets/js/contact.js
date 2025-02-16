document
  .getElementById("contact-form")
  .addEventListener("submit", async (event) => {
    event.preventDefault();
    const name = document.getElementById("name").value;
    const email = document.getElementById("email").value;
    const phone = document.getElementById("phone").value;
    const message = document.getElementById("message").value;
    const response = await fetch("https://sheetdb.io/api/v1/r525e0v3rdvhz", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ name, email, phone, message }),
    });
    const data = await response.json();
    if (response.ok) {
      Swal.fire({
        title: "Success",
        text: "Your Response has been submitted successfully!, We will get back to you soon.",
        icon: "success",
        confirmButtonText: "Ok",
      });
      document.getElementById("contact-form").reset();
    } else {
      Swal.fire({
        title: "Error",
        text: "An error occurred while submitting your response, Please try again later.",
        icon: "error",
        confirmButtonText: "Ok",
      });
    }
  });
