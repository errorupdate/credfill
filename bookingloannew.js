document.addEventListener('DOMContentLoaded', function() {
    const bookingForm = document.getElementById('bookingForm');
    const serviceInput = document.getElementById('service');
    const costInput = document.getElementById('cost');
    const pincodeInput = document.getElementById('pincode');
    const districtInput = document.getElementById('district');
    const stateInput = document.getElementById('state');

    // Get URL parameters and set values
    const urlParams = new URLSearchParams(window.location.search);
    serviceInput.value = urlParams.get('service') || '';
    costInput.value = urlParams.get('cost') || '';

    // Create success message elements
    bookingForm.addEventListener('submit', function(event) {
    event.preventDefault();

    const formData = new FormData(bookingForm);
    
    fetch('send_bookingloannew.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Just redirect to the success page
            window.location.href = data.redirect_url;
        } else {
            alert(data.message || 'An error occurred. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again later.');
    });
});

    // Rest of your existing code for pincode fetching remains the same
    function fetchLocation() {
        const pincode = pincodeInput.value;
        if (pincode.length === 6) {
            districtInput.value = 'Fetching...';
            stateInput.value = 'Fetching...';
            fetch(`https://api.postalpincode.in/pincode/${pincode}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Pincode API response:', data);
                    if (data[0].Status === "Success") {
                        const locationData = data[0].PostOffice[0];
                        districtInput.value = locationData.District;
                        stateInput.value = locationData.State;
                    } else {
                        districtInput.value = '';
                        stateInput.value = '';
                        alert("Invalid PIN Code or data not found.");
                    }
                })
                .catch(error => {
                    console.error("Error fetching location data:", error);
                    districtInput.value = '';
                    stateInput.value = '';
                    alert("Error fetching location data. Please try again.");
                });
        }
    }

    pincodeInput.addEventListener('input', fetchLocation);

    // Initialize form with any existing values
    const initializeForm = () => {
        if (pincodeInput.value.length === 6 && !districtInput.value) {
            fetchLocation();
        }
    };

    initializeForm();
});