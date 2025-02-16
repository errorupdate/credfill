document.addEventListener('DOMContentLoaded', function() {
    const bookingForm = document.getElementById('bookingForm');
    const serviceInput = document.getElementById('service');
    const costInput = document.getElementById('cost');

    // Get URL parameters and set values
    const urlParams = new URLSearchParams(window.location.search);
    serviceInput.value = urlParams.get('service') || '';
    costInput.value = urlParams.get('cost') || '';

    bookingForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(bookingForm);

        fetch('send_booking.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                window.location.href = data.redirect_url;
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again later.');
        });
    });

    // Document upload handling
    const addDocumentButton = document.querySelector('.addDocumentButton');
    const documentList = document.querySelector('.documentList');
    
    if (addDocumentButton) {
        addDocumentButton.addEventListener('click', function() {
            const newLi = document.createElement('li');
            newLi.innerHTML = `
                <input type="file" name="document[]" class="input-field" multiple />
                <button class="btn btn-primary removeDocumentButton" type="button">
                    <ion-icon name="remove-outline"></ion-icon>
                </button>
            `;
            documentList.appendChild(newLi);

            const removeButton = newLi.querySelector('.removeDocumentButton');
            removeButton.addEventListener('click', function() {
                newLi.remove();
            });
        });
    }
});